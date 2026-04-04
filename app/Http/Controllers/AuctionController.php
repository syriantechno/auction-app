<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\User;
use App\Notifications\NewBidPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuctionController extends Controller
{
    /**
     * Show the detailed auction page for a specific car.
     */
    public function show(Auction $auction)
    {
        // Load the related car, its latest inspection, and all existing bids
        $auction->load([
            'car.latestInspection',
            'bids' => function ($q) {
                $q->latest()
                    ->limit(5)
                    ->select(['id', 'auction_id', 'user_id', 'amount', 'created_at'])
                    ->with(['user:id,name']);
            }
        ]);
        $auction->loadCount('bids');

        return view('auctions.show', compact('auction'));
    }

    /**
     * List all coming-soon and active auctions with search capability.
     */
    public function index(Request $request)
    {
        $normalizedCatalog = $this->usesNormalizedCatalog();

        $query = Auction::whereIn('status', ['active', 'coming_soon'])
            ->with($normalizedCatalog ? ['car.brand', 'car.carModel', 'bids.user'] : ['car', 'bids.user'])
            ->withCount('bids')
            ->orderBy('start_at', 'asc');

        if ($request->filled('make')) {
            $make = $request->make;
            $query->whereHas('car', function ($q) use ($make) {
                $q->where('make', 'like', "%{$make}%");

                if (Schema::hasTable('brands') && Schema::hasColumn('cars', 'brand_id')) {
                    $q->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', "%{$make}%"));
                }
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('car', function ($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%")
                    ->orWhere('trim', 'like', "%{$search}%");

                if (Schema::hasTable('brands') && Schema::hasTable('car_models') && Schema::hasColumn('cars', 'brand_id') && Schema::hasColumn('cars', 'car_model_id')) {
                    $q->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->where('name', 'like', "%{$search}%"));
                }
            });
        }

        $auctions = $query->get();

        // --- التعديل الجديد لدعم تطبيق الموبايل ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($auctions->map(function ($auction) {
                $imageUrl = $auction->car->image_url ?? '';

                if ($imageUrl !== '' && !str_starts_with($imageUrl, 'http')) {
                    $imageUrl = url($imageUrl);
                }

                return [
                    'id' => $auction->id,
                    'title' => ($auction->car->brand?->name ?? $auction->car->make ?? 'Car') . ' ' . ($auction->car->carModel?->name ?? $auction->car->model ?? ''),
                    'description' => $auction->description ?? '',
                    'image_url' => $imageUrl !== '' ? $imageUrl : 'https://via.placeholder.com/400',
                    'current_price' => (double) ($auction->current_price ?? $auction->starting_price),
                    'starting_price' => (double) $auction->starting_price,
                    'end_time' => $auction->end_at ?? $auction->start_at, // تأكد من اسم حقل وقت الانتهاء
                    'car_make' => $auction->car->brand?->name ?? $auction->car->make ?? '',
                    'car_model' => $auction->car->carModel?->name ?? $auction->car->model ?? '',
                    'car_year' => (int) ($auction->car->year ?? 0),
                ];
            }));
        }
        // ---------------------------------------

        return view('auctions.index', compact('auctions'));
    }

    public function placeBid(Request $request, Auction $auction)
    {
        if (!auth()->check()) {
            if ($request->ajax()) return response()->json(['error' => 'Auth Required'], 401);
            return redirect()->route('login')->with('error', 'Please login to place a bid.');
        }

        try {
            $result = \DB::transaction(function () use ($request, $auction) {
                /** @var Auction $lockedAuction */
                $lockedAuction = Auction::where('id', $auction->id)->lockForUpdate()->first();

                // ── Bid Increment Validation ──────────────────────────────
                $actualCurrent = (float) ($lockedAuction->current_price ?? $lockedAuction->initial_price);
                $increment     = (float) ($lockedAuction->bid_increment ?? 500);
                $minRequired   = $actualCurrent + $increment;

                if ((float) $request->amount < $minRequired) {
                    throw new \Exception(
                        'Minimum bid is $' . number_format($minRequired) .
                        ' (current $' . number_format($actualCurrent) .
                        ' + increment $' . number_format($increment) . ')'
                    );
                }

                // ── Time Extension Logic (reads from Global System Settings) ──
                $timeExtended = false;
                $newEndAt     = $lockedAuction->end_at;

                $antiSnipeEnabled = \App\Models\SystemSetting::get('anti_snipe_enabled', '1') === '1';

                if ($antiSnipeEnabled && $lockedAuction->end_at && $lockedAuction->status === 'active') {
                    $secsLeft  = now()->diffInSeconds($lockedAuction->end_at, false);
                    $threshold = (int) \App\Models\SystemSetting::get('time_extension_threshold', 30);
                    $extension = (int) \App\Models\SystemSetting::get('time_extension_seconds', 20);

                    if ($secsLeft > 0 && $secsLeft <= $threshold) {
                        $newEndAt = $lockedAuction->end_at->addSeconds($extension);
                        $lockedAuction->update(['end_at' => $newEndAt]);
                        $timeExtended = true;
                    }
                }

                // ── Create Bid ────────────────────────────────────────────
                $bid = \App\Models\Bid::create([
                    'auction_id' => $auction->id,
                    'user_id'    => auth()->id(),
                    'amount'     => $request->amount,
                    'status'     => 'active',
                ]);

                // Update price FIRST so events carry the correct value
                $lockedAuction->update(['current_price' => $request->amount]);
                $lockedAuction->refresh()->loadCount('bids');

                // Broadcast real-time events
                event(new \App\Events\BidPlaced($bid, $lockedAuction));
                event(new \App\Events\AuctionUpdated($lockedAuction));

                // Notify all admins
                User::where('role', 'admin')
                    ->orWhereIn('email', ['admin@motorbazar.ae', 'admin@automazad.com'])
                    ->get()
                    ->each(fn($admin) => $admin->notify(new NewBidPlaced($bid, $lockedAuction)));

                return [
                    'current_price'           => (float) $request->amount,
                    'current_price_formatted' => number_format($request->amount),
                    'next_bid_amount'         => (float) ($request->amount + $increment),
                    'next_bid_formatted'      => '$' . number_format($request->amount + $increment),
                    'bid_increment'           => (float) $increment,
                    'bids_count'              => $lockedAuction->bids_count,
                    'time_extended'           => $timeExtended,
                    'new_end_at'              => $newEndAt?->toIso8601String(),
                    'extension_seconds'       => $timeExtended ? ($lockedAuction->time_extension_seconds ?? 20) : 0,
                ];
            });

            if ($request->ajax()) {
                return response()->json(array_merge(['success' => true], $result));
            }

            return redirect()->back()->with('success', 'Bid placed successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) return response()->json(['error' => $e->getMessage()], 422);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function sync(Auction $auction)
    {
        $auction->loadCount('bids');
        $actualPrice = (float) ($auction->current_price ?? $auction->initial_price);
        $increment   = (float) ($auction->bid_increment ?? 500);
        
        $latestBids = $auction->bids()
            ->latest()
            ->select(['id', 'auction_id', 'user_id', 'amount', 'created_at'])
            ->with(['user:id,name'])
            ->take(5)
            ->get()
            ->map(function($bid) {
            return [
                'user_name'    => $bid->user->name,
                'user_initial' => substr($bid->user->name, 0, 1),
                'amount'       => '$' . number_format($bid->amount),
                'time'         => $bid->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success'                  => true,
            'current_price'            => $actualPrice,
            'current_price_formatted'  => number_format($actualPrice),
            'next_bid_amount'          => $actualPrice + $increment,
            'next_bid_formatted'       => '$' . number_format($actualPrice + $increment),
            'bid_increment'            => $increment,
            'bids_count'               => $auction->bids_count,
            'latest_bids'              => $latestBids,
            'end_at'                   => $auction->end_at?->toIso8601String(),
            'status'                   => $auction->status,
            'time_extension_threshold' => (int) ($auction->time_extension_threshold ?? 30),
            'time_extension_seconds'   => (int) ($auction->time_extension_seconds   ?? 20),
        ]);
    }

    private function usesNormalizedCatalog(): bool
    {
        return Schema::hasTable('brands')
            && Schema::hasTable('car_models')
            && Schema::hasColumn('cars', 'brand_id')
            && Schema::hasColumn('cars', 'car_model_id');
    }
}
