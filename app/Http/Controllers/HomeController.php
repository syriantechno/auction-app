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
use App\Events\LeadCreated;
use App\Services\WhatsAppService;
use App\Notifications\NewLeadReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return $this->buildHomeView($request, 'home');
    }

    private function buildHomeView(Request $request, string $view)
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
                    ->distinct()
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
                ->distinct()
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

        $googleReviewBlock = $this->buildGoogleReviewsBlock();

        $pageContent = $page?->content ?? [];
        $leadArchitecture = [
            'header'         => SystemSetting::get('lead_header_label', data_get($pageContent, 'lead_form.header', 'Ready to Sell?')),
            'wizard_title'   => SystemSetting::get('lead_header_title', data_get($pageContent, 'lead_form.title', 'What would you like to sell?')),
            'step1'          => SystemSetting::get('lead_wizard_w1', data_get($pageContent, 'lead_form.wizard_w1', 'Select')),
            'step2'          => SystemSetting::get('lead_wizard_w2', data_get($pageContent, 'lead_form.wizard_w2', 'Customize')),
            'step3'          => SystemSetting::get('lead_wizard_w3', data_get($pageContent, 'lead_form.wizard_w3', 'Submit')),
            'featured_brand_names' => json_decode(SystemSetting::get('lead_form_brands'), true) 
                                    ?: collect(data_get($pageContent, 'lead_form_brands', []))->pluck('name')->toArray(),
            'circles_enabled' => SystemSetting::get('lead_circles_enabled', '1') === '1',
        ];

        // Resolve logos for featured brands
        $leadArchitecture['featured_brands'] = collect($leadArchitecture['featured_brand_names'])->map(function($brandName) use ($catalogMakesWithLogos) {
            $found = collect($catalogMakesWithLogos)->firstWhere('name', $brandName);
            if ($found) return $found;
            return [
                'name' => $brandName,
                'logo' => $this->brandLogoFor($brandName)
            ];
        })->all();

        return view($view, compact(
            'featuredAuctions', 'stats', 'page', 'catalogMakes', 
            'catalogMakesWithLogos', 'firstRow', 'secondRow',
            'catalogModelsByMake', 'popularBrands', 'wizardStartStep',
            'sellCarYears', 'sellCarConditions', 'googleReviewBlock',
            'leadArchitecture'
        ));
    }

    public function home2(Request $request)
    {
        return $this->buildHomeView($request, 'home2');
    }

    public function storeSellLead(Request $request)
    {
        $isPlate = $request->input('lead_type') === 'sell_plate';

        $rules = [
            'year' => $isPlate ? ['required', 'string', 'max:50'] : ['required', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'trim' => ['nullable', 'string', 'max:100'],
            'mileage' => ['nullable', 'string', 'max:100'],
            'gcc' => ['nullable', 'string', 'max:100'],
            'body' => ['nullable', 'string', 'max:100'],
            'engine' => ['nullable', 'string', 'max:100'],
            'paint' => ['nullable', 'string', 'max:100'],
            'condition' => $isPlate ? ['nullable', 'string'] : ['required', 'in:excellent,good,fair,needs_work'],
            'features' => ['nullable', 'string', 'max:1000'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:30'],
            'inspection_date'  => ['required', 'date'],
            'inspection_time'  => ['required', 'string'],
            'inspection_type'  => ['nullable', 'string'],
            'home_address'     => ['nullable', 'string', 'max:500'],
            'lead_type'        => ['nullable', 'string'],
        ];

        $validated = $request->validate($rules);

        $lead = Lead::create([
            'user_id' => $request->user()?->id,
            'car_details' => [
                'source' => 'home_sell_wizard',
                'lead_type' => $validated['lead_type'] ?? 'sell_car',
                'year' => $validated['year'],
                'make' => $validated['make'],
                'model' => $validated['model'],
                'trim' => $validated['trim'] ?? null,
                'mileage' => $validated['mileage'] ?? null,
                'gcc' => $validated['gcc'] ?? null,
                'body' => $validated['body'] ?? null,
                'engine' => $validated['engine'] ?? null,
                'paint' => $validated['paint'] ?? null,
                'condition' => $validated['condition'] ?? 'good',
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
            'source' => session('lead_source', 'direct'),
            'notes' => sprintf(
                '%s: %s %s %s. Inspection: %s at %s',
                ($validated['lead_type'] ?? 'sell_car') === 'sell_plate' ? 'Plate Lead' : 'Car Lead',
                $validated['year'],
                $validated['make'],
                $validated['model'],
                $validated['inspection_date'] ?? 'N/A',
                $validated['inspection_time'] ?? 'N/A'
            ),
        ]);

        // ── Broadcast to admins in real-time ───────────────────
        broadcast(new LeadCreated($lead));

        // ── Notify all admins (Safe Capture) ───────────────────
        try {
            User::where('role', 'admin')
                ->orWhereIn('email', ['admin@motorbazar.ae', 'admin@automazad.com'])
                ->get()
                ->each(fn($admin) => $admin->notify(new NewLeadReceived($lead)));
        } catch (\Throwable $e) {
            Log::error('[Notification] Admin live alert failed: ' . $e->getMessage());
        }

        // ── Send confirmation email to lead ───────────────────
        $leadEmail = data_get($lead->car_details, 'email');
        Log::info('[Email] Attempting to send to: ' . ($leadEmail ?? 'NULL'));
        if ($leadEmail && filter_var($leadEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($leadEmail)->send(new \App\Mail\LeadConfirmation($lead));
                Log::info('[Email] Confirmation sent successfully to ' . $leadEmail);
            } catch (\Throwable $e) {
                Log::error('[Email] Lead confirmation failed to ' . $leadEmail . ': ' . $e->getMessage());
                Log::error($e);
            }
        } else {
            Log::warning('[Email] Invalid or missing email address: ' . ($leadEmail ?? 'NONE'));
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
                    '{ref}'   => str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT),
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

    private function buildGoogleReviewsBlock(): array
    {
        $enabled = SystemSetting::get('google_reviews_enabled', '0') === '1';

        $config = [
            'enabled' => $enabled,
            'title' => SystemSetting::get('google_reviews_title', 'Loved by real buyers'),
            'subtitle' => SystemSetting::get('google_reviews_subtitle', 'Straight from Google Reviews'),
            'badge' => SystemSetting::get('google_reviews_badge', '4.9 / 5 • Google Reviews'),
            'place_id' => SystemSetting::get('google_reviews_place_id'),
            'api_key' => SystemSetting::get('google_reviews_api_key'),
            'manual_reviews' => json_decode(SystemSetting::get('google_reviews_manual_list', '[]'), true) ?: [],
        ];

        if (!$enabled) {
            $config['reviews'] = [];
            return $config;
        }

        $remoteReviews = $this->fetchGoogleReviews($config['place_id'], $config['api_key']);
        $reviews = !empty($remoteReviews) ? $remoteReviews : $config['manual_reviews'];
        $config['reviews'] = collect($reviews)
            ->map(function ($review) {
                $text = trim((string) data_get($review, 'text', ''));
                $timestamp = (int) data_get($review, 'timestamp', data_get($review, 'time', 0));
                return [
                    'author' => data_get($review, 'author', data_get($review, 'author_name', 'Google User')),
                    'rating' => (int) data_get($review, 'rating', 5),
                    'text' => Str::limit($text, 420),
                    'time' => data_get($review, 'relative_time_description') ?? data_get($review, 'time'),
                    'profile_url' => data_get($review, 'profile_url') ?? data_get($review, 'author_url'),
                    'photo_url' => data_get($review, 'photo_url') ?? data_get($review, 'profile_photo_url'),
                    'timestamp' => $timestamp,
                ];
            })
            ->filter(fn ($review) => filled($review['author']) && filled($review['text']) && (int) data_get($review, 'rating', 0) === 5)
            ->sortByDesc(fn ($review) => (int) data_get($review, 'timestamp', 0))
            ->take(8)
            ->values()
            ->all();

        return $config;
    }

    private function fetchGoogleReviews(?string $placeId, ?string $apiKey): array
    {
        if (blank($placeId) || blank($apiKey)) {
            return [];
        }

        $cacheKey = 'homepage.google_reviews.' . md5($placeId . $apiKey);

        return Cache::remember($cacheKey, now()->addHours(3), function () use ($placeId, $apiKey) {
            try {
                $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'rating,reviews,user_ratings_total,name,reviews.profile_photo_url',
                    'reviews_sort' => 'newest',
                    'key' => $apiKey,
                ]);

                if (!$response->ok()) {
                    Log::warning('[GoogleReviews] HTTP error', ['status' => $response->status()]);
                    return [];
                }

                $payload = $response->json();
                $status = data_get($payload, 'status');
                if ($status !== 'OK') {
                    Log::warning('[GoogleReviews] API status not OK', ['status' => $status]);
                    return [];
                }

                return collect(data_get($payload, 'result.reviews', []))
                    ->map(function ($review) {
                        return [
                            'author' => data_get($review, 'author_name', 'Google User'),
                            'rating' => (int) data_get($review, 'rating', 5),
                            'text' => data_get($review, 'text', ''),
                            'relative_time_description' => data_get($review, 'relative_time_description'),
                            'author_url' => data_get($review, 'author_url'),
                            'profile_photo_url' => data_get($review, 'profile_photo_url'),
                            'timestamp' => data_get($review, 'time'),
                        ];
                    })
                    ->take(6)
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('[GoogleReviews] fetch failed: ' . $e->getMessage());
                return [];
            }
        });
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
