@extends('layouts.app')

@section('title', 'Motor Bazar - Premium Car Marketplace')
@section('meta_description', 'أكبر منصة لمزادات السيارات في الخليج. اشترِ سيارتك الآن بأفضل الأسعار مع تقرير فحص فني دقيق لكل سيارة.')
@section('meta_keywords', 'مزادات السيارات, سيارات مستعملة, حراج السيارات, شراء سيارة, تويوتا, نيسان, الخليج, دبي')

@section('head')
{{-- Anti-FOUC: hide page until styled. Runs synchronously (no defer/async) --}}
@php
        $page = $page ?? null;
        $heroContent = data_get($page?->content, 'hero', []);
        $homepageHeroImage = data_get($heroContent, 'background_image') ?: ($page ? $page->hero_image : null) ?: '/images/cars/mclaren.png';
        $fallbackCarImages = [
            '/images/cars/mclaren.png',
            '/images/cars/elite-navy-car.png',
            '/images/cars/car-silver.png',
            '/images/cars/home-car.png',
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
        $heroCarMirror = (bool) data_get($heroContent, 'car_mirror', false);
        $heroBackgroundImage = data_get($heroContent, 'background_image') ?: ($page ? $page->hero_image : null) ?: '/images/hero-bg.png';
        $heroCarImage = ($page ? $page->hero_image : null) ?: '/images/cars/mclaren.png';
        $heroBackgroundRgb = sscanf(ltrim($heroBackgroundColor, '#'), "%02x%02x%02x");
        $heroBackgroundStyle = $heroBackgroundOverlayEnabled
            ? "background-image: linear-gradient(" . ($heroBackgroundDirection === 'vertical' ? 'to bottom' : 'to right') . ", rgba({$heroBackgroundRgb[0]}, {$heroBackgroundRgb[1]}, {$heroBackgroundRgb[2]}, {$heroBackgroundOpacity}), rgba({$heroBackgroundRgb[0]}, {$heroBackgroundRgb[1]}, {$heroBackgroundRgb[2]}, " . max(0.14, $heroBackgroundOpacity * 0.8) . ")), url('{$heroBackgroundImage}'); background-size: cover; background-position: center;"
            : "background-image: url('{$heroBackgroundImage}'); background-size: cover; background-position: center; background-color: {$heroBackgroundColor};";
    @endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
@endsection

@php
    $page = $page ?? null;
    $heroContent = data_get($page?->content, 'hero', []);
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
        $heroMode = data_get($page?->content, 'hero.background_mode', 'image');
        $heroBg = data_get($page?->content, 'hero.background_image', '/images/hero-bg.png');
        $heroColor = data_get($page?->content, 'hero.background_color', '#e7e7e7');
        $heroColor2 = data_get($page?->content, 'hero.background_color_secondary', '#1a1d26');
        $heroAngle = data_get($page?->content, 'hero.background_gradient_angle', 135);
        $heroCustomCss = data_get($page?->content, 'hero.custom_css', '');
        $heroOpacity = (float) data_get($page?->content, 'hero.background_overlay_opacity', 0.72);
        $heroOverlayEnabled = data_get($page?->content, 'hero.background_overlay_enabled', true);
        $heroDirection = data_get($page?->content, 'hero.background_overlay_direction', 'horizontal');
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
                    <div class="inline-flex items-center gap-3 px-6 py-2 rounded-full bg-[#031629] text-white text-[0.7rem] font-black uppercase tracking-[0.3em] shadow-2xl">
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
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=1" alt="Motor Bazar User" loading="lazy">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=2" alt="Motor Bazar User" loading="lazy">
                            <img class="inline-block h-10 w-10 rounded-full ring-2 ring-[#e7e7e7]" src="https://i.pravatar.cc/100?u=3" alt="Motor Bazar User" loading="lazy">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full ring-2 ring-[#e7e7e7] bg-[#031629] text-white text-[0.6rem] font-black">+1k</div>
                        </div>
                        <div class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest leading-none">
                            Trusted by thousands <br/> of Dubai sellers
                        </div>
                    </div>
                </div>

                {{-- Right Side: The Elite Showroom Asset --}}
                <div class="w-full lg:w-[55%] relative transform hover:scale-[1.05] transition-all duration-1000">
                    <img src="{{ $page?->hero_image ?: '/images/cars/mclaren.png' }}" 
                        class="w-full h-auto object-contain filter contrast-[1.18] brightness-[1.05] saturate-[1.25] drop-shadow-[0_90px_110px_rgba(3,22,41,0.18)]" 
                        alt="Elite Selection"
                        style="image-rendering: -webkit-optimize-contrast; transform: scale({{ $heroCarScale }}) {{ $heroCarMirror ? 'scaleX(-1)' : '' }}; transform-origin: center bottom;">
                </div>

            </div>
        </div>
    </section>

    @php
        $hideSellWizard = auth()->check() && auth()->user()->hasRole('dealer');
    @endphp

    @unless($hideSellWizard)
    {{-- Sell Car Wizard: Independent Glass Card --}}
    <section class="relative z-40 -mt-[19rem] px-6 lg:px-12 pb-16">
        <div class="mx-auto max-w-[1440px]">
            <div class="sell-wizard-card relative z-10 rounded-[1rem] border border-white/70 bg-white/65 backdrop-blur-2xl shadow-[0_40px_120px_-45px_rgba(15,23,42,0.35)] p-6 lg:p-8 overflow-visible">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="text-bazar-500 font-black uppercase tracking-[0.35em] text-[0.62rem] mb-3 block">{{ $leadArchitecture['header'] }}</span>
                        @php
                            $wizardW1 = $leadArchitecture['step1'];
                            $wizardW2 = $leadArchitecture['step2'];
                            $wizardW3 = $leadArchitecture['step3'];
                        @endphp
                        <h2 class="text-3xl lg:text-4xl font-black tracking-tight flex items-center flex-wrap gap-x-3 gap-y-1 text-slate-900">
                            {!! $leadArchitecture['wizard_title'] !!}
                        </h2>
                        <div class="flex items-center flex-wrap gap-x-3 gap-y-1 mt-4">
                            <span id="wizard-title-w1" class="text-lg font-black transition-colors duration-500 text-[#ff6900]">{{ $wizardW1 }}</span>
                            <span class="text-slate-300 font-light text-2xl leading-none">&bull;</span>
                            <span id="wizard-title-w2" class="text-lg font-black transition-colors duration-500 text-slate-300">{{ $wizardW2 }}</span>
                            <span class="text-slate-300 font-light text-2xl leading-none">&bull;</span>
                            <span id="wizard-title-w3" class="text-lg font-black transition-colors duration-500 text-slate-300">{{ $wizardW3 }}</span>
                        </div>
                    </div>

                    <p class="text-sm text-slate-500 max-w-2xl">{{ data_get($page?->content, 'lead_form.step1.subtitle', 'The model list updates automatically from the catalog.') }}</p>
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
                                
                            // 2. "Quick Pick" Cards (8 brands): Pulled from Lead Architecture Settings
                            $brandCardBrands = $leadArchitecture['featured_brands'] ?? [];
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

                                <div id="brandHubDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[9999] rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_45px_130px_-25px_rgba(15,23,42,0.35)] p-5 animate-in fade-in zoom-in-95 duration-500">
                                    <div class="flex items-center gap-4 pb-3 border-b border-slate-100">
                                        <div class="relative flex-1">
                                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400/70"></i>
                                            <input type="text" id="brandHubSearch" placeholder="Search brand..." class="w-full h-12 pl-14 pr-4 rounded-xl bg-slate-50 border border-slate-200 font-bold text-sm text-slate-900 focus:bg-white focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <button type="button" id="resetBrandHub" class="text-[0.6rem] font-black uppercase tracking-[0.2em] text-[#ff6900] hover:text-orange-600 transition-colors">Reset</button>
                                            <button type="button" id="closeBrandHub" class="text-[0.6rem] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-red-500 transition-colors">Close</button>
                                        </div>
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
                            <button type="button" data-action="next" class="h-16 px-12 bg-[#ff6900] text-white rounded-lg font-black uppercase tracking-widest text-sm hover:-translate-y-0.5 shadow-lg shadow-orange-500/20 transition-all group animate-pulse-orange">
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
                            <button type="button" data-action="next" class="btn-bazar-primary h-10 px-9 rounded-lg font-black uppercase tracking-widest text-[0.65rem] transition-all animate-pulse-orange">Next Stage &rarr;</button>
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
                                                <div class="w-12 h-12 rounded-md bg-[#031629] flex items-center justify-center shadow-lg">
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
                                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-[#031629]/10 blur-[2px]"></div>
                                            <i data-lucide="map-pin" class="w-12 h-12 text-[#FF6900] drop-shadow-2xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Data --}}
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
                                    <button type="submit" data-action="submit" class="btn-bazar-primary w-full h-14 rounded-lg font-black uppercase tracking-[0.25em] text-[0.75rem] transition-all shadow-2xl shadow-orange-500/30 animate-pulse-orange">{{ data_get($page?->content, 'lead_form.step3.submit_label', 'Request Free Valuation') }}</button>
                                    <button type="button" data-action="back" class="w-full text-center text-[0.6rem] font-black uppercase tracking-[0.35em] text-slate-400 hover:text-slate-900 transition-all">&larr; Adjust Specs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Trust Badges: Centered Icon + Title + Description --}}
            @php
                $trustBadges = array_slice(data_get($page?->content, 'trust_badges', [
                    ['label' => 'Guaranteed Purchase',    'icon' => 'shield-check', 'color' => '#ff4605', 'bg_color' => '#fff7ed', 'desc' => 'We guarantee every transaction is safe, verified, and backed by Motor Bazar.'],
                    ['label' => 'No Costs. No Obligation','icon' => 'wallet',       'color' => '#031629', 'bg_color' => '#f1f5f9', 'desc' => 'Free valuations with zero hidden fees. Walk away any time — no strings attached.'],
                    ['label' => 'Quick and Easy',         'icon' => 'zap',          'color' => '#ff6900', 'bg_color' => '#fff7ed', 'desc' => 'Submit your car in under 3 minutes. Our team contacts you within 24 hours.'],
                    ['label' => 'Fast and Secure',        'icon' => 'lock',         'color' => '#334155', 'bg_color' => '#f8fafc', 'desc' => 'Bank-grade encryption protects your data and payment at every step.'],
                ]), 0, 3);
                $badgesTitle = data_get($page?->content, 'trust_badges_title', 'We built our business on trust');
            @endphp

            {{-- Section Heading --}}
            <div class="mt-6 mb-8 text-center">
                <h3 class="text-2xl lg:text-3xl font-black text-[#031629] tracking-tight">{{ $badgesTitle }}</h3>
            </div>

            {{-- Badges Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 relative max-w-5xl mx-auto">
                {{-- Gradient separator lines --}}
                <div class="hidden md:block absolute top-[10%] bottom-[10%] left-1/3 w-px" style="background: linear-gradient(to bottom, transparent, #cbd5e1, transparent);"></div>
                <div class="hidden md:block absolute top-[10%] bottom-[10%] left-2/3 w-px" style="background: linear-gradient(to bottom, transparent, #cbd5e1, transparent);"></div>
                <div class="md:hidden absolute left-[10%] right-[10%] top-1/2 h-px" style="background: linear-gradient(to right, transparent, #cbd5e1, transparent);"></div>
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
                            <a href="{{ route('auctions.show', $auction) }}" class="w-12 h-12 rounded-md bg-[#031629] flex items-center justify-center text-white hover:bg-bazar-500 transition-all">
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

            {{-- Section Card Title - Premium Design --}}
            <div class="text-center mb-10">
                <div class="inline-flex flex-col items-center">
                    {{-- Icon Badge --}}
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#ff4605] to-[#ff6900] flex items-center justify-center shadow-lg shadow-orange-500/25 mb-4">
                        <i data-lucide="map-pin" class="w-7 h-7 text-white"></i>
                    </div>
                    {{-- Title with decorative lines --}}
                    <div class="flex items-center gap-4">
                        <div class="h-px w-16 bg-gradient-to-r from-transparent via-[#ff4605] to-[#ff4605]"></div>
                        <h2 class="text-lg font-black uppercase tracking-[0.25em] text-[#031629]">{{ data_get($page?->content, 'location.section_header_title', 'Find Us Section') }}</h2>
                        <div class="h-px w-16 bg-gradient-to-l from-transparent via-[#ff4605] to-[#ff4605]"></div>
                    </div>
                    {{-- Subtitle --}}
                    <p class="text-sm text-slate-500 font-medium mt-2 tracking-wide">{{ data_get($page?->content, 'location.section_header_subtitle', 'Visit our showroom and explore premium vehicles') }}</p>
                </div>
            </div>

            {{-- Section Label --}}
            <div class="flex items-center gap-4 mb-8">
                <div class="h-1 w-10 bg-[#ff4605] rounded-full"></div>
                <span class="text-[0.62rem] font-black uppercase tracking-[0.3em] text-slate-500">{{ data_get($page?->content, 'location.section_label', 'Find Us') }}</span>
            </div>

            {{-- Map Card --}}
            <div class="relative rounded-3xl overflow-hidden shadow-[0_24px_80px_-20px_rgba(3,22,41,0.18)] min-h-[420px]">

                {{-- Map Embed --}}
                <iframe
                    src="{{ data_get($page?->content, 'location.iframe_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3608.6!2d55.296249!3d25.264171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDE1JzUxLjAiTiA1NcKwMTcnNDYuNSJF!5e0!3m2!1sen!2sae!4v1680000000000!5m2!1sen!2sae') }}"
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
                        {{ data_get($page?->content, 'location.title', 'Visit Motor') }}<br><span class="text-[#ff4605]">{{ data_get($page?->content, 'location.title_accent', 'Bazar') }}</span>
                    </h2>
                    <p class="text-slate-300 text-sm font-medium mb-8">{{ data_get($page?->content, 'location.subtitle', 'Come see our full inventory in person — our team is ready to help.') }}</p>

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
                        {{ data_get($page?->content, 'location.button_label', 'Get Directions') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endunless

    {{-- Google Reviews Highlight --}}
    @php
        $reviewsConfig = $googleReviewBlock ?? [];
        $showReviews = data_get($reviewsConfig, 'enabled') && count(data_get($reviewsConfig, 'reviews', []));
        $reviews = data_get($reviewsConfig, 'reviews', []);
    @endphp
    @if($showReviews)
    <section class="py-24 px-6 lg:px-12 bg-[#031629] relative z-30 overflow-hidden">
        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at top, rgba(255,255,255,0.08), transparent 55%), radial-gradient(circle at bottom, rgba(255,105,0,0.08), transparent 45%);"></div>
        <div class="relative max-w-[1440px] mx-auto space-y-12">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full text-[0.72rem] font-black uppercase tracking-[0.32em] bg-white/10 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4.5 h-4.5">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.89-6.89C35.82 2.38 30.41 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.99 6.21C12.12 13.39 17.55 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.5 24.55c0-1.5-.13-2.94-.38-4.35H24v8.23h12.7c-.55 2.81-2.22 5.19-4.72 6.79l7.62 5.92C44.41 36.58 46.5 30.98 46.5 24.55z"/>
                            <path fill="#FBBC05" d="M10.55 28.43A14.38 14.38 0 0 1 9.5 24c0-1.52.26-2.99.75-4.36l-7.99-6.21A23.884 23.884 0 0 0 0 24c0 3.82.91 7.42 2.51 10.59l8.04-6.16z"/>
                            <path fill="#34A853" d="M24 47.5c6.11 0 11.24-2.01 14.99-5.46l-7.62-5.92c-2.12 1.42-4.8 2.25-7.37 2.25-5.64 0-10.42-3.79-12.45-9.02l-8.04 6.16C6.56 42.47 14.51 47.5 24 47.5z"/>
                        </svg>
                        Google Reviews
                    </span>
                    <h2 class="mt-4 text-4xl lg:text-5xl font-black text-white tracking-tight">
                        {{ data_get($reviewsConfig, 'title', 'Loved by real buyers') }}
                    </h2>
                    <p class="mt-3 text-slate-300 font-medium max-w-2xl">
                        {{ data_get($reviewsConfig, 'subtitle', 'Straight from verified Google customers.') }}
                    </p>
                </div>
                <div class="flex flex-col items-start lg:items-end gap-3">
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white text-[#031629] font-black text-[0.75rem] uppercase tracking-[0.3em] shadow-lg">
                        <i data-lucide="badge-check" class="w-4 h-4"></i>
                        {{ data_get($reviewsConfig, 'badge', '4.9 / 5 • Google Reviews') }}
                    </div>
                    <p class="text-[0.6rem] text-slate-400 uppercase tracking-[0.35em]">Latest verified testimonials</p>
                </div>
            </div>

            <div class="relative" data-review-slider>
                <div class="overflow-hidden" data-review-scroll>
                    <div class="flex gap-6">
                @foreach($reviews as $review)
                <div class="shrink-0 basis-[85vw] md:basis-[48%] xl:basis-[30%]">
                    <div class="bg-white rounded-[1.75rem] border border-slate-100 shadow-[0_35px_90px_-35px_rgba(3,22,41,0.5)] p-6 h-full flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-3">
                        @php
                            $photoUrl = data_get($review, 'photo_url');
                            $initials = strtoupper(Str::substr(data_get($review, 'author', 'G'), 0, 2));
                        @endphp
                        <div class="w-12 h-12 rounded-full border-2 border-white shadow-md overflow-hidden flex items-center justify-center bg-gradient-to-br from-[#ff4605] to-[#ff6900] text-white font-black text-sm">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ data_get($review, 'author', 'Reviewer') }}" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="flex-1 pe-6">
                            <p class="text-[0.95rem] font-black text-[#031629] leading-tight">{{ data_get($review, 'author') }}</p>
                            <p class="text-[0.6rem] font-bold uppercase tracking-[0.3em] text-slate-400">{{ data_get($review, 'time', 'Recently') }}</p>
                        </div>
                        <div class="flex flex-col items-end text-right">
                            <span class="inline-flex items-center gap-1 text-[0.55rem] font-black uppercase tracking-[0.35em] text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                                    <path fill="#4285F4" d="M12 2l2.09 6.26h6.58l-5.32 3.87 2.03 6.24L12 15.5l-5.38 3.87 2.03-6.24-5.32-3.87h6.58z"/>
                                </svg>
                                Verified
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-[#ffb703]">
                        @for($i = 0; $i < data_get($review, 'rating', 5); $i++)
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        @endfor
                    </div>
                    <p class="text-[0.95rem] text-slate-600 leading-relaxed flex-1">“{{ data_get($review, 'text') }}”</p>
                    @if(data_get($review, 'profile_url'))
                        <a href="{{ data_get($review, 'profile_url') }}" target="_blank" class="inline-flex items-center gap-2 text-[0.65rem] font-black uppercase tracking-[0.3em] text-[#ff4605]">
                            Read more
                            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                    </div>
                </div>
                @endforeach
                    </div>
                </div>
                <div class="absolute inset-y-0 flex items-center justify-between pointer-events-none">
                    <button type="button" class="pointer-events-auto w-11 h-11 rounded-full bg-white/80 text-[#031629] shadow-lg flex items-center justify-center hover:bg-white" data-review-prev>
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button type="button" class="pointer-events-auto w-11 h-11 rounded-full bg-white/80 text-[#031629] shadow-lg flex items-center justify-center hover:bg-white" data-review-next>
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('[data-review-slider]');
            if (!slider) return;
            const scrollBox = slider.querySelector('[data-review-scroll]');
            const prevBtn = slider.querySelector('[data-review-prev]');
            const nextBtn = slider.querySelector('[data-review-next]');

            const updateState = () => {
                const maxScroll = scrollBox.scrollWidth - scrollBox.clientWidth;
                const left = scrollBox.scrollLeft;
                prevBtn.disabled = left <= 0;
                nextBtn.disabled = left >= maxScroll - 5;
                prevBtn.classList.toggle('opacity-30', prevBtn.disabled);
                nextBtn.classList.toggle('opacity-30', nextBtn.disabled);
            };

            const scrollByViewport = (direction = 1) => {
                const amount = scrollBox.clientWidth * direction;
                scrollBox.scrollBy({ left: amount, behavior: 'smooth' });
            };

            prevBtn.addEventListener('click', () => scrollByViewport(-1));
            nextBtn.addEventListener('click', () => scrollByViewport(1));
            scrollBox.addEventListener('scroll', updateState, { passive: true });
            window.addEventListener('resize', updateState);
            updateState();
        });
    </script>
    @else
    <section class="py-24 px-6 lg:px-12 bg-slate-100 relative z-20 text-center">
        <div class="max-w-3xl mx-auto space-y-4">
            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[0.6rem] font-black uppercase tracking-[0.3em] text-slate-500 bg-white shadow">Google Reviews Placeholder</span>
            <h2 class="text-3xl font-black text-slate-800">This is a placeholder message confirming the reviews block renders.</h2>
            <p class="text-slate-500 text-sm">Once Google Reviews are enabled and configured, this placeholder will be replaced by the live testimonials card.</p>
        </div>
    </section>
    @endif

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

    

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
         BAZAR TOAST NOTIFICATION SYSTEM
         Replaces native alert() with premium UI
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    {{-- ══════════════════════════════════════════
         LIVE AUCTION SHOWCASE SECTION
    ══════════════════════════════════════════ --}}
    @php
        $showLiveAuctions = $featuredAuctions->isNotEmpty() && auth()->check() && auth()->user()->isAdmin();
    @endphp
    @if($showLiveAuctions)
    <section class="py-24 px-6 lg:px-12 bg-white" id="live-auctions">
        <div class="mx-auto max-w-[1440px]">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="text-[0.65rem] font-black uppercase tracking-[0.35em] text-[#ff6900] block mb-3">Live Now</span>
                    <h2 class="text-4xl lg:text-5xl font-black text-[#031629] tracking-tighter leading-none">
                        Active <span class="text-[#ff6900]">Auctions</span>
                    </h2>
                    <p class="text-slate-400 font-bold text-sm mt-3">Real-time bidding — prices update live</p>
                </div>
                <a href="{{ route('auctions.index') }}" class="hidden lg:flex items-center gap-2 px-6 py-3 bg-[#031629] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-black transition-all">
                    View All <i data-lucide="arrow-right" class="w-4 h-4 text-[#ff6900]"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredAuctions as $fa)
                @php
                    $faImgs = optional($fa->car)->photos;
                    $faImg = is_string($faImgs) ? (json_decode($faImgs, true)[0] ?? null) : ($faImgs[0] ?? null);
                    $faImageUrl = $faImg ? asset('storage/' . $faImg) : '/images/cars/navy-mclaren.png';
                    $isLive = $fa->status === 'active';
                    $faPrice = $fa->current_price ?? $fa->initial_price;
                @endphp
                <a href="{{ route('auctions.show', $fa) }}" class="group block bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                    <div class="relative h-56 bg-slate-50 overflow-hidden">
                        <img src="{{ $faImageUrl }}" alt="{{ optional($fa->car)->make }} {{ optional($fa->car)->model }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 left-4">
                            @if($isLive)
                                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-full text-[0.6rem] font-black uppercase tracking-widest text-emerald-600 shadow-lg">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Now
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-full text-[0.6rem] font-black uppercase tracking-widest text-[#ff6900] shadow-lg">
                                    <span class="w-2 h-2 rounded-full bg-[#ff6900] animate-pulse"></span> Coming Soon
                                </span>
                            @endif
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-[#031629]/90 text-white rounded-full text-[0.6rem] font-black tracking-widest backdrop-blur-sm">
                                {{ $fa->bids_count }} Bids
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 mb-1">
                            {{ optional($fa->car)->year }} · Ref: {{ $fa->reference_code }}
                        </div>
                        <h3 class="text-xl font-black text-[#031629] tracking-tight leading-tight mb-4">
                            {{ optional($fa->car)->make }} {{ optional($fa->car)->model }}
                        </h3>
                        <div class="flex items-end justify-between">
                            <div>
                                <div class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-1">{{ $isLive ? 'Current Bid' : 'Starting Price' }}</div>
                                <div class="text-2xl font-black text-[#031629]">${{ number_format($faPrice, 0) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-1">{{ $isLive ? 'Ends In' : 'Opens In' }}</div>
                                <div class="text-sm font-black text-[#ff6900] tabular-nums auction-timer"
                                     data-expires="{{ $isLive ? $fa->end_at?->toIso8601String() : $fa->start_at?->toIso8601String() }}">--:--:--</div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">+${{ number_format($fa->bid_increment ?? 500, 0) }} increment</span>
                            <span class="flex items-center gap-1 text-[0.65rem] font-black text-[#ff6900] uppercase tracking-widest group-hover:gap-2 transition-all">
                                {{ $isLive ? 'Bid Now' : 'View Details' }} <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="mt-8 text-center lg:hidden">
                <a href="{{ route('auctions.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-[#031629] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest">
                    View All <i data-lucide="arrow-right" class="w-4 h-4 text-[#ff6900]"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Signal DOM ready for any remaining FOUC-prevention logic
            document.body.classList.add('dom-ready');

            // 1. Initialize Global Countdown Engine (Auction Timers)
            if (window.initCountdowns) {
                window.initCountdowns();
            }

            // 2. Initialize the "Sell Your Car" Wizard Engine
            // This modular engine handles all steps, mapping, maps, and pickers.
            if (window.initBazarWizard) {
                window.initBazarWizard({
                    brandModelMap: @json( ?? (object)[]),
                    branchCoords: [
                        {{ \App\Models\SystemSetting::get('branch_lat', '25.1384') }},
                        {{ \App\Models\SystemSetting::get('branch_lng', '55.2285') }}
                    ],
                    startStep: Math.max(0, (parseInt(document.getElementById('sellCarWizard')?.dataset.startStep || '1', 10) || 1) - 1),
                    mapProvider: window.mapProvider || 'google'
                });
            }
        });
    </script>
@endsection
