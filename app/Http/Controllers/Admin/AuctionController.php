<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Car;
use App\Services\ReferenceCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuctionController extends Controller
{
    /**
     * Display the Auction Matrix with High-Performance Blade Fragments.
     */
    public function index(Request $request)
    {
        $normalizedCatalog = Schema::hasTable('brands')
            && Schema::hasTable('car_models')
            && Schema::hasColumn('cars', 'brand_id')
            && Schema::hasColumn('cars', 'car_model_id');

        $query = Auction::with($normalizedCatalog ? ['car.brand', 'car.carModel'] : ['car'])->latest();

        // High-Precision Filter Hub
        if ($search = $request->input('search')) {
            $query->whereHas('car', function($q) use ($search) {
                $q->where('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%")
                  ->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $auctions = $query->paginate(15)->withQueryString();

        // AJAX Delta Updates: Instant Fragment Sync
        if ($request->ajax()) {
            return view('admin.auctions._table', compact('auctions'))->render();
        }

        return view('admin.auctions.index', compact('auctions'));
    }

    public function create()
    {
        $cars = Car::latest()->take(100)->get();
        return view('admin.auctions.create', compact('cars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'initial_price' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|in:coming_soon,active,paused,closed',
        ]);

        $auction = Auction::create($validated);

        // Auto-generate reference code when going to Coming Soon or Active
        if (in_array($auction->status, ['coming_soon', 'active'])) {
            ReferenceCodeService::assignTo($auction);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Auction created.',
                'reference_code' => $auction->fresh()->reference_code
            ]);
        }

        return redirect()->route('admin.auctions.index')->with('success', 'Auction launched. Ref: ' . $auction->reference_code);
    }

    public function edit(Auction $auction)
    {
        $cars = Car::latest()->take(100)->get();
        if (!$cars->contains('id', $auction->car_id)) {
            $cars->prepend($auction->car);
        }
        return view('admin.auctions.edit', compact('auction', 'cars'));
    }

    public function update(Request $request, Auction $auction)
    {
        $validated = $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:coming_soon,active,paused,closed',
        ]);

        $auction->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Auction Re-calibrated']);
        }

        return redirect()->route('admin.auctions.index')->with('success', 'Auction modified.');
    }

    public function destroy(Auction $auction)
    {
        $auction->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Auction Purged']);
        }
        return redirect()->route('admin.auctions.index')->with('success', 'Auction archived.');
    }

    /**
     * Authorize a "Coming Soon" node to Go Live.
     */
    public function approve(Auction $auction, Request $request)
    {
        $duration = $request->input('duration', 20); // Default 20 mins

        $auction->update([
            'status'           => 'active',
            'start_at'         => now(),
            'end_at'           => now()->addMinutes($duration),
            'duration_minutes' => $duration
        ]);

        // Ensure ref code exists when going live
        $refCode = ReferenceCodeService::assignTo($auction);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Auction is now LIVE for {$duration} minutes.",
                'reference_code' => $refCode,
            ]);
        }

        return redirect()->back()->with('success', "Auction is now LIVE for {$duration} minutes. Ref: {$refCode}");
    }

    /**
     * Get real-time auction data (AJAX)
     */
    public function sync(Auction $auction)
    {
        $auction->loadCount('bids');
        $latestBids = $auction->bids()->latest()->with('user')->take(5)->get()->map(function($bid) {
            return [
                'user_name' => $bid->user->name,
                'user_initial' => substr($bid->user->name, 0, 1),
                'amount' => '$' . number_format($bid->amount),
                'time' => $bid->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'current_price' => (double) $auction->current_price,
            'current_price_formatted' => number_format($auction->current_price),
            'next_bid_amount' => (double) ($auction->current_price + 500),
            'next_bid_formatted' => '$' . number_format($auction->current_price + 500),
            'bids_count' => $auction->bids_count,
            'latest_bids' => $latestBids,
        ]);
    }
}
