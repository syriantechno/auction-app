<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Brand;
use App\Models\Lead;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CMS\Page;
use App\Models\SystemSetting;
use App\Models\User;
use App\Mail\LeadConfirmation;
use App\Notifications\NewLeadReceived;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $page = Cache::remember('homepage.cms.page', now()->addMinutes(10), function () {
            return Page::query()
                ->where('slug', 'home')
                ->where('is_published', true)
                ->first();
        });

        $featuredAuctions = Cache::remember('homepage.featured.auctions', now()->addMinutes(5), function () {
            return Auction::query()
                ->whereIn('status', ['active', 'coming_soon'])
                ->with(['car', 'bids.user'])
                ->withCount('bids')
                ->orderBy('start_at', 'asc')
                ->take(6)
                ->get();
        });

        $stats = Cache::remember('homepage.stats', now()->addMinutes(5), function () {
            return [
                'active_auctions' => Auction::where('status', 'active')->count(),
                'total_cars' => Car::count(),
                'total_bids' => Bid::count(),
                'happy_customers' => User::whereHas('bids')->count(),
            ];
        });

        $catalogMakes = Cache::remember('homepage.catalog.makes', now()->addMinutes(30), function () {
            if (!Schema::hasTable('brands')) {
                return Car::query()
                    ->select('make')
                    ->distinct()
                    ->orderBy('make')
                    ->pluck('make')
                    ->values()
                    ->all();
            }

            $brandNames = Brand::query()
                ->orderBy('name')
                ->pluck('name')
                ->values()
                ->all();

            if (!empty($brandNames)) {
                return $brandNames;
            }

            return Car::query()
                ->select('make')
                ->distinct()
                ->orderBy('make')
                ->pluck('make')
                ->values()
                ->all();
        });

        $catalogMakesWithLogos = Cache::remember('homepage.catalog.makes_with_logos', now()->addMinutes(30), function () {
            if (!Schema::hasTable('brands')) {
                return collect(Car::query()
                    ->select('make')
                    ->distinct()
                    ->orderBy('make')
                    ->pluck('make')
                    ->values()
                    ->all())->map(function ($make) {
                        return [
                            'name' => $make,
                            'logo' => $this->brandLogoFor($make),
                        ];
                    })->all();
            }

            $brands = Brand::query()
                ->with('models')
                ->orderBy('name')
                ->get();

            if ($brands->isNotEmpty()) {
                return $brands->map(function (Brand $brand) {
                    return [
                        'name' => $brand->name,
                        'logo' => $brand->logo_url,
                    ];
                })->values()->all();
            }

            return collect(Car::query()
                ->select('make')
                ->distinct()
                ->orderBy('make')
                ->pluck('make')
                ->values()
                ->all())->map(function ($make) {
                    return [
                        'name' => $make,
                        'logo' => $this->brandLogoFor($make),
                    ];
                })->all();
        });

        $catalogModelsByMake = Cache::remember('homepage.catalog.models_by_make', now()->addMinutes(30), function () {
            if (!Schema::hasTable('brands') || !Schema::hasTable('car_models')) {
                $allModels = Car::query()
                    ->select('model')
                    ->whereNotNull('model')
                    ->orderBy('model')
                    ->pluck('model')
                    ->map(fn ($model) => trim((string) $model))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                return Car::query()
                    ->select('make', 'model')
                    ->whereNotNull('make')
                    ->whereNotNull('model')
                    ->get()
                    ->groupBy(function (Car $car) {
                        return mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', trim((string) $car->make)));
                    })
                    ->map(function ($cars) {
                        return $cars
                            ->pluck('model')
                            ->map(fn ($model) => trim((string) $model))
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values()
                            ->all();
                    })
                    ->put('__all__', $allModels)
                    ->all();
            }

            $brands = Brand::query()->with('models')->orderBy('name')->get();

            if ($brands->isNotEmpty()) {
                $mapped = $brands->mapWithKeys(function (Brand $brand) {
                    $key = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', trim((string) $brand->name)));

                    return [
                        $key => $brand->models
                            ->pluck('name')
                            ->map(fn ($model) => trim((string) $model))
                            ->filter()
                            ->unique()
                            ->sort()
                            ->values()
                            ->all(),
                    ];
                })->all();

                $mapped['__all__'] = CarModel::query()
                    ->orderBy('name')
                    ->pluck('name')
                    ->map(fn ($model) => trim((string) $model))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                return $mapped;
            }

            $allModels = Car::query()
                ->select('model')
                ->whereNotNull('model')
                ->orderBy('model')
                ->pluck('model')
                ->map(fn ($model) => trim((string) $model))
                ->filter()
                ->unique()
                ->values()
                ->all();

            return Car::query()
                ->select('make', 'model')
                ->whereNotNull('make')
                ->whereNotNull('model')
                ->get()
                ->groupBy(function (Car $car) {
                    return mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', trim((string) $car->make)));
                })
                ->map(function ($cars) {
                    return $cars
                        ->pluck('model')
                        ->map(fn ($model) => trim((string) $model))
                        ->filter()
                        ->unique()
                        ->sort()
                        ->values()
                        ->all();
                })
                ->put('__all__', $allModels)
                ->all();
        });

        // Popular brands - two rows (14 brands total)
        $popularBrandNames = [
            'Toyota', 'Nissan', 'Ford', 'Mercedes-Benz', 'BMW', 'Honda', 'Jeep',
            'Audi', 'Lexus', 'Kia', 'Porsche', 'Volkswagen', 'Mitsubishi', 'Chevrolet'
        ];
        $popularBrands = collect($popularBrandNames)->map(fn ($name) => [
            'name' => $name,
            'make' => $name,
            'logo' => $this->brandLogoFor($name),
        ]);

        $firstRow = $popularBrands->slice(0, 7);
        $secondRow = $popularBrands->slice(7, 7);


        $wizardStartStep = (int) $request->query('step', 1);

        $sellCarYears = range((int) date('Y') + 1, 1990);
        $sellCarConditions = [
            'excellent' => 'Excellent - Like New',
            'good' => 'Good - Minor Wear',
            'fair' => 'Fair - Normal Wear',
            'needs_work' => 'Needs Work / Salvage',
        ];

        return view('home', compact(
            'featuredAuctions', 'stats', 'page', 'catalogMakes', 
            'catalogMakesWithLogos', 'firstRow', 'secondRow',
            'catalogModelsByMake', 'popularBrands', 'wizardStartStep',
            'sellCarYears', 'sellCarConditions'
        ));
    }

    public function storeSellLead(Request $request)
    {
        $validated = $request->validate([
            'year' => ['required', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'trim' => ['nullable', 'string', 'max:100'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'gcc' => ['nullable', 'string', 'max:50'],
            'body' => ['nullable', 'string', 'max:50'],
            'engine' => ['nullable', 'string', 'max:50'],
            'paint' => ['nullable', 'string', 'max:50'],
            'condition' => ['required', 'in:excellent,good,fair,needs_work'],
            'features' => ['nullable', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:30'],
            'inspection_date'  => ['required', 'date'],
            'inspection_time'  => ['required', 'string'],
            'inspection_type'  => ['nullable', 'in:branch,home'],
            'home_address'     => ['nullable', 'string', 'max:500'],
        ]);

        $lead = Lead::create([
            'user_id' => $request->user()?->id,
            'car_details' => [
                'source' => 'home_sell_wizard',
                'year' => (int) $validated['year'],
                'make' => $validated['make'],
                'model' => $validated['model'],
                'trim' => $validated['trim'] ?? null,
                'mileage' => isset($validated['mileage']) ? (int) $validated['mileage'] : null,
                'gcc' => $validated['gcc'] ?? null,
                'body' => $validated['body'] ?? null,
                'engine' => $validated['engine'] ?? null,
                'paint' => $validated['paint'] ?? null,
                'condition' => $validated['condition'],
                'features' => $validated['features'] ?? null,
                'inspection_date'  => $validated['inspection_date'] ?? null,
                'inspection_time'  => $validated['inspection_time'] ?? null,
                'inspection_type'  => $validated['inspection_type'] ?? 'branch',
                'home_address'     => $validated['home_address'] ?? null,
                'name'             => $validated['name'],
                'email'            => $validated['email'],
                'phone'            => $validated['phone'],
            ],
            'status' => 'new',
            'notes' => sprintf(
                'Sell lead: %s %s %s. Inspection scheduled for %s at %s',
                $validated['year'],
                $validated['make'],
                $validated['model'],
                $validated['inspection_date'] ?? 'N/A',
                $validated['inspection_time'] ?? 'N/A'
            ),
        ]);

        // ── Notify all admins ─────────────────────────────────
        User::where('role', 'admin')
            ->orWhereIn('email', ['admin@motorbazar.ae', 'admin@automazad.com'])
            ->get()
            ->each(fn($admin) => $admin->notify(new NewLeadReceived($lead)));

        // ── Send confirmation email to lead ───────────────────
        $leadEmail = data_get($lead->car_details, 'email');
        if ($leadEmail && filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($leadEmail)->send(new LeadConfirmation($lead));
            } catch (\Throwable $e) {
                Log::error('[Email] Lead confirmation failed: ' . $e->getMessage());
            }
        }

        // ── Send WhatsApp confirmation to lead ────────────────
        $leadPhone = data_get($lead->car_details, 'phone');
        if ($leadPhone) {
            try {
                $whatsappTemplate = SystemSetting::get(
                    'whatsapp_lead_template',
                    "Hello {name}! 👋\n\nYour Motor Bazar request has been received.\n\n🚗 Vehicle: {year} {make} {model}\n📅 Inspection: {date} at {time}\n🔖 Ref: #{ref}\n\nOur team will contact you shortly. Thank you!"
                );

                $message = strtr($whatsappTemplate, [
                    '{name}'  => data_get($lead->car_details, 'name', 'Client'),
                    '{make}'  => data_get($lead->car_details, 'make', ''),
                    '{model}' => data_get($lead->car_details, 'model', ''),
                    '{year}'  => data_get($lead->car_details, 'year', ''),
                    '{date}'  => data_get($lead->car_details, 'inspection_date', 'TBD'),
                    '{time}'  => data_get($lead->car_details, 'inspection_time', 'TBD'),
                    '{ref}'   => str_pad($lead->id, 6, '0', STR_PAD_LEFT),
                ]);

                app(WhatsAppService::class)->send($leadPhone, $message);
            } catch (\Throwable $e) {
                Log::error('[WhatsApp] Lead message failed: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead pushed to CRM Matrix successfully.'
            ]);
        }

        return back()->with('lead_submitted', true);
    }

    private function brandLogoFor(string $make): string
    {
        $localName = strtolower(str_replace([' ', '-'], ['', ''], $make));
        
        // Smart normalization mapping for filenames
        $map = [
            'mercedesbenz' => 'mercedes',
            'volkswagen' => 'volkswagen',
            'landrover' => 'land-rover',
            'mercedes' => 'mercedes',
        ];
        
        $searchName = $map[$localName] ?? $localName;
        $file = $searchName . '.svg';
        
        if(file_exists(public_path('images/brands/' . $file))) {
            return asset('images/brands/' . $file);
        }

        // Catch-all for sub-variants or missed names
        $logos = [
            'Toyota' => 'https://cdn.simpleicons.org/toyota/eb0a1e',
            'Nissan' => 'https://cdn.simpleicons.org/nissan/c3012c',
        ];

        return $logos[$make] ?? 'https://placehold.co/40x40/f8fafc/111827?text=' . urlencode(mb_substr($make, 0, 1));
    }
}
