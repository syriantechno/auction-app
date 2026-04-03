@extends('layouts.app')

@section('title', 'Motor Bazar - Premium Car Marketplace')

@section('head')
<style>
    @php
        $page = $page ?? null;
        $heroContent = $page ? data_get($page->content, 'hero', []) : [];
        $homepageHeroImage = data_get($heroContent, 'background_image') ?: ($page ? $page->hero_image : null) ?: '/images/cars/navy-mclaren.png';
        $fallbackCarImages = [
            '/images/cars/navy-mclaren.png',
            '/images/cars/car-main.png',
            '/images/cars/car-1.png',
            '/images/cars/car-2.png',
            '/images/cars/car-3.png',
        ];

        $heroStats = [
            ['label' => 'Active Auctions', 'value' => number_format($stats['active_auctions'] ?? 0)],
            ['label' => 'Total Cars', 'value' => number_format($stats['total_cars'] ?? 0)],
            ['label' => 'Total Bids', 'value' => number_format($stats['total_bids'] ?? 0)],
            ['label' => 'Happy Customers', 'value' => number_format($stats['happy_customers'] ?? 0)],
        ];

        $featuredBadges = ['Verified History', 'Instant Offers', 'Trusted Sellers'];

        $heroBackgroundColor = data_get($heroContent, 'background_color', '#e7e7e7');
        $heroBackgroundOpacity = (float) data_get($heroContent, 'background_overlay_opacity', 0.18);
        $heroBackgroundDirection = data_get($heroContent, 'background_overlay_direction', 'horizontal');
        $heroBackgroundOverlayEnabled = (bool) data_get($heroContent, 'background_overlay_enabled', true);
        $heroCarScale = (float) data_get($heroContent, 'car_scale', 1);
        $heroBackgroundImage = data_get($heroContent, 'background_image') ?: ($page ? $page->hero_image : null) ?: '/images/hero-bg.png';
        $heroCarImage = ($page ? $page->hero_image : null) ?: '/images/cars/navy-mclaren.png';
        $heroBackgroundRgb = sscanf(ltrim($heroBackgroundColor, '#'), "%02x%02x%02x");
        $heroBackgroundStyle = $heroBackgroundOverlayEnabled
            ? "background-image: linear-gradient(" . ($heroBackgroundDirection === 'vertical' ? 'to bottom' : 'to right') . ", rgba({$heroBackgroundRgb[0]}, {$heroBackgroundRgb[1]}, {$heroBackgroundRgb[2]}, {$heroBackgroundOpacity}), rgba({$heroBackgroundRgb[0]}, {$heroBackgroundRgb[1]}, {$heroBackgroundRgb[2]}, " . max(0.14, $heroBackgroundOpacity * 0.8) . ")), url('{$heroBackgroundImage}'); background-size: cover; background-position: center;"
            : "background-image: url('{$heroBackgroundImage}'); background-size: cover; background-position: center; background-color: {$heroBackgroundColor};";
    @endphp

    /* ── Anti-FOUC: brand logo size locked before page renders ────────── */
    .brand-logo-wrapper { width:60px!important; height:60px!important; overflow:hidden!important; flex-shrink:0!important; }
    .brand-logo { max-width:100%!important; max-height:100%!important; object-fit:contain!important; }
    .w-14.h-14 > img, .brand-pick img { max-width:56px!important; max-height:56px!important; object-fit:contain!important; }

    .search-tab {
        padding: 12px 20px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #adb5bd;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .search-tab.active {
        color: #ff4605;
        border-bottom-color: #ff4605;
    }
    .search-select {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 12px 16px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1d26;
        outline: none;
        appearance: none;
    }
    .body-type-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 1rem;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .body-type-card:hover {
        border-color: #ff4605;
        box-shadow: 0 20px 40px -10px rgba(255, 70, 5, 0.1);
        transform: translateY(-5px);
    }
    .car-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 20px 50px -25px rgba(15, 23, 42, 0.18);
    }
    .car-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 28px 70px -28px rgba(15, 23, 42, 0.22);
    }
    .badge-year {
        background: #ff4605;
        color: white;
        padding: 4px 10px;
        border-radius: 1rem;
        font-size: 0.65rem;
        font-weight: 800;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(18px);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .soft-shadow {
        box-shadow: 0 24px 60px -20px rgba(0, 0, 0, 0.25);
    }

    .model-shell {
        position: relative;
        perspective: 1600px;
    }

    .model-shell model-viewer {
        width: 100%;
        height: 100%;
        background: transparent;
        --poster-color: transparent;
    }

    .wizard-field {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 12px 14px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1d26;
        outline: none;
        transition: all 0.2s ease;
    }

    .wizard-field:focus {
        border-color: #ff4605;
        box-shadow: 0 0 0 4px rgba(255, 70, 5, 0.08);
    }

    .brand-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        transition: all 0.25s ease;
    }

    .home-page [class*="rounded-"] {
        border-radius: 1rem !important;
    }

    .home-page .car-card,
    .home-page .brand-card,
    .home-page .brand-slide,
    .home-page .search-select,
    .home-page .wizard-field,
    .home-page .flatpickr-calendar.noCalendar,
    .home-page .flatpickr-time,
    .home-page .badge-year,
    .home-page .sell-wizard-card {
        border-radius: 1rem !important;
    }

    .brand-card:hover {
        transform: translateY(-4px);
        border-color: #ff4605;
        box-shadow: 0 16px 40px -18px rgba(255, 70, 5, 0.22);
    }

    .brand-pick {
        transition: transform 0.2s ease;
        background: transparent;
        border: none;
    }

    /* Narrow Elite Card */
    .floating-card {
        max-width: 440px !important;
        width: 100%;
        border-radius: 1rem !important;
        box-shadow: 0 40px 100px -20px rgba(0,0,0,0.15) !important;
    }

    .brand-pick {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .brand-pick.is-active .brand-pick-icon {
        border-color: #ff4605 !important;
        background: #fffafa !important;
        box-shadow: 0 0 15px rgba(255, 70, 5, 0.1) !important;
    }

    .brand-dropdown-menu {
        box-shadow: 0 24px 50px -20px rgba(0, 0, 0, 0.22);
    }

    .brand-dropdown-menu.drop-up {
        top: auto !important;
        bottom: calc(100% + 0.5rem) !important;
        margin-top: 0 !important;
    }

    .brand-dropdown-option.is-active {
        background: #fff7f2;
        color: #ff4605;
    }

    .brand-pick .brand-pick-label {
        line-height: 1.15;
    }

    /* Reset Ugly Focus */
    input:focus, select:focus, textarea:focus {
        outline: none !important;
        box-shadow: 0 0 0 4px rgba(255, 70, 5, 0.08) !important;
        border-color: #ff4605 !important;
    }

    /* Ultra Compact Time Picker */
    .flatpickr-calendar.noCalendar {
        width: 140px !important; 
        min-width: 140px !important;
        border-radius: 1rem !important;
        border: 1px solid #f8fafc !important;
        box-shadow: 0 10px 30px rgba(255, 70, 5, 0.1) !important;
    }
    .flatpickr-time {
        height: 60px !important;
        overflow: hidden !important;
        border-radius: 1rem !important;
    }
    .flatpickr-time input {
        font-weight: 800 !important;
        color: #ff4605 !important;
        font-size: 16px !important;
    }
    .flatpickr-time .flatpickr-am-pm {
        font-weight: 900 !important;
        font-size: 11px !important;
        color: #64748b !important;
    }
    .flatpickr-time .flatpickr-time-separator {
        color: #e2e8f0 !important;
        font-weight: 900 !important;
    }
    .flatpickr-calendar.hasTime.noCalendar .flatpickr-time {
        border: none !important;
    }
    .btn-active-orange {
        background-color: #FF6900 !important;
        border-color: #FF6900 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 105, 0, 0.2) !important;
    }
    .btn-bazar-primary {
        background-color: #FF6900 !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        border: none !important;
        box-shadow: 0 10px 30px -10px rgba(255, 105, 0, 0.4) !important;
    }
    .btn-bazar-primary:hover {
        background-color: #e65c00 !important;
        transform: translateY(-1px);
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
@endsection

@php
    $page = $page ?? null;
    $heroContent = $page ? data_get($page->content, 'hero', []) : [];
    $sellCarYears = range((int) date('Y') + 1, 1995);
    $sellCarMakes = $catalogMakes ?? [];
    $popularBrands = $catalogBrands ?? [];
    $heroAnnouncementHtml = $heroContent['announcement'] ?? 'Under 30 Minutes';
    $heroTitleHtml = $heroContent['title'] ?? 'Sell Your Car <span style="color:#ff9900;">Dubai</span> Instantly.';
    $heroSubtitleHtml = $heroContent['subtitle'] ?? 'Our trusted 3-step elite process handles everything. From used cars to premium SUVs, MotorBazar is your partner in the UAE.';
    $sellCarConditions = [
        'excellent' => 'Excellent',
        'good' => 'Good',
        'fair' => 'Fair',
        'needs_work' => 'Needs Work',
    ];
    $wizardStartStep = 1;

    if ($errors->hasAny(['year', 'make', 'model'])) {
        $wizardStartStep = 1;
    } elseif ($errors->hasAny(['trim', 'mileage', 'condition', 'features'])) {
        $wizardStartStep = 2;
    } elseif ($errors->hasAny(['name', 'email', 'phone'])) {
        $wizardStartStep = 3;
    }
@endphp

@section('content')
    <div class="home-page">
    {{-- High-Performance Elite Dashboard (Advanced 3-Layer Layout) --}}

    @php
        $heroMode = data_get($page->content, 'hero.background_mode', 'image');
        $heroBg = data_get($page->content, 'hero.background_image', '/images/hero-bg.png');
        $heroColor = data_get($page->content, 'hero.background_color', '#e7e7e7');
        $heroColor2 = data_get($page->content, 'hero.background_color_secondary', '#1a1d26');
        $heroAngle = data_get($page->content, 'hero.background_gradient_angle', 135);
        $heroCustomCss = data_get($page->content, 'hero.custom_css', '');
        $heroOpacity = (float) data_get($page->content, 'hero.background_overlay_opacity', 0.72);
        $heroOverlayEnabled = data_get($page->content, 'hero.background_overlay_enabled', true);
        $heroDirection = data_get($page->content, 'hero.background_overlay_direction', 'horizontal');
        $gradDir = $heroDirection === 'vertical' ? 'to bottom' : 'to right';
        
        // Helper to convert hex to rgba in blade
        $hexToRgba = function($hex, $opacity) {
            $hex = str_replace("#", "", $hex);
            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } else {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            }
            return "rgba($r,$g,$b,$opacity)";
        };

        $rgba1 = $hexToRgba($heroColor, $heroOpacity);
        $rgba2 = $hexToRgba($heroColor2, $heroOpacity);
        
        $backgroundStyle = "";
        if ($heroMode === 'custom' && !empty($heroCustomCss)) {
            $backgroundStyle = $heroCustomCss;
        } elseif ($heroMode === 'solid') {
            $backgroundStyle = "background-color: $rgba1;";
        } elseif ($heroMode === 'gradient') {
            $backgroundStyle = "background: linear-gradient({$heroAngle}deg, $rgba1, $rgba2);";
        } else {
            // Image mode
            $overlayRgba = "rgba(14,16,23,0)";
            if ($heroOverlayEnabled) {
                $overlayRgba = "rgba(3, 22, 41, $heroOpacity)";
            }
            
            if (empty($heroBg)) {
                 $backgroundStyle = "background-color: $rgba1;";
            } else {
                 $backgroundStyle = "background: " . ($heroOverlayEnabled ? "linear-gradient($gradDir, $overlayRgba, transparent), " : "") . " url('$heroBg'); background-size: cover; background-position: center; background-color: $heroColor;";
            }
        }
    @endphp

    <section class="relative z-30 overflow-visible pt-[150px] pb-72 min-h-[900px] transition-all duration-1000" 
        style="{{ $backgroundStyle }}">
        <div class="relative z-10 mx-auto max-w-[1440px] px-6 lg:px-12 w-full flex flex-col gap-12 lg:gap-16">
            
            {{-- Layer 1: The Elite Proposition & Asset Showroom --}}
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-20">
                
                {{-- Left Side: High-Impact Marketing Proposition --}}
                <div class="w-full lg:w-[45%] space-y-8 animate-in fade-in slide-in-from-left duration-1000">
                    <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-[#1d293d] text-white text-[0.7rem] font-black uppercase tracking-[0.3em] shadow-2xl">
                        <i data-lucide="zap" class="w-4 h-4 text-[#ff6900] animate-pulse"></i>
                        {!! $heroAnnouncementHtml !!}
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-black leading-[1.05] tracking-tighter text-slate-900">
                        {!! nl2br($heroTitleHtml) !!}
                    </h1>
                    
                    <p class="text-slate-500 font-bold text-lg leading-relaxed max-w-lg">
                        {!! $heroSubtitleHtml !!}
                    </p>

                    <div class="flex items-center gap-8 pt-4">
                        <div class="flex -space-x-3 overflow-hidden">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=1" alt="">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=2" alt="">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=3" alt="">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full ring-2 ring-[#e7e7e7] bg-[#1d293d] text-white text-[0.6rem] font-black">+1k</div>
                        </div>
                        <div class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest leading-none">
                            Trusted by thousands <br/> of Dubai sellers
                        </div>
                    </div>
                </div>

                {{-- Right Side: The Elite Showroom Asset --}}
                <div class="w-full lg:w-[55%] relative transform hover:scale-[1.05] transition-all duration-1000">
                    <img src="{{ $page->hero_image ?: '/images/cars/mclaren.png' }}" 
                        class="w-full h-auto object-contain filter drop-shadow-[0_80px_100px_rgba(0,0,0,0.12)]" 
                        alt="Elite Selection"
                        style="image-rendering: -webkit-optimize-contrast; transform: scale({{ data_get($page->content, 'hero.car_scale', 1) }}); transform-origin: center bottom;">
                </div>

            </div>
        </div>
    </section>

    {{-- Sell Car Wizard: Independent Glass Card --}}
    <section class="relative z-40 -mt-[19rem] px-6 lg:px-12 pb-16">
        <div class="mx-auto max-w-[1440px]">
            <div class="sell-wizard-card relative z-10 rounded-[1rem] border border-white/70 bg-white/65 backdrop-blur-2xl shadow-[0_40px_120px_-45px_rgba(15,23,42,0.35)] p-6 lg:p-8 overflow-visible">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="text-bazar-500 font-black uppercase tracking-[0.35em] text-[0.62rem] mb-3 block">Sell Your Car</span>
                        @php
                            $wizardW1 = data_get($page?->content, 'lead_form.wizard_w1', 'Select');
                            $wizardW2 = data_get($page?->content, 'lead_form.wizard_w2', 'Customize');
                            $wizardW3 = data_get($page?->content, 'lead_form.wizard_w3', 'Submit');
                        @endphp
                        <h2 class="text-3xl lg:text-4xl font-black tracking-tight flex items-center flex-wrap gap-x-3 gap-y-1">
                            <span id="wizard-title-w1" class="transition-colors duration-500 text-[#ff6900]">{{ $wizardW1 }}</span>
                            <span class="text-slate-300 font-light text-2xl leading-none">&bull;</span>
                            <span id="wizard-title-w2" class="transition-colors duration-500 text-slate-300">{{ $wizardW2 }}</span>
                            <span class="text-slate-300 font-light text-2xl leading-none">&bull;</span>
                            <span id="wizard-title-w3" class="transition-colors duration-500 text-slate-300">{{ $wizardW3 }}</span>
                        </h2>

                    </div>

                    <p class="text-sm text-slate-500 max-w-2xl">{{ data_get($page?->content, 'lead_form.step1.subtitle', 'Pick a brand first. The model list updates automatically from the catalog.') }}</p>
                </div>

                <form action="{{ route('sell-car-lead') }}" method="POST" id="sellCarWizard" data-start-step="{{ $wizardStartStep }}" class="relative">
                    @csrf

                    {{-- Step 1: Brand, Model, Year --}}
                    <div data-step="1" class="space-y-4">
                        @php
                            // 1. Selection List: Always ALL Brands from catalog (70+)
                            $brandSelectBrands = collect($catalogMakesWithLogos ?? [])
                                ->filter(fn ($brand) => !empty(data_get($brand, 'name')))
                                ->map(fn ($brand) => [
                                    'name' => data_get($brand, 'name'),
                                    'logo' => data_get($brand, 'logo'),
                                ])
                                ->merge($popularBrands ?? [])
                                ->unique('name')
                                ->values()
                                ->all();
                                
                            // 2. "Quick Pick" Cards (8 brands): Curated in CMS, don't touch
                            $cmsLeadBrands = data_get($page->content, 'lead_form_brands', []);
                            
                            if (!empty($cmsLeadBrands)) {
                                $brandCardBrands = collect($cmsLeadBrands)->map(function($brand) {
                                    $slug = $brand['slug'];
                                    $name = $brand['name'];
                                    
                                    $variants = [$slug, str_replace(['-', '_'], '', $slug), explode('-', $slug)[0]];
                                    $logoPath = "/images/brands/default.svg";
                                    foreach ($variants as $variant) {
                                        if (file_exists(public_path("images/brands/{$variant}.svg"))) {
                                            $logoPath = "/images/brands/{$variant}.svg";
                                            break;
                                        }
                                        if (file_exists(public_path("images/brands/{$variant}.png"))) {
                                            $logoPath = "/images/brands/{$variant}.png";
                                            break;
                                        }
                                    }
                                    return ['name' => $name, 'logo' => $logoPath];
                                })->all();
                            } else {
                                // Fallback to first 8 brands if CMS configuration is missing
                                $brandCardBrands = collect($brandSelectBrands)->take(8)->all();
                            }
                        @endphp

                        @if(!empty($brandCardBrands))
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-slate-400">Popular Brands</label>
                                    <span class="text-[0.6rem] font-black uppercase tracking-[0.2em] text-slate-300">Quick Pick</span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                                    @foreach($brandCardBrands as $brand)
                                        @php
                                            $brandKey = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                            $brandModels = data_get($catalogModelsByMake ?? [], $brandKey, data_get($catalogModelsByMake ?? [], '__all__', []));
                                        @endphp
                                        <button type="button" class="brand-pick group flex flex-col items-center justify-center gap-2.5 p-3 rounded-lg bg-transparent border border-transparent shadow-none transition-all duration-300 hover:shadow-none hover:bg-transparent hover:border-transparent" data-brand-pick="{{ $brand['name'] }}" data-brand-models='@json($brandModels)'>
                                            <div class="w-14 h-14 flex items-center justify-center p-0.5 transition-all duration-300 group-hover:scale-105" style="width:3.5rem;height:3.5rem;overflow:hidden;flex-shrink:0">
                                                <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="w-full h-full object-contain grayscale opacity-75 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100" style="max-width:100%;max-height:100%;object-fit:contain">
                                            </div>
                                            <span class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest group-hover:text-slate-900 transition-colors text-center leading-tight">{{ $brand['name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="space-y-3 relative">
                                <label class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-slate-400">{{ data_get($page?->content, 'lead_form.step1.brand_label', 'Brand Selection') }}</label>
                                <input type="hidden" name="make" id="sellCarMakeSelect_dynamic" value="{{ old('make') }}">
                                <button type="button" id="brandHubToggle" class="group w-full h-16 px-5 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-base font-black text-slate-900 shadow-sm transition-all hover:bg-slate-50">
                                    <span class="flex items-center gap-3 min-w-0">
                                        <span id="brandHubIcon" class="w-9 h-9 rounded-md bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden {{ old('make') ? '' : 'hidden' }}">
                                            <img id="brandHubIconImg" src="" alt="Brand logo" class="w-full h-full object-contain p-1">
                                        </span>
                                        <span id="brandHubLabel" class="truncate {{ old('make') ? 'text-slate-900' : 'text-slate-400' }}">{{ old('make') ?: 'Select Brand' }}</span>
                                    </span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 group-hover:rotate-180 transition-transform duration-500"></i>
                                </button>

                                <div id="brandHubDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[9999] rounded-[1rem] overflow-hidden border border-slate-200 bg-white/95 backdrop-blur-2xl shadow-[0_40px_120px_-30px_rgba(15,23,42,0.28)] p-2.5 animate-in fade-in zoom-in-95 duration-500">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <div class="relative flex-1">
                                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300"></i>
                                            <input type="text" id="brandHubSearch" placeholder="Search brand..." class="w-full h-11 pl-12 pr-4 rounded-[1rem] bg-slate-50 border border-slate-200 font-semibold text-sm text-slate-900 focus:ring-2 focus:ring-orange-500/15 focus:border-orange-200 transition-all">
                                        </div>
                                        <button type="button" id="resetBrandHub" class="text-[0.62rem] font-black uppercase tracking-[0.2em] text-[#ff6900] hover:text-orange-600 shrink-0">Reset</button>
                                        <button type="button" id="closeBrandHub" class="text-[0.62rem] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-slate-900 shrink-0">Close</button>
                                    </div>
                                    <div class="max-h-80 overflow-y-auto pt-2 pr-0.5 custom-scrollbar space-y-0">
                                        @foreach($brandSelectBrands as $brand)
                                            @php
                                                $brandKey = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                                $brandModels = data_get($catalogModelsByMake ?? [], $brandKey, data_get($catalogModelsByMake ?? [], '__all__', []));
                                            @endphp
                                            <button type="button" class="brand-pick-final flex items-center gap-3 w-full p-1.5 rounded-[1rem] border border-transparent hover:bg-slate-50 hover:border-slate-100 transition-all text-left" data-brand-hub-value="{{ $brand['name'] }}" data-brand-hub-logo="{{ $brand['logo'] }}" data-brand-key="{{ $brandKey }}" data-brand-models='@json($brandModels)'>
                                                <span class="w-8 h-8 rounded-[1rem] bg-white border border-slate-200 flex items-center justify-center p-1.5 shadow-sm shrink-0">
                                                    <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}" class="w-full h-full object-contain">
                                                </span>
                                                <span class="text-sm font-semibold text-slate-700 truncate">{{ $brand['name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 relative">
                                <label class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-slate-400">{{ data_get($page?->content, 'lead_form.step1.model_label', 'Model') }}</label>
                                <input type="hidden" name="model" id="sellCarModelInput" value="{{ old('model') }}">
                                <button type="button" id="modelHubToggle" disabled class="group w-full h-16 px-5 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between text-base font-black text-slate-400 shadow-sm transition-all hover:bg-slate-50 disabled:opacity-75 disabled:cursor-not-allowed">
                                    <span id="modelHubLabel" class="truncate">Select brand first</span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 group-hover:rotate-180 transition-transform duration-500"></i>
                                </button>

                                <div id="modelHubDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[300] rounded-[1rem] overflow-hidden border border-slate-200 bg-white/95 backdrop-blur-2xl shadow-[0_40px_120px_-30px_rgba(15,23,42,0.28)] p-2.5 animate-in fade-in zoom-in-95 duration-500">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <div class="relative flex-1">
                                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300"></i>
                                            <input type="text" id="modelHubSearch" placeholder="Search model..." class="w-full h-11 pl-12 pr-4 rounded-[1rem] bg-slate-50 border border-slate-200 font-semibold text-sm text-slate-900 focus:ring-2 focus:ring-orange-500/15 focus:border-orange-200 transition-all">
                                        </div>
                                        <button type="button" id="closeModelHub" class="text-[0.62rem] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-slate-900 shrink-0">Close</button>
                                    </div>
                                    <div id="modelListContainer" class="max-h-60 overflow-y-auto pt-2 pr-0.5 custom-scrollbar space-y-0.5">
                                        {{-- Populated via JS --}}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 relative">
                                <label class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-slate-400">{{ data_get($page?->content, 'lead_form.step1.year_label', 'Year') }}</label>
                                <input type="hidden" name="year" id="sellCarYearInput" value="{{ old('year') }}">
                                <button type="button" id="yearHubToggle" class="group w-full h-16 px-5 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-base font-black text-slate-900 shadow-sm transition-all hover:bg-slate-50">
                                    <span id="yearHubLabel" class="truncate {{ old('year') ? 'text-slate-900' : 'text-slate-400' }}">{{ old('year') ?: 'Year' }}</span>
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 group-hover:rotate-180 transition-transform duration-500"></i>
                                </button>
                                <div id="yearHubDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[300] rounded-[1rem] overflow-hidden border border-slate-200 bg-white/95 backdrop-blur-2xl shadow-[0_40px_120px_-30px_rgba(15,23,42,0.28)] p-2.5 animate-in fade-in zoom-in-95 duration-500">
                                    <div class="grid grid-cols-3 gap-1.5 max-h-60 overflow-y-auto pr-0.5 custom-scrollbar">
                                        @foreach($sellCarYears as $year)
                                            <button type="button" class="year-pick p-2.5 rounded-[1rem] border border-transparent hover:bg-slate-50 hover:border-slate-100 transition-all text-center text-sm font-semibold text-slate-700 hover:text-slate-900" data-year-value="{{ $year }}">
                                                {{ $year }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="button" data-action="next" class="h-16 px-12 bg-[#ff6900] text-white rounded-lg font-black uppercase tracking-widest text-sm hover:-translate-y-0.5 shadow-lg shadow-orange-500/20 transition-all group">
                                <span class="flex items-center gap-3">
                                    {{ data_get($page?->content, 'lead_form.step1.button_label', 'Get Free Valuation') }}
                                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                                </span>
                            </button>
                        </div>
                    </div>



                    {{-- Step 2: Combined Technical & Regional Calibration --}}
                    <div data-step="2" class="hidden animate-in fade-in slide-in-from-right duration-700">
                        <div class="grid grid-cols-2 gap-5">

                            {{-- â”€â”€ LEFT: Dropdown Selects â”€â”€ --}}
                            <div class="space-y-3">
                                <p class="text-[0.5rem] font-black uppercase tracking-[0.25em] text-slate-300">Vehicle Specs</p>

                                {{-- Regional Spec --}}
                                <div class="space-y-1 relative">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">{{ data_get($page?->content, 'lead_form.step2.specs_label', 'Regional Specs') }}</label>
                                    <input type="hidden" name="gcc_specs" id="sellCarGccInput" value="{{ old('gcc_specs', 'GCC') }}">
                                    <button type="button" id="gccHubToggle" class="group w-full h-9 px-3 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-[0.7rem] font-bold text-slate-900 transition-all hover:border-[#ff6900]/40">
                                        <span id="gccHubLabel" class="truncate text-slate-700">GCC Specs</span>
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-300 group-hover:text-[#ff6900] shrink-0 transition-colors"></i>
                                    </button>
                                    <div id="gccHubDrawer" class="hidden absolute left-0 right-0 top-full mt-1 z-[300] rounded-xl border border-slate-100 bg-white shadow-xl p-1.5">
                                        @foreach([['v'=>'GCC','l'=>'GCC Specs'],['v'=>'European','l'=>'European'],['v'=>'American','l'=>'American'],['v'=>'Canadian','l'=>'Canadian'],['v'=>'Korean','l'=>'Korean'],['v'=>'Other','l'=>'Other']] as $reg)
                                            <button type="button" class="gcc-pick block w-full px-3 py-1.5 rounded-lg text-left text-[0.7rem] font-bold text-slate-700 hover:bg-orange-50 hover:text-[#FF6900] transition-all" data-gcc-value="{{ $reg['v'] }}" data-gcc-label="{{ $reg['l'] }}">{{ $reg['l'] }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Body Type --}}
                                <div class="space-y-1 relative">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">{{ data_get($page?->content, 'lead_form.step2.body_label', 'Body Type') }}</label>
                                    <input type="hidden" name="body_type" id="sellCarBodyInput" value="{{ old('body_type') }}">
                                    <button type="button" id="bodyHubToggle" class="group w-full h-9 px-3 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-[0.7rem] font-bold text-slate-900 transition-all hover:border-[#ff6900]/40">
                                        <span id="bodyHubLabel" class="truncate {{ old('body_type') ? 'text-slate-700' : 'text-slate-300' }}">{{ old('body_type') ?: 'Select Type' }}</span>
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-300 shrink-0 group-hover:rotate-180 transition-transform"></i>
                                    </button>
                                    <div id="bodyHubDrawer" class="hidden absolute left-0 right-0 top-full mt-1 z-[300] rounded-xl border border-slate-100 bg-white shadow-xl p-1.5">
                                        @foreach(['Sedan','SUV','Coupe','Hatchback','Pickup','Luxury','Other'] as $type)
                                            <button type="button" class="body-pick block w-full px-3 py-1.5 rounded-lg text-left text-[0.7rem] font-bold text-slate-700 hover:bg-orange-50 hover:text-[#FF6900] transition-all" data-body-value="{{ $type }}">{{ $type }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Engine Size --}}
                                <div class="space-y-1 relative">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">{{ data_get($page?->content, 'lead_form.step2.engine_label', 'Engine Size') }}</label>
                                    <input type="hidden" name="engine" id="sellCarEngineInput" value="{{ old('engine') }}">
                                    <button type="button" id="engineHubToggle" class="group w-full h-9 px-3 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-[0.7rem] font-bold text-slate-900 transition-all hover:border-[#ff6900]/40">
                                        <span id="engineHubLabel" class="truncate {{ old('engine') ? 'text-slate-700' : 'text-slate-300' }}">{{ old('engine') ?: 'Select Engine' }}</span>
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-300 shrink-0 group-hover:rotate-180 transition-transform"></i>
                                    </button>
                                    <div id="engineHubDrawer" class="hidden absolute left-0 right-0 top-full mt-1 z-[300] rounded-xl border border-slate-100 bg-white shadow-xl p-1.5">
                                        @foreach(['1.0L - 1.5L','1.6L - 2.0L','2.1L - 3.0L','3.1L - 4.0L','Over 4.0L','EV / Electric','Other'] as $size)
                                            <button type="button" class="engine-pick block w-full px-3 py-1.5 rounded-lg text-left text-[0.7rem] font-bold text-slate-700 hover:bg-orange-50 hover:text-[#FF6900] transition-all" data-engine-value="{{ $size }}">{{ $size }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Mileage --}}
                                <div class="space-y-1 relative">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">{{ data_get($page?->content, 'lead_form.step2.mileage_label', 'Mileage (KM)') }}</label>
                                    <input type="hidden" name="mileage" id="sellCarMileageInput" value="{{ old('mileage') }}">
                                    <button type="button" id="mileageHubToggle" class="group w-full h-9 px-3 rounded-lg bg-white border border-slate-200 flex items-center justify-between text-[0.7rem] font-bold text-slate-900 transition-all hover:border-[#ff6900]/40">
                                        <span id="mileageHubLabel" class="truncate {{ old('mileage') ? 'text-slate-700' : 'text-slate-300' }}">{{ old('mileage') ?: 'Select Mileage' }}</span>
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-300 shrink-0 group-hover:rotate-180 transition-transform"></i>
                                    </button>
                                    <div id="mileageHubDrawer" class="hidden absolute left-0 right-0 top-full mt-1 z-[300] rounded-xl border border-slate-100 bg-white shadow-xl p-1.5">
                                        <div class="grid grid-cols-2 gap-0.5">
                                            @foreach(['0-20k','20k-50k','50k-100k','100k-150k','150k-200k','Over 200k','Unknown'] as $range)
                                                <button type="button" class="mileage-pick px-2 py-1.5 rounded-lg text-center text-[0.65rem] font-bold text-slate-700 hover:bg-orange-50 hover:text-[#FF6900] transition-all" data-mileage-value="{{ $range }}">{{ $range }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- â”€â”€ RIGHT: Toggle Options â”€â”€ --}}
                            <div class="space-y-3 border-l border-slate-100 pl-5">
                                <p class="text-[0.5rem] font-black uppercase tracking-[0.25em] text-slate-300">Vehicle Options</p>

                                {{-- Trim --}}
                                <div class="space-y-1.5">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">Trim / Options</label>
                                    <input type="hidden" name="trim" id="sellCarTrimInput" value="{{ old('trim', 'Full option') }}">
                                    <div class="grid grid-cols-2 gap-1.5">
                                        @foreach(['Basic','Mid option','Full option','Unknown'] as $opt)
                                            <button type="button" class="trim-pick h-8 rounded-lg border border-slate-100 bg-white font-black text-[0.55rem] uppercase tracking-wide transition-all {{ old('trim','Full option') === $opt ? 'btn-active-orange' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-700' }}" data-trim-value="{{ $opt }}">{{ $opt }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Paint --}}
                                <div class="space-y-1.5">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">Paint Condition</label>
                                    <input type="hidden" name="paint" id="sellCarPaintInput" value="{{ old('paint', 'Original') }}">
                                    <div class="grid grid-cols-2 gap-1.5">
                                        @foreach(['Original','Partial','Total','Unknown'] as $opt)
                                            <button type="button" class="paint-pick h-8 rounded-lg border border-slate-100 bg-white font-black text-[0.55rem] uppercase tracking-wide transition-all {{ old('paint','Original') === $opt ? 'btn-active-orange' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-700' }}" data-paint-value="{{ $opt }}">{{ $opt }}</button>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Overall Condition --}}
                                <div class="space-y-1.5">
                                    <label class="text-[0.55rem] font-black uppercase tracking-[0.2em] text-slate-400">{{ data_get($page?->content, 'lead_form.step2.condition_label', 'Overall Condition') }}</label>
                                    <input type="hidden" name="condition" id="sellCarConditionInput" value="{{ old('condition', 'good') }}">
                                    <div class="grid grid-cols-2 gap-1.5">
                                        @foreach(['excellent'=>'Elite','good'=>'Good','fair'=>'Fair','needs_work'=>'Needs Work'] as $val=>$label)
                                            <button type="button" class="condition-pick h-9 rounded-lg border-2 border-slate-100 bg-white flex items-center justify-center transition-all {{ old('condition','good') === $val ? 'btn-active-orange border-[#FF6900]' : 'text-slate-400 hover:border-[#FF6900]/20 hover:text-slate-700' }}" data-condition-value="{{ $val }}">
                                                <span class="text-[0.55rem] font-black uppercase tracking-wide">{{ $label }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Back / Next Row --}}
                        <div class="flex justify-end items-center gap-3 pt-3 mt-3 border-t border-slate-100">
                            <button type="button" data-action="back" class="h-10 px-7 rounded-lg border border-slate-200 font-black uppercase tracking-widest text-[0.6rem] text-slate-400 hover:bg-slate-50 hover:text-slate-900 transition-all">&larr; Back</button>
                            <button type="button" data-action="next" class="btn-bazar-primary h-10 px-9 rounded-lg font-black uppercase tracking-widest text-[0.65rem] transition-all">Next Stage &rarr;</button>
                        </div>
                    </div>

                    {{-- Step 3: Identity & Expert Booking --}}

                    <div data-step="3" class="hidden animate-in fade-in slide-in-from-right duration-700">
                        <input type="hidden" name="inspection_type" id="inspectionTypeInput" value="branch">

                        {{-- Compact Tab Switcher --}}
                        <div class="flex items-center justify-center -mx-8 -mt-8 border-b border-slate-100 mb-4 overflow-hidden rounded-t-[1rem]">
                            <button type="button" onclick="setInspectionType('branch')" id="btnTabBranch" class="flex-1 py-3 text-[0.65rem] font-black uppercase tracking-[0.2em] transition-all bg-white text-slate-900 border-b-2 border-[#FF6900]">
                                Hub Branches
                            </button>
                            <button type="button" onclick="setInspectionType('home')" id="btnTabHome" class="flex-1 py-3 text-[0.65rem] font-black uppercase tracking-[0.2em] transition-all bg-slate-50 text-slate-400 border-b-2 border-transparent">
                                Home Service <span class="ml-1 text-[0.5rem] bg-orange-100 text-[#FF6900] px-1.5 py-0.5 rounded-full uppercase">Pro</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                            
                            {{-- Left: Map & Official Search --}}
                            <div class="lg:col-span-7 space-y-5">
                                <div id="mapWrapper" class="relative h-[420px] rounded-[2rem] bg-slate-100 border-4 border-white shadow-2xl overflow-hidden group">
                                    {{-- Map Content --}}
                                    <div id="googleMapCanvas" class="absolute inset-0 z-0 bg-slate-200">
                                        @php
                                            $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
                                            $mapProvider = \App\Models\SystemSetting::get('google_maps_provider', 'google');
                                            $branchLat = \App\Models\SystemSetting::get('branch_lat', '25.1384');
                                            $branchLng = \App\Models\SystemSetting::get('branch_lng', '55.2285');
                                        @endphp
                                        @if($mapProvider === 'google' && $googleMapsKey)
                                            <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $branchLat }},{{ $branchLng }}&zoom=14&size=800x600&scale=2&style=feature:all|element:labels|visibility:on&key={{ $googleMapsKey }}" class="w-full h-full object-cover grayscale opacity-50 group-hover:grayscale-0 transition-all duration-1000" alt="Map Preview">
                                        @else
                                            <div id="leafletHomeMap" class="w-full h-full z-0"></div>
                                        @endif
                                    </div>
                                    
                                    {{-- Address Bar (Inside Map Hub) --}}
                                    <div class="absolute inset-x-4 top-4 z-10">
                                        <div id="mapSearchContainer" class="hidden animate-in fade-in slide-in-from-top duration-500">
                                            <div class="relative flex items-center gap-2">
                                                <div class="flex-1 relative group">
                                                    <input type="text" id="homeAddressSearch" name="home_address" placeholder="Official Delivery Address Search" 
                                                           class="w-full h-12 pl-12 pr-6 rounded-md bg-white/95 backdrop-blur-xl border border-white shadow-2xl font-black text-[0.7rem] text-slate-900 focus:border-[#FF6900] focus:ring-0 transition-all">
                                                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-[#FF6900]"></i>
                                                </div>
                                                <button type="button" onclick="detectLocation()" class="w-12 h-12 rounded-md bg-[#FF6900] text-white flex items-center justify-center shadow-2xl hover:scale-105 active:scale-95 transition-all">
                                                    <i data-lucide="crosshair" class="w-6 h-6"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Branch Badge --}}
                                    <div id="mapBranchInfo" class="absolute inset-x-4 bottom-4 z-10 animate-in fade-in slide-in-from-bottom duration-500">
                                        <div class="p-5 rounded-lg bg-white/95 backdrop-blur-xl border border-white shadow-2xl flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-md bg-[#1d293d] flex items-center justify-center shadow-lg">
                                                    <i data-lucide="map-pin" class="w-6 h-6 text-[#FF6900]"></i>
                                                </div>
                                                <div>
                                                    <h5 class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest mb-0.5">HUB AL QUOZ HQ</h5>
                                                    <p class="text-[0.6rem] text-slate-500 font-bold tracking-tight">SZR, EXIT 40, DUBAI - UAE</p>
                                                </div>
                                            </div>
                                            <span class="px-4 py-2 rounded-lg bg-emerald-50 text-emerald-600 text-[0.6rem] font-black uppercase tracking-widest">ACTIVE HUB</span>
                                        </div>
                                    </div>

                                    {{-- Center Marker --}}
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                                        <div class="relative mb-10 transform group-hover:scale-110 transition-transform duration-300">
                                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-[#1d293d]/10 blur-[2px]"></div>
                                            <i data-lucide="map-pin" class="w-12 h-12 text-[#FF6900] drop-shadow-2xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Data Matrix --}}
                            <div class="lg:col-span-5 flex flex-col h-full space-y-5">

                                {{-- Appointment Date + Time --}}
                                <div class="space-y-2">
                                    <label class="text-[0.6rem] font-black uppercase tracking-[0.3em] text-slate-400 flex items-center gap-1.5">
                                        <i data-lucide="calendar-clock" class="w-3 h-3"></i>
                                        {{ data_get($page?->content, 'lead_form.step3.slot_label', 'Appraisal Slot') }}
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">

                                        {{-- â”€â”€ Custom Date Picker â”€â”€ --}}
                                        <div class="relative" id="datePicker">
                                            <input type="hidden" name="inspection_date" id="inspectionDateVal">
                                            <button type="button" id="datePickerToggle"
                                                class="group w-full h-12 px-4 rounded-xl bg-white border-2 border-slate-100 hover:border-[#FF6900]/30 flex items-center gap-2.5 text-left transition-all">
                                                <i data-lucide="calendar" class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-[#FF6900] transition-colors"></i>
                                                <span id="datePickerLabel" class="text-[0.7rem] font-bold text-slate-300 truncate">Select Date</span>
                                            </button>
                                            {{-- Calendar Dropdown --}}
                                            <div id="datePickerDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.5rem)] z-[400] rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-[0_20px_60px_-15px_rgba(15,23,42,0.2)] animate-in fade-in zoom-in-95 duration-200">
                                                {{-- Month Nav --}}
                                                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50">
                                                    <button type="button" id="calPrev" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-400 hover:text-[#FF6900] transition-all">
                                                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                                    </button>
                                                    <span id="calMonthYear" class="text-[0.7rem] font-black text-slate-800 uppercase tracking-widest"></span>
                                                    <button type="button" id="calNext" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-400 hover:text-[#FF6900] transition-all">
                                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                                    </button>
                                                </div>
                                                {{-- Day headers --}}
                                                <div class="grid grid-cols-7 px-3 pt-2 pb-1">
                                                    @foreach(['S','M','T','W','T','F','S'] as $d)
                                                    <div class="text-center text-[0.5rem] font-black uppercase text-slate-300 py-1">{{ $d }}</div>
                                                    @endforeach
                                                </div>
                                                {{-- Days grid --}}
                                                <div id="calDaysGrid" class="grid grid-cols-7 gap-0.5 px-3 pb-3"></div>
                                            </div>
                                        </div>

                                        {{-- â”€â”€ Custom Time Picker â”€â”€ --}}
                                        <div class="relative" id="timePicker">
                                            <input type="hidden" name="inspection_time" id="inspectionTimeVal">
                                            <button type="button" id="timePickerToggle"
                                                class="group w-full h-12 px-4 rounded-xl bg-white border-2 border-slate-100 hover:border-[#FF6900]/30 flex items-center gap-2.5 text-left transition-all">
                                                <i data-lucide="clock" class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-[#FF6900] transition-colors"></i>
                                                <span id="timePickerLabel" class="text-[0.7rem] font-bold text-slate-300 truncate">Time Slot</span>
                                            </button>
                                            {{-- Drum Picker Dropdown --}}
                                            <div id="timePickerDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.5rem)] z-[400] rounded-2xl border border-slate-100 bg-white shadow-[0_20px_60px_-15px_rgba(15,23,42,0.2)] animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                                                {{-- Header --}}
                                                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50">
                                                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">Pick Time</span>
                                                    <div class="flex gap-1">
                                                        <button type="button" id="amToggle" class="px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all">AM</button>
                                                        <button type="button" id="pmToggle" class="px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-slate-100 text-slate-400 transition-all">PM</button>
                                                    </div>
                                                </div>

                                                {{-- Drums --}}
                                                <div class="flex items-stretch gap-0 px-3 py-3">

                                                    {{-- Hour Drum --}}
                                                    <div class="flex-1 flex flex-col items-center">
                                                        <span class="text-[0.45rem] font-black uppercase tracking-widest text-slate-300 mb-2">Hour</span>
                                                        <button type="button" id="hrUp" class="w-8 h-6 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                                                            <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                                        </button>
                                                        <div class="relative h-[72px] overflow-hidden w-full flex flex-col items-center justify-center">
                                                            <div id="hrPrev" class="text-[0.9rem] font-bold text-slate-200 leading-none py-1 text-center transition-all duration-200"></div>
                                                            <div id="hrCurrent" class="text-[1.4rem] font-black text-[#FF6900] leading-none py-1.5 px-4 bg-orange-50 rounded-xl w-full text-center transition-all duration-200"></div>
                                                            <div id="hrNext" class="text-[0.9rem] font-bold text-slate-200 leading-none py-1 text-center transition-all duration-200"></div>
                                                        </div>
                                                        <button type="button" id="hrDown" class="w-8 h-6 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                                                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>

                                                    {{-- Separator --}}
                                                    <div class="flex items-center justify-center px-2">
                                                        <span class="text-2xl font-black text-slate-200">:</span>
                                                    </div>

                                                    {{-- Minute Drum --}}
                                                    <div class="flex-1 flex flex-col items-center">
                                                        <span class="text-[0.45rem] font-black uppercase tracking-widest text-slate-300 mb-2">Min</span>
                                                        <button type="button" id="minUp" class="w-8 h-6 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                                                            <i data-lucide="chevron-up" class="w-4 h-4"></i>
                                                        </button>
                                                        <div class="relative h-[72px] overflow-hidden w-full flex flex-col items-center justify-center">
                                                            <div id="minPrev" class="text-[0.9rem] font-bold text-slate-200 leading-none py-1 text-center"></div>
                                                            <div id="minCurrent" class="text-[1.4rem] font-black text-[#FF6900] leading-none py-1.5 px-4 bg-orange-50 rounded-xl w-full text-center"></div>
                                                            <div id="minNext" class="text-[0.9rem] font-bold text-slate-200 leading-none py-1 text-center"></div>
                                                        </div>
                                                        <button type="button" id="minDown" class="w-8 h-6 flex items-center justify-center rounded-lg hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                                                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                {{-- Confirm --}}
                                                <div class="px-3 pb-3">
                                                    <button type="button" id="timeConfirm" class="w-full py-2.5 bg-[#FF6900] text-white rounded-xl text-[0.65rem] font-black uppercase tracking-widest hover:bg-orange-600 transition-all shadow-md shadow-orange-200">
                                                        Confirm Time
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                {{-- Contact Fields --}}
                                <div class="space-y-3">
                                    {{-- Full Name --}}
                                    <div class="space-y-1.5">
                                        <label class="text-[0.55rem] font-black uppercase tracking-[0.25em] text-slate-400 flex items-center gap-1">
                                            <i data-lucide="user" class="w-2.5 h-2.5"></i>
                                            {{ data_get($page?->content, 'lead_form.step3.name_label', 'Full Name') }}
                                        </label>
                                        <div class="relative group">
                                            <input type="text" name="name" placeholder="Enter your full name"
                                                   class="w-full h-12 px-4 rounded-xl bg-white border-2 border-slate-100 focus:border-[#FF6900] focus:outline-none text-[0.75rem] font-semibold text-slate-800 placeholder:text-slate-300 placeholder:font-normal transition-all">
                                        </div>
                                    </div>
                                    {{-- Phone --}}
                                    <div class="space-y-1.5">
                                        <label class="text-[0.55rem] font-black uppercase tracking-[0.25em] text-slate-400 flex items-center gap-1">
                                            <i data-lucide="phone" class="w-2.5 h-2.5"></i>
                                            {{ data_get($page?->content, 'lead_form.step3.phone_label', 'Mobile Number') }}
                                        </label>
                                        <div class="relative flex items-center">
                                            <span class="absolute left-0 h-full flex items-center px-3.5 text-[0.7rem] font-black text-slate-400 border-r border-slate-100">&#x1F1E6;&#x1F1EA;</span>
                                            <input type="tel" name="phone" placeholder="+971 50 000 0000"
                                                   class="w-full h-12 pl-14 pr-4 rounded-xl bg-white border-2 border-slate-100 focus:border-[#FF6900] focus:outline-none text-[0.75rem] font-semibold text-slate-800 placeholder:text-slate-300 placeholder:font-normal transition-all">
                                        </div>
                                    </div>
                                    {{-- Email --}}
                                    <div class="space-y-1.5">
                                        <label class="text-[0.55rem] font-black uppercase tracking-[0.25em] text-slate-400 flex items-center gap-1">
                                            <i data-lucide="mail" class="w-2.5 h-2.5"></i>
                                            {{ data_get($page?->content, 'lead_form.step3.email_label', 'Email Address') }}
                                        </label>
                                        <input type="email" name="email" placeholder="you@example.com"
                                               class="w-full h-12 px-4 rounded-xl bg-white border-2 border-slate-100 focus:border-[#FF6900] focus:outline-none text-[0.75rem] font-semibold text-slate-800 placeholder:text-slate-300 placeholder:font-normal transition-all">
                                    </div>
                                </div>



                                {{-- Final Action --}}
                                <div class="mt-auto pt-6 space-y-4">
                                    <button type="submit" data-action="submit" class="btn-bazar-primary w-full h-14 rounded-lg font-black uppercase tracking-[0.25em] text-[0.75rem] transition-all shadow-2xl shadow-orange-500/30">{{ data_get($page?->content, 'lead_form.step3.submit_label', 'Request Free Valuation') }}</button>
                                    <button type="button" data-action="back" class="w-full text-center text-[0.6rem] font-black uppercase tracking-[0.35em] text-slate-400 hover:text-slate-900 transition-all">&larr; Adjust Specs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Trust Badges: Centered Icon + Title + Description --}}
            @php
                $trustBadges = data_get($page?->content, 'trust_badges', [
                    ['label' => 'Guaranteed Purchase',    'icon' => 'shield-check', 'color' => '#ff4605', 'bg_color' => '#fff7ed', 'desc' => 'We guarantee every transaction is safe, verified, and backed by Motor Bazar.'],
                    ['label' => 'No Costs. No Obligation','icon' => 'wallet',       'color' => '#031629', 'bg_color' => '#f1f5f9', 'desc' => 'Free valuations with zero hidden fees. Walk away any time — no strings attached.'],
                    ['label' => 'Quick and Easy',         'icon' => 'zap',          'color' => '#ff6900', 'bg_color' => '#fff7ed', 'desc' => 'Submit your car in under 3 minutes. Our team contacts you within 24 hours.'],
                    ['label' => 'Fast and Secure',        'icon' => 'lock',         'color' => '#334155', 'bg_color' => '#f8fafc', 'desc' => 'Bank-grade encryption protects your data and payment at every step.'],
                ]);
                $badgesTitle = data_get($page?->content, 'trust_badges_title', 'We built our business on trust');
            @endphp

            {{-- Section Heading --}}
            <div class="mt-6 mb-8 text-center">
                <h3 class="text-2xl lg:text-3xl font-black text-[#031629] tracking-tight">{{ $badgesTitle }}</h3>
            </div>

            {{-- Badges Grid --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 relative">
                {{-- Gradient separator lines --}}
                <div class="hidden xl:block absolute top-[10%] bottom-[10%] left-1/4 w-px" style="background: linear-gradient(to bottom, transparent, #cbd5e1, transparent);"></div>
                <div class="hidden xl:block absolute top-[10%] bottom-[10%] left-2/4 w-px" style="background: linear-gradient(to bottom, transparent, #cbd5e1, transparent);"></div>
                <div class="hidden xl:block absolute top-[10%] bottom-[10%] left-3/4 w-px" style="background: linear-gradient(to bottom, transparent, #cbd5e1, transparent);"></div>
                <div class="xl:hidden absolute left-[10%] right-[10%] top-1/2 h-px" style="background: linear-gradient(to right, transparent, #cbd5e1, transparent);"></div>
                @foreach($trustBadges as $i => $badge)
                @php
                    $bColor = data_get($badge, 'color', '#333');
                    $bIcon  = data_get($badge, 'icon', 'star');
                    $bLabel = data_get($badge, 'label', '');
                    $bDesc  = data_get($badge, 'desc', '');
                @endphp
                <div class="group flex flex-col items-center text-center px-8 py-6 cursor-default">

                    {{-- Large Icon with Color Glow on Hover --}}
                    <div class="relative w-20 h-20 mb-5 flex items-center justify-center">
                        {{-- Glow background (no filter - uses bg blur instead) --}}
                        <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-30 transition-all duration-500 blur-xl"
                             style="background-color: {{ $bColor }};"></div>
                        {{-- Icon --}}
                        <i data-lucide="{{ $bIcon }}" class="relative w-12 h-12 transition-all duration-300 group-hover:-translate-y-2 group-hover:scale-110"
                           style="stroke-width: 1.5; color: {{ $bColor }};"></i>
                    </div>

                    {{-- Label --}}
                    <p class="text-[0.92rem] font-black text-[#031629] mb-2 tracking-tight">{{ $bLabel }}</p>

                    {{-- Description --}}
                    @if($bDesc)
                    <p class="text-[0.75rem] text-slate-500 font-medium leading-relaxed max-w-[200px]">{{ $bDesc }}</p>
                    @endif
                </div>
                @endforeach
            </div>

        </div>
    </section>


    {{-- Latest Luxury Listings: Elite Inventory Segment --}}
                <section class="py-24 px-6 lg:px-12 bg-[#e7e7e7] relative z-30">
                    <div class="max-w-[1440px] mx-auto">
                        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-5 mb-20">
                            <div class="space-y-4">
                                <div class="h-1 w-12 bg-[#ff4605] rounded-full"></div>
                                <h2 class="text-5xl lg:text-7xl font-black text-[#031629] leading-tight tracking-tight">Active <br>Market.</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($featuredAuctions as $auction)
                <div class="car-card">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $auction->car->image_url ?? $fallbackCarImages[$loop->index % count($fallbackCarImages)] }}" class="w-full h-full object-cover" alt="Featured car image">
                        <div class="absolute top-5 left-5 flex flex-col gap-2">
                            <span class="badge-year">{{ $auction->car->year ?? '2024' }}</span>
                            @if($auction->status === 'coming_soon')
                                <span class="bg-[#ff6900] text-white text-[0.6rem] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-lg shadow-orange-500/30">Coming Soon</span>
                            @elseif($auction->status === 'active')
                                <div class="active-countdown bg-emerald-500 text-white text-[0.6rem] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-xl shadow-emerald-500/20 flex items-center gap-2" data-end-at="{{ $auction->end_at->toIso8601String() }}">
                                    <i data-lucide="clock" class="w-3"></i> 
                                    <span class="timer-values">--:--:--</span>
                                </div>
                            @endif
                        </div>
                        <div class="absolute bottom-5 right-5">
                            <button class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center text-white"><i data-lucide="star" class="w-4"></i></button>
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-black text-slate-900 mb-2">{{ $auction->car->make }} {{ $auction->car->model }}</h3>
                        <div class="flex items-center gap-4 text-[0.6rem] text-gray-500 font-bold uppercase tracking-widest mb-6">
                            <span>{{ $auction->car->transmission ?? 'Automatic' }}</span> â€¢ <span>{{ $auction->car->fuel_type ?? 'Petrol' }}</span> â€¢ <span>{{ $auction->car->color ?? 'Silver' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-white/5 pt-6">
                            <div>
                                <span class="text-gray-500 text-[0.6rem] font-bold uppercase tracking-widest block mb-1">Current Bid</span>
                                <span class="text-2xl font-black text-bazar-500">${{ number_format($auction->current_price) }}</span>
                            </div>
                            <a href="{{ route('auctions.show', $auction) }}" class="w-12 h-12 rounded-md bg-[#1d293d] flex items-center justify-center text-white hover:bg-bazar-500 transition-all">
                                <i data-lucide="arrow-right" class="w-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Location Map Card --}}
    <section class="py-16 px-6 lg:px-12 bg-[#e7e7e7] relative z-10">
        <div class="max-w-[1440px] mx-auto">

            {{-- Section Label --}}
            <div class="flex items-center gap-4 mb-8">
                <div class="h-1 w-10 bg-[#ff4605] rounded-full"></div>
                <span class="text-[0.62rem] font-black uppercase tracking-[0.3em] text-slate-500">Find Us</span>
            </div>

            {{-- Map Card --}}
            <div class="relative rounded-3xl overflow-hidden shadow-[0_24px_80px_-20px_rgba(3,22,41,0.18)] min-h-[420px]">

                {{-- Map Embed --}}
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3608.6!2d55.296249!3d25.264171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDE1JzUxLjAiTiA1NcKwMTcnNDYuNSJF!5e0!3m2!1sen!2sae!4v1680000000000!5m2!1sen!2sae"
                    width="100%" height="100%"
                    class="absolute inset-0 w-full h-full object-cover"
                    style="border:0; min-height: 420px; filter: grayscale(20%) contrast(1.05);"
                    allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                {{-- Overlay gradient --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#031629]/90 via-[#031629]/50 to-transparent pointer-events-none"></div>

                {{-- Info Card --}}
                <div class="relative z-10 flex flex-col justify-center h-full min-h-[420px] max-w-sm px-10 py-10">

                    <h2 class="text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight mb-2">
                        Visit Motor<br><span class="text-[#ff4605]">Bazar</span>
                    </h2>
                    <p class="text-slate-300 text-sm font-medium mb-8">Come see our full inventory in person — our team is ready to help.</p>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#ff4605]/10 border border-[#ff4605]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4 text-[#ff4605]"></i>
                            </div>
                            <div>
                                <p class="text-[0.5rem] font-black uppercase tracking-widest text-slate-400 mb-0.5">Address</p>
                                <p class="text-sm font-bold text-white">{{ data_get($page?->content, 'location.address', 'Dubai, United Arab Emirates') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#ff4605]/10 border border-[#ff4605]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="phone" class="w-4 h-4 text-[#ff4605]"></i>
                            </div>
                            <div>
                                <p class="text-[0.5rem] font-black uppercase tracking-widest text-slate-400 mb-0.5">Phone</p>
                                <p class="text-sm font-bold text-white">{{ data_get($page?->content, 'location.phone', '+971 4 000 0000') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#ff4605]/10 border border-[#ff4605]/20 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-4 h-4 text-[#ff4605]"></i>
                            </div>
                            <div>
                                <p class="text-[0.5rem] font-black uppercase tracking-widest text-slate-400 mb-0.5">Working Hours</p>
                                <p class="text-sm font-bold text-white">{{ data_get($page?->content, 'location.hours', 'Mon – Sat: 9:00 AM – 7:00 PM') }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ data_get($page?->content, 'location.maps_url', 'https://maps.google.com') }}"
                       target="_blank"
                       class="inline-flex items-center gap-2.5 bg-[#ff4605] hover:bg-[#ff6900] text-white font-black text-[0.72rem] uppercase tracking-[0.2em] px-6 py-3.5 rounded-xl transition-all duration-300 hover:shadow-[0_8px_24px_-6px_rgba(255,70,5,0.5)] hover:-translate-y-0.5 w-fit">
                        <i data-lucide="navigation" class="w-4 h-4"></i>
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Body Type Browser: Dynamic CMS Sync --}}
    <section class="py-20 px-6 lg:px-12 bg-transparent relative z-10">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="text-bazar-500 font-black uppercase tracking-[0.35em] text-[0.65rem] mb-3 block">Browse by category</span>
                    <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-deep-900">Search cars by body type</h2>
                </div>
                <a href="{{ route('auctions.index') }}" class="text-deep-900 font-black text-xs uppercase tracking-[0.22em] border-b-2 border-bazar-500 pb-1 w-fit">View all inventory</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6" id="body-types-container">
                @php
                    $defaultBodyTypes = [
                        ['label' => 'Sedan', 'icon' => 'car', 'slug' => 'sedan'],
                        ['label' => 'SUV', 'icon' => 'shield', 'slug' => 'suv'],
                        ['label' => 'Coupe', 'icon' => 'zap', 'slug' => 'coupe'],
                        ['label' => 'Hatch', 'icon' => 'box', 'slug' => 'hatchback'],
                        ['label' => 'Cabrio', 'icon' => 'sun', 'slug' => 'cabrio'],
                        ['label' => 'Pickup', 'icon' => 'truck', 'slug' => 'pickup'],
                    ];
                    $bodyTypes = data_get($page?->content, 'body_types', []) ?: $defaultBodyTypes;
                @endphp
                @foreach($bodyTypes as $type)
                <div class="body-type-card" onclick="window.location.href='{{ route('auctions.index', ['body_type' => $type['slug']]) }}'">
                    <i data-lucide="{{ $type['icon'] ?? 'car' }}" class="w-10 h-10 mx-auto mb-4 text-bazar-500"></i>
                    <span class="text-sm font-black uppercase tracking-widest text-deep-900">{{ $type['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Brands Carousel: Rotating Slider --}}
    <section class="py-16 bg-transparent relative overflow-hidden z-10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-bazar-500 font-black uppercase tracking-[0.35em] text-[0.65rem] mb-2 block">Trusted Partners</span>
                    <h2 class="text-2xl lg:text-3xl font-black tracking-tight text-deep-900">Premium Brands</h2>
                </div>
                <a href="{{ route('auctions.index') }}" class="text-gray-600 hover:text-deep-900 font-bold text-xs uppercase tracking-widest border-b border-gray-400 pb-1 transition-colors">View All</a>
            </div>
        </div>
        
        @php
            $brands = data_get($page?->content, 'brands', []);
            if (count($brands) > 0) {
                // Repeat brands enough times to fill viewport width (need at least 12+ duplicates)
                $repeatCount = max(20, ceil(12 / count($brands)) * 2);
                $displayBrands = [];
                for ($i = 0; $i < $repeatCount; $i++) {
                    $displayBrands = array_merge($displayBrands, $brands);
                }
            } else {
                $displayBrands = [];
            }
        @endphp
        
        <div class="brands-carousel-container relative">
            <div class="brands-track" id="brands-track">
                @foreach($displayBrands as $brand)
                    @php
                        $logoPath = '/images/brands/' . $brand['slug'] . '.svg';
                        if (!file_exists(public_path($logoPath))) {
                            $logoPath = '/images/brands/' . $brand['slug'] . '.png';
                        }
                    @endphp
                    <a href="{{ route('auctions.index', ['make' => $brand['name']]) }}" class="brand-slide group">
                        <div class="brand-logo-wrapper" style="width:60px;height:60px;overflow:hidden;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <img src="{{ $logoPath }}" alt="{{ $brand['name'] }}" class="brand-logo" style="max-width:100%;max-height:100%;object-fit:contain;filter:grayscale(100%) opacity(0.6)">
                        </div>
                        <span class="brand-name">{{ $brand['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        /* Date & Time picker premium styling */
        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        input[type="date"]:focus,
        select:focus {
            box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.08);
        }
        select option {
            font-weight: 600;
            color: #1e293b;
        }
        /* Smooth focus borders for step3 fields */
        .step3-field:focus {
            border-color: #ff6900;
            box-shadow: 0 0 0 3px rgba(255,105,0,0.07);
        }

        .brands-carousel-container {

            width: 100%;
            overflow: hidden;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        
        .brands-track {
            display: flex;
            gap: 60px;
            animation: scrollBrands 600s linear infinite;
            width: max-content;
            padding: 20px 0;
        }
        
        .brands-track:hover {
            animation-play-state: running;
        }
        
        @keyframes scrollBrands {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        
        .brand-slide {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px 30px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 1rem;
            min-width: 140px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 18px 40px -22px rgba(15, 23, 42, 0.18);
        }
        
        .brand-slide:hover {
            background: #ffffff;
            border-color: rgba(255, 70, 5, 0.22);
            transform: translateY(-5px);
        }
        
        .brand-logo-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .brand-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: grayscale(100%) opacity(0.6);
            transition: all 0.3s ease;
        }
        
        .brand-slide:hover .brand-logo {
            filter: grayscale(0%) opacity(1);
        }
        
        .brand-name {
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: color 0.3s ease;
        }
        
        .brand-slide:hover .brand-name {
            color: #ff4605;
        }
    </style>

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
         BAZAR TOAST NOTIFICATION SYSTEM
         Replaces native alert() with premium UI
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <div id="bazarToastContainer" class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] flex flex-col gap-3 items-center pointer-events-none" style="min-width:320px;max-width:480px;"></div>

    <script>
        window.BazarToast = {
            show(message, type = 'warning') {
                const container = document.getElementById('bazarToastContainer');
                if (!container) return;

                const configs = {
                    warning: {
                        bg: 'from-[#FF6900] to-[#e55a00]',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>`,
                    },
                    error: {
                        bg: 'from-red-600 to-red-700',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376A12 12 0 1 1 2.697 3.334m19.606 13.042L3.697 3.334"/></svg>`,
                    },
                    success: {
                        bg: 'from-emerald-500 to-emerald-600',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>`,
                    },
                    info: {
                        bg: 'from-slate-700 to-slate-800',
                        icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/></svg>`,
                    },
                };

                const cfg = configs[type] || configs.warning;

                const toast = document.createElement('div');
                toast.className = 'pointer-events-auto flex items-start gap-4 px-5 py-4 rounded-lg text-white shadow-2xl bg-gradient-to-br ' + cfg.bg + ' transform translate-y-[-20px] opacity-0 transition-all duration-300 ease-out w-full';
                toast.style.backdropFilter = 'blur(20px)';
                toast.innerHTML = `
                    <div class="pt-0.5 shrink-0">${cfg.icon}</div>
                    <div class="flex-1">
                        <p class="text-[0.8rem] font-bold leading-snug">${message}</p>
                    </div>
                    <button onclick="this.closest('[data-bazar-toast]').remove()" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                `;
                toast.setAttribute('data-bazar-toast', '1');
                container.appendChild(toast);

                // Animate in
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-y-[-20px]', 'opacity-0');
                        toast.classList.add('translate-y-0', 'opacity-100');
                    });
                });

                // Auto dismiss
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-[-20px]');
                    setTimeout(() => toast.remove(), 350);
                }, 4500);
            },
            warn(msg)    { this.show(msg, 'warning'); },
            error(msg)   { this.show(msg, 'error'); },
            success(msg) { this.show(msg, 'success'); },
            info(msg)    { this.show(msg, 'info'); },
        };
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wizard = document.getElementById('sellCarWizard');
            if (!wizard) return;

            const brandModelMap = @json($catalogModelsByMake ?? []);
            const startStep = Math.max(0, (parseInt(wizard.dataset.startStep || '1', 10) || 1) - 1);

            const steps = Array.from(wizard.querySelectorAll('[data-step]'));
            const btnBacks = Array.from(wizard.querySelectorAll('[data-action="back"]'));
            const btnNexts = Array.from(wizard.querySelectorAll('[data-action="next"]'));
            const btnSubmit = wizard.querySelector('[data-action="submit"]') || wizard.querySelector('button[type="submit"]');

            const brandHubToggle = document.getElementById('brandHubToggle');
            const brandHubDrawer = document.getElementById('brandHubDrawer');
            const brandHubSearch = document.getElementById('brandHubSearch');
            const closeBrandHub = document.getElementById('closeBrandHub');
            const brandHubLabel = document.getElementById('brandHubLabel');
            const brandHubIcon = document.getElementById('brandHubIcon');
            const brandHubIconImg = document.getElementById('brandHubIconImg');
            const makeSelect = document.getElementById('sellCarMakeSelect_dynamic');
            const brandHubOptions = Array.from(document.querySelectorAll('[data-brand-hub-value]'));
            const popularBrandPicks = Array.from(document.querySelectorAll('[data-brand-pick]'));
            const resetBrandHub = document.getElementById('resetBrandHub');

            const modelHubToggle = document.getElementById('modelHubToggle');
            const modelHubDrawer = document.getElementById('modelHubDrawer');
            const modelHubSearch = document.getElementById('modelHubSearch');
            const modelHubLabel = document.getElementById('modelHubLabel');
            const modelListContainer = document.getElementById('modelListContainer');
            const modelInput = document.getElementById('sellCarModelInput');

            const yearHubToggle = document.getElementById('yearHubToggle');
            const yearHubDrawer = document.getElementById('yearHubDrawer');
            const yearHubLabel = document.getElementById('yearHubLabel');
            const yearInput = document.getElementById('sellCarYearInput');
            const yearPicks = document.querySelectorAll('.year-pick');

            const gccInput = document.getElementById('sellCarGccInput');
            const gccPicks = document.querySelectorAll('.gcc-pick');
            const gccHubToggle = document.getElementById('gccHubToggle');
            const gccHubDrawer = document.getElementById('gccHubDrawer');
            const gccHubLabel = document.getElementById('gccHubLabel');

            const bodyHubToggle = document.getElementById('bodyHubToggle');
            const bodyHubDrawer = document.getElementById('bodyHubDrawer');
            const bodyHubLabel = document.getElementById('bodyHubLabel');
            const bodyInput = document.getElementById('sellCarBodyInput');
            const bodyPicks = document.querySelectorAll('.body-pick');

            const engineHubToggle = document.getElementById('engineHubToggle');
            const engineHubDrawer = document.getElementById('engineHubDrawer');
            const engineHubLabel = document.getElementById('engineHubLabel');
            const engineInput = document.getElementById('sellCarEngineInput');
            const enginePicks = document.querySelectorAll('.engine-pick');

            const mileageHubToggle = document.getElementById('mileageHubToggle');
            const mileageHubDrawer = document.getElementById('mileageHubDrawer');
            const mileageHubLabel = document.getElementById('mileageHubLabel');
            const mileageInput = document.getElementById('sellCarMileageInput');
            const mileagePicks = document.querySelectorAll('.mileage-pick');

            const trimInput = document.getElementById('sellCarTrimInput');
            const trimPicks = document.querySelectorAll('.trim-pick');
            const paintInput = document.getElementById('sellCarPaintInput');
            const paintPicks = document.querySelectorAll('.paint-pick');
            const conditionInput = document.getElementById('sellCarConditionInput');
            const conditionPicks = document.querySelectorAll('.condition-pick');

            const dateHubToggle = document.getElementById('dateHubToggle');
            const dateHubDrawer = document.getElementById('dateHubDrawer');
            const dateHubLabel = document.getElementById('dateHubLabel');
            const dateInput = document.getElementById('inspection_date_input');
            const datePicks = document.querySelectorAll('.date-pick');

            const timeHubToggle = document.getElementById('timeHubToggle');
            const timeHubDrawer = document.getElementById('timeHubDrawer');
            const timeHubLabel = document.getElementById('timeHubLabel');
            const timeInput = document.getElementById('inspection_time_input');
            const timePicks = document.querySelectorAll('.time-pick');

            let currentIdx = startStep;

            const normalizeMake = (value = '') => String(value).toLowerCase().replace(/[^a-z0-9]+/g, '');

            function parseModels(raw) {
                if (!raw) return [];
                try {
                    const parsed = JSON.parse(raw);
                    return Array.isArray(parsed) ? parsed : [];
                } catch (e) {
                    return [];
                }
            }

            function getModelList(makeValue) {
                const key = normalizeMake(makeValue);
                return brandModelMap[key] || brandModelMap.__all__ || [];
            }

            function populateModels(models, selectedModel = '') {
                if (!modelListContainer || !modelHubToggle) return;

                modelListContainer.innerHTML = '';

                if (!models.length) {
                    modelHubToggle.disabled = true;
                    modelHubToggle.classList.add('bg-slate-50', 'text-slate-400');
                    modelHubToggle.classList.remove('bg-white', 'text-slate-900');
                    modelHubLabel.textContent = 'No models found';
                    return;
                }

                modelHubToggle.disabled = false;
                modelHubToggle.classList.remove('bg-slate-50', 'text-slate-400');
                modelHubToggle.classList.add('bg-white', 'text-slate-900');

                if (!selectedModel) {
                    modelHubLabel.textContent = 'Select Model';
                }

                models.forEach(model => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'w-full p-2.5 rounded-[1rem] border border-transparent hover:bg-slate-50 hover:border-slate-200 transition-all text-left group flex items-center gap-3';
                    btn.setAttribute('data-model-value', model);

                    const bullet = document.createElement('span');
                    bullet.className = 'w-1.5 h-1.5 rounded-full bg-slate-200 group-hover:bg-orange-400 transition-colors shrink-0';
                    
                    const text = document.createElement('span');
                    text.className = 'text-sm font-semibold text-slate-700 group-hover:text-slate-900 truncate';
                    text.textContent = model;

                    btn.appendChild(bullet);
                    btn.appendChild(text);

                    btn.addEventListener('click', () => {
                        setSelectedModel(model);
                        toggleModelHub(false);
                    });

                    modelListContainer.appendChild(btn);
                });

                if (selectedModel) {
                    setSelectedModel(selectedModel);
                }
            }

            function setSelectedModel(value) {
                if (modelInput) modelInput.value = value || '';
                if (modelHubLabel) {
                    modelHubLabel.textContent = value || 'Select Model';
                    modelHubLabel.classList.toggle('text-slate-400', !value);
                    modelHubLabel.classList.toggle('text-slate-900', !!value);
                }
                
                const modelBtns = Array.from(modelListContainer?.querySelectorAll('[data-model-value]') || []);
                modelBtns.forEach(btn => {
                    const isSelected = btn.getAttribute('data-model-value') === value;
                    btn.classList.toggle('bg-orange-50', isSelected);
                    btn.classList.toggle('border-orange-200', isSelected);
                    btn.querySelector('.text-sm')?.classList.toggle('text-slate-900', isSelected);
                    btn.querySelector('.rounded-full')?.classList.toggle('bg-[#ff6900]', isSelected);
                });
            }

            function setSelectedBrand(value, logo = '', preserveModel = false, models = []) {
                if (makeSelect) {
                    makeSelect.value = value || '';
                }

                if (brandHubLabel) {
                    brandHubLabel.textContent = value || 'Select Brand';
                    brandHubLabel.classList.toggle('text-slate-400', !value);
                    brandHubLabel.classList.toggle('text-slate-900', !!value);
                }

                if (brandHubIcon && brandHubIconImg) {
                    if (logo) {
                        brandHubIconImg.src = logo;
                        brandHubIconImg.alt = value || 'Brand logo';
                        brandHubIcon.classList.remove('hidden');
                    } else {
                        brandHubIcon.classList.add('hidden');
                    }
                }

                brandHubOptions.forEach(btn => {
                    const btnValue = btn.getAttribute('data-brand-hub-value') || '';
                    btn.classList.toggle('bg-orange-50', btnValue === value);
                    btn.classList.toggle('border-orange-200', btnValue === value);
                    btn.querySelector('.text-sm')?.classList.toggle('text-slate-900', btnValue === value);
                });

                const resolvedModels = Array.isArray(models) && models.length ? models : getModelList(value);

                if (!preserveModel) {
                    setSelectedModel('');
                }

                populateModels(resolvedModels, preserveModel ? (modelInput?.value || '') : '');
            }

            function toggleGccHub(force = null) {
                if (!gccHubDrawer) return;
                const shouldOpen = force === null ? gccHubDrawer.classList.contains('hidden') : force;
                gccHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleBodyHub(false);
                    toggleEngineHub(false);
                    toggleMileageHub(false);
                }
            }

            function toggleBodyHub(force = null) {
                if (!bodyHubDrawer) return;
                const shouldOpen = force === null ? bodyHubDrawer.classList.contains('hidden') : force;
                bodyHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleEngineHub(false);
                    toggleMileageHub(false);
                }
            }

            function toggleEngineHub(force = null) {
                if (!engineHubDrawer) return;
                const shouldOpen = force === null ? engineHubDrawer.classList.contains('hidden') : force;
                engineHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleBodyHub(false);
                    toggleMileageHub(false);
                }
            }

            function toggleMileageHub(force = null) {
                if (!mileageHubDrawer) return;
                const shouldOpen = force === null ? mileageHubDrawer.classList.contains('hidden') : force;
                mileageHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleBodyHub(false);
                    toggleEngineHub(false);
                }
            }

            function clearSelectedBrand() {
                if (makeSelect) {
                    makeSelect.value = '';
                }

                if (brandHubLabel) {
                    brandHubLabel.textContent = 'Select Brand';
                    brandHubLabel.classList.add('text-slate-400');
                    brandHubLabel.classList.remove('text-slate-900');
                }

                if (brandHubIcon) {
                    brandHubIcon.classList.add('hidden');
                }

                if (brandHubIconImg) {
                    brandHubIconImg.removeAttribute('src');
                    brandHubIconImg.removeAttribute('alt');
                }

                brandHubOptions.forEach(btn => {
                    btn.classList.remove('bg-orange-50', 'border-orange-200');
                    btn.querySelector('.text-sm')?.classList.remove('text-slate-900');
                });

                if (brandHubSearch) {
                    brandHubSearch.value = '';
                    brandHubSearch.dispatchEvent(new Event('input'));
                }

                setSelectedModel('');
                populateModels([]);
            }

            function toggleBrandHub(force = null) {
                if (!brandHubDrawer) return;
                const shouldOpen = force === null ? brandHubDrawer.classList.contains('hidden') : force;
                brandHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleModelHub(false);
                    toggleYearHub(false);
                    toggleDateHub(false);
                    toggleTimeHub(false);
                    if (brandHubSearch) brandHubSearch.focus();
                }
            }

            function toggleModelHub(force = null) {
                if (!modelHubDrawer || modelHubToggle.disabled) return;
                const shouldOpen = force === null ? modelHubDrawer.classList.contains('hidden') : force;
                modelHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleYearHub(false);
                    toggleDateHub(false);
                    toggleTimeHub(false);
                    if (modelHubSearch) modelHubSearch.focus();
                }
            }

            function toggleYearHub(force = null) {
                if (!yearHubDrawer) return;
                const shouldOpen = force === null ? yearHubDrawer.classList.contains('hidden') : force;
                yearHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleDateHub(false);
                    toggleTimeHub(false);
                }
            }

            function toggleDateHub(force = null) {
                if (!dateHubDrawer) return;
                const shouldOpen = force === null ? dateHubDrawer.classList.contains('hidden') : force;
                dateHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleYearHub(false);
                    toggleTimeHub(false);
                }
            }

            function toggleTimeHub(force = null) {
                if (!timeHubDrawer) return;
                const shouldOpen = force === null ? timeHubDrawer.classList.contains('hidden') : force;
                timeHubDrawer.classList.toggle('hidden', !shouldOpen);
                if (shouldOpen) {
                    toggleBrandHub(false);
                    toggleModelHub(false);
                    toggleYearHub(false);
                    toggleDateHub(false);
                }
            }

            function validateStep(stepIndex) {
                const step = steps[stepIndex];
                if (!step) return true;

                let valid = true;
                const requiredFields = Array.from(step.querySelectorAll('[required]'));

                requiredFields.forEach(field => {
                    if (field.disabled) return;

                    const value = typeof field.value === 'string' ? field.value.trim() : '';
                    if (!value) {
                        field.classList.add('ring-2', 'ring-red-400');
                        valid = false;
                    } else {
                        field.classList.remove('ring-2', 'ring-red-400');
                    }
                });

                if (stepIndex === 0) {
                    const hasMake = !!makeSelect?.value;
                    const hasModel = !!modelInput?.value;
                    const hasYear = !!yearInput?.value;
                    if (!hasMake || !hasModel || !hasYear) {
                        valid = false;
                        if (brandHubToggle && !hasMake) brandHubToggle.classList.add('ring-2', 'ring-red-400');
                        if (modelHubToggle && !hasModel) modelHubToggle.classList.add('ring-2', 'ring-red-400');
                        if (yearHubToggle && !hasYear) yearHubToggle.classList.add('ring-2', 'ring-red-400');
                        setTimeout(() => {
                            [brandHubToggle, modelHubToggle, yearHubToggle].forEach(el => el?.classList.remove('ring-2', 'ring-red-400'));
                        }, 800);
                    }
                }
                if (stepIndex === 1) { // Technical Precision Step
                    const hasGcc = !!gccInput?.value;
                    const hasBody = !!bodyInput?.value;
                    const hasEngine = !!engineInput?.value;
                    const hasMileage = !!mileageInput?.value;
                    if (!hasGcc || !hasBody || !hasEngine || !hasMileage) {
                        valid = false;
                        BazarToast.warn('Please complete all vehicle specifications before continuing.');
                    }
                }

                if (stepIndex === 2) {
                    const hasDate  = !!document.getElementById('inspectionDateVal')?.value;
                    const hasTime  = !!document.getElementById('inspectionTimeVal')?.value;
                    const hasName  = !!wizard.querySelector('[name="name"]')?.value;
                    const hasPhone = !!wizard.querySelector('[name="phone"]')?.value;

                    if (!hasName || !hasPhone) {
                        valid = false;
                        BazarToast.warn('Please enter your name and phone number.');
                    } else if (!hasDate || !hasTime) {
                        valid = false;
                        BazarToast.warn('Please select an appointment date and time.');
                    }

                }

                return valid;
            }

            function updateUI() {
                steps.forEach((step, i) => {
                    step.classList.toggle('hidden', i !== currentIdx);
                });

                btnBacks.forEach(btn => btn.classList.toggle('hidden', currentIdx === 0));
                
                // Submit button only on the last step (index 2)
                if (btnSubmit) btnSubmit.classList.toggle('hidden', currentIdx !== 2);

                // Lazy-init Leaflet when Step 3 (index 2) becomes visible
                if (currentIdx === 2 && !window._homeLeafletMap) {
                    setTimeout(() => initHomeLeaflet(), 50);
                } else if (currentIdx === 2 && window._homeLeafletMap) {
                    setTimeout(() => window._homeLeafletMap.invalidateSize(), 100);
                }

                // Wizard title word coloring â€” highlight active step word in orange
                const w1 = document.getElementById('wizard-title-w1');
                const w2 = document.getElementById('wizard-title-w2');
                const w3 = document.getElementById('wizard-title-w3');
                if (w1 && w2 && w3) {
                    const orange = 'text-[#ff6900]';
                    const muted  = 'text-slate-300';
                    [w1, w2, w3].forEach(w => { w.classList.remove(orange, muted); });
                    if (currentIdx === 0) { w1.classList.add(orange); w2.classList.add(muted); w3.classList.add(muted); }
                    if (currentIdx === 1) { w1.classList.add(muted);  w2.classList.add(orange); w3.classList.add(muted); }
                    if (currentIdx === 2) { w1.classList.add(muted);  w2.classList.add(muted);  w3.classList.add(orange); }
                }
            }


            function setInspectionType(type) {
                const input = document.getElementById('inspectionTypeInput');
                const btnBranch = document.getElementById('btnTabBranch');
                const btnHome = document.getElementById('btnTabHome');
                const mapSearch = document.getElementById('mapSearchContainer');
                const mapBranch = document.getElementById('mapBranchInfo');

                input.value = type;
                if (type === 'branch') {
                    btnBranch.classList.remove('bg-slate-50', 'text-slate-400', 'border-transparent');
                    btnBranch.classList.add('bg-white', 'text-slate-900', 'border-[#FF6900]');
                    btnHome.classList.remove('bg-white', 'text-slate-900', 'border-[#FF6900]');
                    btnHome.classList.add('bg-slate-50', 'text-slate-400', 'border-transparent');
                    mapSearch.classList.add('hidden');
                    mapBranch.classList.remove('hidden');
                } else {
                    btnHome.classList.remove('bg-slate-50', 'text-slate-400', 'border-transparent');
                    btnHome.classList.add('bg-white', 'text-[#FF6900]', 'border-[#FF6900]');
                    btnBranch.classList.remove('bg-white', 'text-[#FF6900]', 'border-[#FF6900]');
                    btnBranch.classList.add('bg-slate-50', 'text-slate-400', 'border-transparent');
                    mapSearch.classList.remove('hidden');
                    mapBranch.classList.add('hidden');
                    // Fix Leaflet tile rendering â€” invalidateSize after reveal
                    if (window._homeLeafletMap) {
                        setTimeout(() => {
                            window._homeLeafletMap.invalidateSize();
                        }, 150);
                    }
                }
            }
            window.setInspectionType = setInspectionType;

            if (brandHubToggle) {
                brandHubToggle.addEventListener('click', () => toggleBrandHub());
            }

            if (closeBrandHub) {
                closeBrandHub.addEventListener('click', () => toggleBrandHub(false));
            }

            if (resetBrandHub) {
                resetBrandHub.addEventListener('click', () => {
                    clearSelectedBrand();
                    toggleBrandHub(true);
                });
            }

            if (dateHubToggle) dateHubToggle.addEventListener('click', () => toggleDateHub());
            if (timeHubToggle) timeHubToggle.addEventListener('click', () => toggleTimeHub());

            datePicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-date-value');
                    dateInput.value = val;
                    dateHubLabel.textContent = val;
                    dateHubLabel.classList.remove('text-slate-400');
                    dateHubLabel.classList.add('text-slate-900');
                    
                    datePicks.forEach(b => b.classList.remove('bg-orange-50', 'border-orange-100'));
                    btn.classList.add('bg-orange-50', 'border-orange-100');
                    
                    toggleDateHub(false);
                });
            });

            timePicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-time-value');
                    timeInput.value = val;
                    timeHubLabel.textContent = val;
                    timeHubLabel.classList.remove('text-slate-400');
                    timeHubLabel.classList.add('text-slate-900');

                    timePicks.forEach(b => b.classList.remove('bg-orange-50', 'border-orange-100'));
                    btn.classList.add('bg-orange-50', 'border-orange-100');

                    toggleTimeHub(false);
                });
            });

            if (yearHubToggle) yearHubToggle.addEventListener('click', () => toggleYearHub());

            yearPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-year-value');
                    yearInput.value = val;
                    yearHubLabel.textContent = val;
                    yearHubLabel.classList.remove('text-slate-400');
                    yearHubLabel.classList.add('text-slate-900');

                    yearPicks.forEach(b => {
                        b.classList.remove('bg-orange-50', 'border-orange-100', 'text-slate-900');
                        b.classList.add('text-slate-700');
                    });
                    btn.classList.add('bg-orange-50', 'border-orange-100', 'text-slate-900');
                    btn.classList.remove('text-slate-700');

                    toggleYearHub(false);
                });
            });

            if (gccHubToggle) gccHubToggle.addEventListener('click', () => toggleGccHub());
            gccPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-gcc-value');
                    const label = btn.getAttribute('data-gcc-label');
                    gccInput.value = val;
                    gccHubLabel.textContent = label;
                    gccHubLabel.classList.add('text-slate-900');
                    gccPicks.forEach(b => b.classList.remove('btn-active-orange'));
                    btn.classList.add('btn-active-orange');
                    toggleGccHub(false);
                });
            });

            if (bodyHubToggle) bodyHubToggle.addEventListener('click', () => toggleBodyHub());
            bodyPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-body-value');
                    bodyInput.value = val;
                    bodyHubLabel.textContent = val;
                    bodyHubLabel.classList.add('text-slate-900');
                    toggleBodyHub(false);
                });
            });

            if (engineHubToggle) engineHubToggle.addEventListener('click', () => toggleEngineHub());
            enginePicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-engine-value');
                    engineInput.value = val;
                    engineHubLabel.textContent = val;
                    engineHubLabel.classList.add('text-slate-900');
                    toggleEngineHub(false);
                });
            });

            if (mileageHubToggle) mileageHubToggle.addEventListener('click', () => toggleMileageHub());
            mileagePicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-mileage-value');
                    mileageInput.value = val;
                    mileageHubLabel.textContent = val + ' KM';
                    mileageHubLabel.classList.add('text-slate-900');
                    toggleMileageHub(false);
                });
            });

            trimPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-trim-value');
                    trimInput.value = val;
                    trimPicks.forEach(b => b.classList.remove('btn-active-orange'));
                    btn.classList.add('btn-active-orange');
                });
            });

            paintPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-paint-value');
                    paintInput.value = val;
                    paintPicks.forEach(b => b.classList.remove('btn-active-orange'));
                    btn.classList.add('btn-active-orange');
                });
            });

            conditionPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.getAttribute('data-condition-value');
                    conditionInput.value = val;
                    conditionPicks.forEach(b => {
                        b.classList.remove('btn-active-orange', 'border-[#FF6900]', 'text-slate-900');
                        b.classList.add('text-slate-400', 'border-slate-100');
                    });
                    btn.classList.add('btn-active-orange', 'border-[#FF6900]', 'text-slate-900');
                    btn.classList.remove('text-slate-400', 'border-slate-100');
                });
            });

            if (modelHubToggle) {
                modelHubToggle.addEventListener('click', () => toggleModelHub());
            }

            if (closeModelHub) {
                closeModelHub.addEventListener('click', () => toggleModelHub(false));
            }

            if (modelHubSearch) {
                modelHubSearch.addEventListener('input', (e) => {
                    const q = e.target.value.toLowerCase().trim();
                    const modelBtns = Array.from(modelListContainer?.querySelectorAll('[data-model-value]') || []);
                    modelBtns.forEach(btn => {
                        const name = (btn.getAttribute('data-model-value') || '').toLowerCase();
                        btn.style.display = name.includes(q) ? 'flex' : 'none';
                    });
                });
            }

            if (brandHubSearch) {
                brandHubSearch.addEventListener('input', (e) => {
                    const q = e.target.value.toLowerCase().trim();
                    brandHubOptions.forEach(opt => {
                        const name = (opt.getAttribute('data-brand-hub-value') || '').toLowerCase();
                        opt.style.display = name.includes(q) ? 'flex' : 'none';
                    });
                });
            }

            brandHubOptions.forEach(btn => {
                btn.addEventListener('click', () => {
                    const value = btn.getAttribute('data-brand-hub-value') || '';
                    const logo = btn.getAttribute('data-brand-hub-logo') || '';
                    const models = parseModels(btn.getAttribute('data-brand-models'));
                    setSelectedBrand(value, logo, false, models);
                    toggleBrandHub(false);
                });
            });

            if (btnNexts) {
                btnNexts.forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (validateStep(currentIdx)) {
                            currentIdx++;
                            updateUI();
                        }
                    });
                });
            }

            popularBrandPicks.forEach(btn => {
                btn.addEventListener('click', () => {
                    const value = btn.getAttribute('data-brand-pick') || '';
                    const logo = btn.querySelector('img')?.src || '';
                    const models = parseModels(btn.getAttribute('data-brand-models'));
                    setSelectedBrand(value, logo, false, models);
                    if (brandHubDrawer) {
                        brandHubDrawer.classList.add('hidden');
                    }
                });
            });

            document.addEventListener('click', (e) => {
                if (brandHubDrawer && brandHubToggle && !brandHubDrawer.contains(e.target) && !brandHubToggle.contains(e.target)) {
                    toggleBrandHub(false);
                }
                if (modelHubDrawer && modelHubToggle && !modelHubDrawer.contains(e.target) && !modelHubToggle.contains(e.target)) {
                    toggleModelHub(false);
                }
                if (gccHubDrawer && gccHubToggle && !gccHubDrawer.contains(e.target) && !gccHubToggle.contains(e.target)) {
                    toggleGccHub(false);
                }
                if (yearHubDrawer && yearHubToggle && !yearHubDrawer.contains(e.target) && !yearHubToggle.contains(e.target)) {
                    toggleYearHub(false);
                }
                if (dateHubDrawer && dateHubToggle && !dateHubDrawer.contains(e.target) && !dateHubToggle.contains(e.target)) {
                    toggleDateHub(false);
                }
                if (timeHubDrawer && timeHubToggle && !timeHubDrawer.contains(e.target) && !timeHubToggle.contains(e.target)) {
                    toggleTimeHub(false);
                }
            });

            // Multi-step navigation logic is handled via the btnNexts and btnBacks loops above.

            btnBacks.forEach(btn => {
                btn.addEventListener('click', () => {
                    currentIdx = Math.max(0, currentIdx - 1);
                    updateUI();
                });
            });

            wizard.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (!validateStep(2)) return;

                if (!btnSubmit) return;
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="animate-pulse">Syncing Node...</span>';

                try {
                    const formData = new FormData(wizard);
                    
                    // 1. Resolve Mileage String to Integer
                    const rawMileage = formData.get('mileage');
                    const mileageMap = {
                        '0 - 20k': 20000,
                        '20k - 50k': 50000,
                        '50k - 100k': 100000,
                        '100k - 150k': 150000,
                        '150k - 200k': 200000,
                        'Over 200k': 250000,
                        'Unknown': 0
                    };
                    if (mileageMap[rawMileage] !== undefined) {
                        formData.set('mileage', mileageMap[rawMileage]);
                    } else if (rawMileage) {
                        formData.set('mileage', parseInt(rawMileage.replace(/[^0-9]/g, '')) || 0);
                    } else {
                        formData.set('mileage', 0);
                    }

                    // 2. Parse Appointment into Date & Time
                    const rawAppointment = formData.get('inspection_appointment'); // e.g. "02 Apr 2026 - 10:30 PM"
                    if (rawAppointment && rawAppointment.includes('-')) {
                        const [dateStr, timeStr] = rawAppointment.split(' - ').map(s => s.trim());
                        // Simple parsing for Laravel:
                        const dateObj = new Date(dateStr);
                        if (!isNaN(dateObj.getTime())) {
                            const y = dateObj.getFullYear();
                            const m = String(dateObj.getMonth() + 1).padStart(2, '0');
                            const d = String(dateObj.getDate()).padStart(2, '0');
                            formData.set('inspection_date', `${y}-${m}-${d}`);
                            formData.set('inspection_time', timeStr);
                        }
                    } else if (rawAppointment) {
                        // Fallback if split fails
                        formData.set('inspection_date', new Date().toISOString().split('T')[0]);
                        formData.set('inspection_time', rawAppointment);
                    } else {
                        // Strict fallback
                        formData.set('inspection_date', new Date().toISOString().split('T')[0]);
                        formData.set('inspection_time', 'ASAP');
                    }

                    const res = await fetch(wizard.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });

                    if (!res.ok) {
                        const errorData = await res.json().catch(() => ({}));
                        console.error('Server Matrix Rejection:', errorData);
                        
                        // Human-readable error list
                        let errorMsg = errorData.message || 'Submission failed';
                        if (errorData.errors) {
                            const details = Object.entries(errorData.errors)
                                .map(([field, msgs]) => `${field}: ${msgs.join(', ')}`)
                                .join('\n');
                            errorMsg += '\n\n' + details;
                        }
                        throw new Error(errorMsg);
                    }

                    const data = await res.json();
                    const container = wizard.closest('.sell-wizard-card') || wizard.parentElement;
                    if (container) {
                        container.innerHTML = `
                            <div class="py-12 text-center animate-in zoom-in duration-500">
                                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/10">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h3 class="text-2xl font-black text-slate-800 mb-2">Lead Matrix Captured</h3>
                                <p class="text-slate-500 text-sm">Your data was pushed to the Elite CRM Segment. An operator will respond shortly.</p>
                                <button onclick="window.location.reload()" class="mt-8 text-[0.65rem] font-bold uppercase tracking-widest text-[#ff6900] border-b-2 border-orange-100 pb-1">Submit New Lead</button>
                            </div>
                        `;
                    }
                } catch (err) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = 'Retry Syncing';
                    BazarToast.error('Submission failed: ' + err.message);
                }
            });

            const currentBrand = makeSelect?.value || '';
            const currentModel = modelInput?.value || '';

            if (currentBrand) {
                const existingBrandOption = brandHubOptions.find(btn => normalizeMake(btn.getAttribute('data-brand-hub-value') || '') === normalizeMake(currentBrand));
                const logo = existingBrandOption?.getAttribute('data-brand-hub-logo') || '';
                const models = existingBrandOption ? parseModels(existingBrandOption.getAttribute('data-brand-models')) : getModelList(currentBrand);
                setSelectedBrand(currentBrand, logo, true, models);
                if (currentModel) {
                    setSelectedModel(currentModel);
                }
            } else {
                populateModels([]);
            }

            updateUI();

            window.detectLocation = function() {
                const searchInput = document.getElementById('homeAddressSearch');
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser');
                    return;
                }
                
                searchInput.value = 'Detecting location...';
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const { latitude, longitude } = pos.coords;
                        // Move map marker to detected location
                        if (window._homeLeafletMap && window._homeLeafletMarker) {
                            window._homeLeafletMap.setView([latitude, longitude], 16);
                            window._homeLeafletMarker.setLatLng([latitude, longitude]);
                            reverseGeocodeHome(latitude, longitude);
                        } else {
                            reverseGeocodeHome(latitude, longitude);
                        }
                    },
                    (err) => {
                        searchInput.value = '';
                        BazarToast.error('Could not detect your location. Please type manually.');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            };

            // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
            //  HOME LEAFLET MAP â€” Full Feature Init (Lazy)
            //  Called when Step 3 (index 2) first becomes visible.
            // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
            const mapProvider = window.mapProvider || 'google';
            const branchLat = {{ \App\Models\SystemSetting::get('branch_lat', '25.1384') }};
            const branchLng = {{ \App\Models\SystemSetting::get('branch_lng', '55.2285') }};
            const branchCoords = [branchLat, branchLng];

            window._homeLeafletMap    = null;
            window._homeLeafletMarker = null;

            function initHomeLeaflet() {
                if (window._homeLeafletMap) {
                    // Already initialized â€” just refresh size
                    setTimeout(() => window._homeLeafletMap.invalidateSize(), 80);
                    return;
                }

                // â”€â”€ Google Maps path â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                if (mapProvider === 'google' && window.google && google.maps && google.maps.places) {
                    const ai = document.getElementById('homeAddressSearch');
                    if (ai) new google.maps.places.Autocomplete(ai, {
                        componentRestrictions: { country: 'ae' },
                        fields: ['address_components', 'geometry', 'name'],
                    });
                    return;
                }

                // â”€â”€ OSM / Leaflet path â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                if (!window.L) { console.warn('Leaflet not loaded'); return; }
                const mapEl = document.getElementById('leafletHomeMap');
                if (!mapEl) { console.warn('leafletHomeMap element not found'); return; }

                const map = L.map('leafletHomeMap', {
                    zoomControl: true,
                    scrollWheelZoom: true
                }).setView(branchCoords, 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(map);

                // Custom orange marker icon
                const orangeIcon = L.divIcon({
                    html: `<div style="width:36px;height:36px;background:#FF6900;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 4px 15px rgba(255,105,0,0.4);"></div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 36],
                    className: ''
                });

                const marker = L.marker(branchCoords, {
                    draggable: true,
                    icon: orangeIcon
                }).addTo(map);

                // Store globally for detectLocation() access
                window._homeLeafletMap    = map;
                window._homeLeafletMarker = marker;

                // Force correct tile rendering after CSS paint
                setTimeout(() => map.invalidateSize(), 150);

                // â”€â”€ Manual click to place marker â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                map.on('click', (e) => {
                    marker.setLatLng(e.latlng);
                    reverseGeocodeHome(e.latlng.lat, e.latlng.lng);
                    BazarToast.info('Location pinned. You can drag to adjust.');
                });

                // â”€â”€ Drag marker to update address â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    reverseGeocodeHome(pos.lat, pos.lng);
                });

                // â”€â”€ Address Search (Nominatim) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                const searchInput = document.getElementById('homeAddressSearch');
                if (searchInput) {
                    // Create results dropdown â€” must be relative to the input wrapper
                    const existingDrop = document.getElementById('homeOsmResults');
                    if (existingDrop) existingDrop.remove();

                    const osmResults = document.createElement('div');
                    osmResults.id = 'homeOsmResults';
                    osmResults.style.cssText = 'position:absolute;top:100%;left:0;right:0;z-index:1200;background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 20px 40px rgba(0,0,0,0.12);margin-top:4px;display:none;overflow:hidden;';

                    // Append to the flex div that wraps the input
                    const inputWrapper = searchInput.closest('.flex-1.relative');
                    if (inputWrapper) {
                        inputWrapper.style.position = 'relative';
                        inputWrapper.appendChild(osmResults);
                    } else {
                        searchInput.parentElement.style.position = 'relative';
                        searchInput.parentElement.appendChild(osmResults);
                    }

                    let searchTimer;
                    searchInput.addEventListener('input', () => {
                        clearTimeout(searchTimer);
                        const q = searchInput.value.trim();
                        if (q.length < 3) { osmResults.style.display = 'none'; return; }
                        searchTimer = setTimeout(() => {
                            fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=6&q=${encodeURIComponent(q)}`, {
                                headers: { 'Accept': 'application/json', 'User-Agent': 'MotorBazar/1.0' }
                            })
                            .then(r => r.json())
                            .then(items => renderHomeOsmResults(items, map, marker, osmResults, searchInput))
                            .catch(() => osmResults.style.display = 'none');
                        }, 500);
                    });

                    document.addEventListener('click', (e) => {
                        if (!osmResults.contains(e.target) && e.target !== searchInput) {
                            osmResults.style.display = 'none';
                        }
                    });
                }
            }


            function renderHomeOsmResults(data, map, marker, resultsEl, inputEl) {
                if (!data || data.length === 0) {
                    resultsEl.style.display = 'none';
                    return;
                }
                resultsEl.innerHTML = '';
                data.forEach(item => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.style.cssText = 'width:100%;padding:10px 16px;text-align:left;background:none;border:none;border-bottom:1px solid #f1f5f9;cursor:pointer;display:flex;align-items:center;gap:10px;transition:background 0.15s;';
                    btn.onmouseover = () => btn.style.background = '#fef3ec';
                    btn.onmouseout  = () => btn.style.background = 'none';
                    btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:#FF6900;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0z"/></svg>
                        <span style="font-size:0.72rem;font-weight:600;color:#334155;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${item.display_name}</span>
                    `;
                    btn.onclick = () => {
                        const lat = parseFloat(item.lat);
                        const lon = parseFloat(item.lon);
                        map.setView([lat, lon], 16);
                        marker.setLatLng([lat, lon]);
                        inputEl.value = item.display_name;
                        resultsEl.style.display = 'none';
                    };
                    resultsEl.appendChild(btn);
                });
                resultsEl.style.display = 'block';
            }

            function reverseGeocodeHome(lat, lng) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                    headers: { 'Accept': 'application/json', 'User-Agent': 'MotorBazar-App' }
                })
                .then(r => r.json())
                .then(data => {
                    const addrInput = document.getElementById('homeAddressSearch');
                    if (addrInput) addrInput.value = data.display_name || (lat.toFixed(4) + ', ' + lng.toFixed(4));
                });
            }

            // setTimeout inside DOMContentLoaded
            setTimeout(() => {
                const iframe = document.querySelector('iframe[title="MG Roadster 1968 Virtual Tour"]');
                if (iframe) iframe.focus();
            }, 3000);

            initCountdowns();
        });

        // REAL-TIME AUCTION COUNTDOWN ENGINE (defined at script scope)
        function initCountdowns() {
            const updateCountdowns = () => {
                document.querySelectorAll('.active-countdown').forEach(el => {
                    const endAt = new Date(el.getAttribute('data-end-at')).getTime();
                    const now = new Date().getTime();
                    const diff = endAt - now;

                    const timerSpan = el.querySelector('.timer-values');
                    if (!timerSpan) return;

                    if (diff <= 0) {
                        timerSpan.innerText = 'EXPIRED';
                        el.classList.replace('bg-emerald-500', 'bg-slate-500');
                        return;
                    }

                    const h = Math.floor(diff / (1000 * 60 * 60));
                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                    timerSpan.innerText = (h > 0 ? h + 'h ' : '') + 
                                          String(m).padStart(2, '0') + 'm ' + 
                                          String(s).padStart(2, '0') + 's';
                });
            };
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        }

        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        // CUSTOM DATE PICKER ENGINE
        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        (function() {
            const toggle     = document.getElementById('datePickerToggle');
            const drawer     = document.getElementById('datePickerDrawer');
            const label      = document.getElementById('datePickerLabel');
            const hiddenVal  = document.getElementById('inspectionDateVal');
            const grid       = document.getElementById('calDaysGrid');
            const monthLabel = document.getElementById('calMonthYear');
            const btnPrev    = document.getElementById('calPrev');
            const btnNext    = document.getElementById('calNext');
            if (!toggle || !drawer) return;

            const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const today  = new Date(); today.setHours(0,0,0,0);
            let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
            let selected = null;

            function renderCalendar() {
                const yr  = viewDate.getFullYear();
                const mo  = viewDate.getMonth();
                monthLabel.textContent = MONTHS[mo] + ' ' + yr;
                grid.innerHTML = '';

                const firstDay = new Date(yr, mo, 1).getDay();
                const daysInMo = new Date(yr, mo + 1, 0).getDate();

                // Blank cells for offset
                for (let i = 0; i < firstDay; i++) {
                    const blank = document.createElement('div');
                    grid.appendChild(blank);
                }

                for (let d = 1; d <= daysInMo; d++) {
                    const date = new Date(yr, mo, d);
                    const btn  = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = d;

                    const isPast    = date < today;
                    const isToday   = date.getTime() === today.getTime();
                    const isSel     = selected && date.getTime() === selected.getTime();

                    btn.className = 'w-full aspect-square flex items-center justify-center rounded-lg text-[0.65rem] font-bold transition-all ';
                    if (isPast) {
                        btn.className += 'text-slate-200 cursor-not-allowed';
                        btn.disabled = true;
                    } else if (isSel) {
                        btn.className += 'bg-[#FF6900] text-white shadow-md shadow-orange-200 font-black';
                    } else if (isToday) {
                        btn.className += 'ring-2 ring-[#FF6900]/40 text-[#FF6900] font-black hover:bg-orange-50';
                    } else {
                        btn.className += 'text-slate-600 hover:bg-orange-50 hover:text-[#FF6900]';
                    }

                    if (!isPast) {
                        btn.addEventListener('click', () => {
                            selected = date;
                            const iso = `${yr}-${String(mo+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                            hiddenVal.value = iso;
                            label.textContent = date.toLocaleDateString('en-US', { weekday:'short', month:'short', day:'numeric' });
                            label.classList.replace('text-slate-300','text-slate-800');
                            // Update toggle border to orange
                            toggle.classList.add('border-[#FF6900]');
                            toggle.classList.remove('border-slate-100');
                            drawer.classList.add('hidden');
                            renderCalendar();
                        });
                    }
                    grid.appendChild(btn);
                }
            }

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                // Close time picker
                document.getElementById('timePickerDrawer')?.classList.add('hidden');
                renderCalendar();
            });

            btnPrev?.addEventListener('click', (e) => {
                e.stopPropagation();
                viewDate.setMonth(viewDate.getMonth() - 1);
                renderCalendar();
            });

            btnNext?.addEventListener('click', (e) => {
                e.stopPropagation();
                viewDate.setMonth(viewDate.getMonth() + 1);
                renderCalendar();
            });

            document.addEventListener('click', (e) => {
                if (!document.getElementById('datePicker')?.contains(e.target)) {
                    drawer.classList.add('hidden');
                }
            });

            renderCalendar();
        })();

        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        // CUSTOM TIME DRUM PICKER ENGINE
        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        (function() {
            const toggle    = document.getElementById('timePickerToggle');
            const drawer    = document.getElementById('timePickerDrawer');
            const label     = document.getElementById('timePickerLabel');
            const hiddenVal = document.getElementById('inspectionTimeVal');
            if (!toggle || !drawer) return;

            const HOURS   = [9,10,11,12,1,2,3,4,5];
            const MINUTES = ['00','15','30','45'];
            let hrIdx  = 0;   // 9 AM
            let minIdx = 0;   // :00
            let isPM   = false;

            const hrPrev    = document.getElementById('hrPrev');
            const hrCur     = document.getElementById('hrCurrent');
            const hrNxt     = document.getElementById('hrNext');
            const minPrev   = document.getElementById('minPrev');
            const minCur    = document.getElementById('minCurrent');
            const minNxt    = document.getElementById('minNext');
            const amBtn     = document.getElementById('amToggle');
            const pmBtn     = document.getElementById('pmToggle');
            const confirmBtn= document.getElementById('timeConfirm');

            function renderDrums() {
                const hPrev = HOURS[(hrIdx - 1 + HOURS.length) % HOURS.length];
                const hCur  = HOURS[hrIdx];
                const hNxt  = HOURS[(hrIdx + 1) % HOURS.length];
                if(hrPrev) hrPrev.textContent = String(hPrev).padStart(2,'0');
                if(hrCur)  hrCur.textContent  = String(hCur).padStart(2,'0');
                if(hrNxt)  hrNxt.textContent  = String(hNxt).padStart(2,'0');

                const mPrev = MINUTES[(minIdx - 1 + MINUTES.length) % MINUTES.length];
                const mCur  = MINUTES[minIdx];
                const mNxt  = MINUTES[(minIdx + 1) % MINUTES.length];
                if(minPrev) minPrev.textContent = mPrev;
                if(minCur)  minCur.textContent  = mCur;
                if(minNxt)  minNxt.textContent  = mNxt;
            }

            function buildTimeStr() {
                const h   = HOURS[hrIdx];
                const m   = MINUTES[minIdx];
                const per = isPM ? 'PM' : 'AM';
                return String(h).padStart(2,'0') + ':' + m + ' ' + per;
            }

            function setAMPM(pm) {
                isPM = pm;
                amBtn.className = !pm
                    ? 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all'
                    : 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-slate-100 text-slate-400 transition-all';
                pmBtn.className = pm
                    ? 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all'
                    : 'px-2.5 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-wider bg-slate-100 text-slate-400 transition-all';
            }

            // Arrow buttons
            document.getElementById('hrUp')?.addEventListener('click', (e) => {
                e.stopPropagation();
                hrIdx = (hrIdx - 1 + HOURS.length) % HOURS.length;
                renderDrums();
            });
            document.getElementById('hrDown')?.addEventListener('click', (e) => {
                e.stopPropagation();
                hrIdx = (hrIdx + 1) % HOURS.length;
                renderDrums();
            });
            document.getElementById('minUp')?.addEventListener('click', (e) => {
                e.stopPropagation();
                minIdx = (minIdx - 1 + MINUTES.length) % MINUTES.length;
                renderDrums();
            });
            document.getElementById('minDown')?.addEventListener('click', (e) => {
                e.stopPropagation();
                minIdx = (minIdx + 1) % MINUTES.length;
                renderDrums();
            });

            // AM/PM toggle
            amBtn?.addEventListener('click', (e) => { e.stopPropagation(); setAMPM(false); });
            pmBtn?.addEventListener('click', (e) => { e.stopPropagation(); setAMPM(true); });

            // Confirm
            confirmBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                const timeStr = buildTimeStr();
                hiddenVal.value = timeStr;
                label.textContent = timeStr;
                label.classList.replace('text-slate-300','text-slate-800');
                toggle.classList.add('border-[#FF6900]');
                toggle.classList.remove('border-slate-100');
                drawer.classList.add('hidden');
            });

            // Toggle open/close
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                document.getElementById('datePickerDrawer')?.classList.add('hidden');
                renderDrums();
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!document.getElementById('timePicker')?.contains(e.target)) {
                    drawer.classList.add('hidden');
                }
            });

            renderDrums();
        })();

    </script>
@endsection


