@php
    $page = $page ?? null;
    $heroContent = data_get($page?->content, 'hero', []);

    $cleanRteHtml = function ($html) {
        if (!$html)
            return $html;
        $html = preg_replace_callback('/<font([^>]*)>(.*?)<\/font>/is', function ($m) {
            $attrs = $m[1];
            $inner = $m[2];
            $style = '';
            if (preg_match('/color=["\']?([^"\'>\s]+)/i', $attrs, $c))
                $style .= 'color:' . $c[1] . ';';
            if (preg_match('/size=["\']?([^"\'>\s]+)/i', $attrs, $s)) {
                $map = ['1' => '.65rem', '2' => '.8rem', '3' => '1rem', '4' => '1.25rem', '5' => '1.6rem', '6' => '2rem', '7' => '2.8rem'];
                $style .= 'font-size:' . ($map[$s[1]] ?? '1rem') . ';';
            }
            return $style ? '<span style="' . $style . '">' . $inner . '</span>' : $inner;
        }, $html);
        return $html;
    };

    $h2Eyebrow = $cleanRteHtml(data_get($heroContent, 'announcement') ?: 'Get Your');
    $h2Title = $cleanRteHtml(data_get($heroContent, 'title') ?: 'Dream Car');
    $h2Subtitle = $cleanRteHtml(data_get($heroContent, 'subtitle') ?: 'Discover the UAE\'s most trusted pre-owned car marketplace. Every vehicle comes with a certified inspection report, transparent history, and instant offers.');
    $h2CtaLabel = data_get($heroContent, 'primary_cta_label') ?: 'Discover';
    $h2CtaUrl = data_get($heroContent, 'primary_cta_url') ?: route('auctions.index');
    $h2CarImage = ($page?->hero_image) ?: '/images/cars/car-silver.png';
    $h2CarLabel = data_get($heroContent, 'car_label') ?: 'Premium SUV';
    $h2CarMirror = (bool) data_get($heroContent, 'car_mirror', false);
    $h2CarScale = (float) data_get($heroContent, 'car_scale', 1);
    $h2CarRight = data_get($heroContent, 'car_right', -7);
    $h2CarTop = data_get($heroContent, 'car_top', 80);
    $h2CirclesEnabled = (bool) data_get($heroContent, 'circles_enabled', true);
    // Lead Form CMS
    $lfContent = data_get($page?->content, 'lead_form', []);
    $lfHeaderLabel = $leadArchitecture['header'] ?? 'Ready to sell?';
    $lfHeaderTitle = $leadArchitecture['wizard_title'] ?? 'What would you like to <span>sell?</span>';
    $lfTabCar = data_get($lfContent, 'tab_car_label', 'Car');
    $lfTabPlate = data_get($lfContent, 'tab_plate_label', 'Plate');
    $lfBrandLabel = data_get($lfContent, 'step1.brand_label', 'Select Brand');
    $lfModelLabel = data_get($lfContent, 'step1.model_label', 'Select Model');
    $lfYearLabel = data_get($lfContent, 'step1.year_label', 'Select Year');
    $lfCondLabel = data_get($lfContent, 'step2.condition_label', 'Condition');
    $lfBtnStep1 = data_get($lfContent, 'step1.button_label', 'Get Free Valuation');
    $lfNameLabel = data_get($lfContent, 'step3.name_label', 'Full Name');
    $lfPhoneLabel = data_get($lfContent, 'step3.phone_label', 'Phone / WhatsApp');
    $lfSubmitLabel = data_get($lfContent, 'step3.submit_label', 'Submit Request');
    $lfWizardW1 = $leadArchitecture['step1'] ?? 'Select';
    $lfWizardW2 = $leadArchitecture['step2'] ?? 'Customize';
    $lfWizardW3 = $leadArchitecture['step3'] ?? 'Submit';
    $lfShowHero = (bool) data_get($lfContent, 'show_hero_form', true);
    $lfHeroWidth = (int) data_get($lfContent, 'hero_form_width', 580);
    $lfCirclesEnabled = $leadArchitecture['circles_enabled'] ?? true;
    $lfFeaturedBrands = data_get($page?->content, 'lead_form_brands', []);

    // Step 2 Technical Labels & Options
    $lfBodyLabel = data_get($lfContent, 'step2.body_label', 'Body Type');
    $lfEngineLabel = data_get($lfContent, 'step2.engine_label', 'Engine Size');
    $lfMileageLabel = data_get($lfContent, 'step2.mileage_label', 'Mileage (KM)');
    $lfSpecsLabel = data_get($lfContent, 'step2.specs_label', 'Regional Specs');

    $parseList = function ($str, $sep = "\n") {
        if (!$str)
            return [];
        return array_filter(array_map('trim', explode($sep, $str)));
    };

    $lfBodyOptions = $parseList(data_get($lfContent, 'step2.body_options'));
    if (empty($lfBodyOptions))
        $lfBodyOptions = ['Sedan', 'SUV', 'Crossover', 'Coupe', 'Convertible', 'Hatchback', 'Van', 'Pickup'];

    $lfEngineOptions = $parseList(data_get($lfContent, 'step2.engine_options'), ',');
    if (empty($lfEngineOptions))
        $lfEngineOptions = ['1.0L', '1.2L', '1.4L', '1.6L', '1.8L', '2.0L', '2.4L', '3.0L', '4.0L', '5.0L', '6.0L', 'Other'];

    $lfMileageOptions = $parseList(data_get($lfContent, 'step2.mileage_options'));
    if (empty($lfMileageOptions))
        $lfMileageOptions = ['Up to 10,000 KM', 'Up to 30,000 KM', 'Up to 60,000 KM', 'Up to 100,000 KM', 'Up to 150,000 KM', 'More than 200,000 KM'];

    $lfSpecsOptions = $parseList(data_get($lfContent, 'step2.specs_options'));
    if (empty($lfSpecsOptions))
        $lfSpecsOptions = ['GCC Specs', 'American Specs', 'Japanese Specs', 'European Specs', 'Other'];

    $lfPaintOptions = $parseList(data_get($lfContent, 'step2.paint_options'));
    if (empty($lfPaintOptions))
        $lfPaintOptions = ['Original Paint', '1-2 Panels Repaint', 'Total Repaint', 'Unknown'];

    $lfTrimOptions = $parseList(data_get($lfContent, 'step2.trim_options'));
    if (empty($lfTrimOptions))
        $lfTrimOptions = ['Basic', 'Mid', 'Full', 'Unknown'];

    // Plate Funnel Labels
    $lfPlateCodeLabel = data_get($lfContent, 'plate.code_label', 'Plate Code');
    $lfPlateNumLabel = data_get($lfContent, 'plate.number_label', 'Plate Number');
    $lfPlateSubtitle = data_get($lfContent, 'plate.subtitle', 'Plate Details');
    $lfPlateBtn = data_get($lfContent, 'plate.button_label', 'CONTINUE TO CONTACT');

    // Global Success Message
    $lfSuccessMsg = data_get($lfContent, 'success_message', 'Valuation request submitted successfully!');
    $lfFinalBtn = data_get($lfContent, 'final_btn_label', 'COMPLETE VALUATION');

    // Hero Atmosphere & Background
    $heroBgMode = data_get($heroContent, 'background_mode', 'image');
    $heroBgColor = data_get($heroContent, 'background_color', '#e7e7e7');
    $heroBgColorSecondary = data_get($heroContent, 'background_color_secondary', '#cbd5e1');
    $heroBgAngle = data_get($heroContent, 'background_gradient_angle', 135);
    $heroBgImage = data_get($heroContent, 'background_image', '/images/hero-bg.png');
    $heroOverlayEnabled = (bool) data_get($heroContent, 'background_overlay_enabled', true);
    $heroOverlayOpacity = data_get($heroContent, 'background_overlay_opacity', 0.72);
    $heroCustomCss = data_get($heroContent, 'custom_css');

    // Build Hero Background Style
    $heroStyleFinal = "";
    if ($heroBgMode === 'solid') {
        $heroStyleFinal = "background: {$heroBgColor} !important;";
    } elseif ($heroBgMode === 'gradient') {
        $heroStyleFinal = "background: linear-gradient({$heroBgAngle}deg, {$heroBgColor} 0%, {$heroBgColorSecondary} 100%) !important;";
    } elseif ($heroBgMode === 'image') {
        $overlay = $heroOverlayEnabled ? "linear-gradient(rgba(14,16,23,{$heroOverlayOpacity}), rgba(14,16,23,{$heroOverlayOpacity})), " : "";
        $heroStyleFinal = "background: {$overlay} url('{$heroBgImage}') !important; background-size: cover !important; background-position: center !important;";
    } elseif ($heroBgMode === 'custom' && $heroCustomCss) {
        $heroStyleFinal = "{$heroCustomCss} !important;";
    } else {
        $heroStyleFinal = "background: #e7e7e7 !important;";
    }

    // Trust Badges Data
    $trustBadges = data_get($page?->content, 'trust_badges', []);
    if (empty($trustBadges)) {
        $trustBadges = [
            ['label' => 'Guaranteed Purchase', 'icon' => 'shield-check', 'color' => '#3b82f6', 'bg_color' => '#ebf5ff'],
            ['label' => 'Free Service', 'icon' => 'wallet', 'color' => '#ff6900', 'bg_color' => '#fff7ed'],
            ['label' => 'Total Trust', 'icon' => 'zap', 'color' => '#22c55e', 'bg_color' => '#f0fdf4']
        ];
    }

    // Split multi-word labels if needed
    $formattedBadges = [];
    foreach ($trustBadges as $badge) {
        $lbl = $badge['label'] ?? '';
        $parts = explode(' ', $lbl, 2);
        $formattedBadges[] = [
            'main' => $parts[0] ?? '',
            'sub' => $parts[1] ?? '',
            'desc' => $badge['desc'] ?? null,
            'icon' => $badge['icon'] ?? 'star',
            'color' => $badge['color'] ?? '#333',
            'bg' => $badge['bg_color'] ?? '#f8fafc'
        ];
    }

    /**
     * عطّل كل الأقسام بين الهيرو والفوتر (شريط الثقة، Google Reviews، فئات الهيكل، المزادات) مع الإبقاء على الهيرو والفوتر فقط — للتشخيص.
     * غيّر إلى true لإعادة تفعيل كل الأقسام.
     */
    $h2ShowMiddleSections = true;

    // Brand Logo Helper
    $getBrandLogo = function ($slug) {
        $variants = [$slug, str_replace(['-', '_'], '', $slug), explode('-', $slug)[0]];
        foreach ($variants as $v) {
            foreach (['svg', 'png', 'jpg'] as $ext) {
                if (file_exists(public_path("images/brands/{$v}.{$ext}")))
                    return "/images/brands/{$v}.{$ext}";
            }
        }
        return "/images/brands/default.svg";
    };

    $navbarContent = data_get($page?->content, 'navbar', []);
    $navPhone = data_get($navbarContent, 'phone');
    $navWhatsapp = data_get($navbarContent, 'whatsapp');
    $navHours = data_get($navbarContent, 'hours');
    $navBgColor = data_get($navbarContent, 'bg_color', '#ffffff');
    $navTxtColor = data_get($navbarContent, 'text_color', '#031629');
    $navDotColor = data_get($navbarContent, 'dot_color', '#ff6900');
    $navLogoScale = data_get($navbarContent, 'logo_scale', 100) / 100;
    $navSticky = (bool) data_get($navbarContent, 'sticky', true);
    $headerMenu = \App\Models\Menu::where('location', 'header')->with(['items' => fn($q) => $q->orderBy('order')])->first();
    $navMenuItems = $headerMenu?->items ?? collect();
    if (isset($featuredAuctions) && $featuredAuctions->first()) {
        $h2CarLabel = ($featuredAuctions->first()->car?->make ?? 'SUV') . ' ' . ($featuredAuctions->first()->car?->model ?? '');
    }

    $brandSelectBrands2 = collect($catalogMakesWithLogos ?? [])
        ->filter(fn($b) => !empty(data_get($b, 'name')))
        ->values()->all();

    $brandCardBrands2 = $leadArchitecture['featured_brands'] ?? [];
    $wizardStartStep2 = $wizardStartStep ?? 1;
    $h2Years = range(date('Y') + 1, 1970);

    // Social links for footer
    $allSocialKeys = ['facebook', 'instagram', 'tiktok', 'youtube', 'x', 'linkedin', 'whatsapp'];
    $footerSocials = [];
    foreach ($allSocialKeys as $sk) {
        $url = \App\Models\SystemSetting::get('social_' . $sk, '');
        if ($url && \App\Models\SystemSetting::get('social_' . $sk . '_show_footer', '0') === '1') {
            $footerSocials[$sk] = $url;
        }
    }
@endphp
{{-- Car Sale Landing Page --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strip_tags($h2Title) }} — {{ \App\Models\SystemSetting::get('site_name', 'Motor Bazar') }}</title>
    {{-- قالب الصفحة: home2 (standalone) — ليس home.blade.php --}}
    <meta name="app-home-template" content="home2">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&family=Saira+Condensed:wght@700;800;900&family=Bebas+Neue&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --h2-nav-bg:
                {{ $navBgColor }}
            ;
            --h2-nav-txt:
                {{ $navTxtColor }}
            ;
            --h2-nav-dot:
                {{ $navDotColor }}
            ;
            --h2-nav-pos:
                {{ $navSticky ? 'sticky' : 'relative' }}
            ;
            --h2-nav-top:
                {{ $navSticky ? '0' : 'auto' }}
            ;
            --h2-nav-z: 1000;
            --h2-hero-form-width:
                {{ $lfHeroWidth }}
                px;
        }

        .nav-logo img {
            transform: scale({{ $navLogoScale }});
            transform-origin: left center;
        }

        body {
            background: #e7e7e7 !important;
        }

        .hero {
            {!! $heroStyleFinal !!}
            height: calc(100vh - 80px) !important;
            min-height: 950px !important;
            display: flex !important;
            align-items: flex-start !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .hero-inner {
            width: 100% !important;
            max-width: 1600px !important;
            margin: 0 auto !important;
            padding: 120px 4% 0 4% !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: flex-start !important;
            gap: 60px !important;
            position: relative !important;
            height: auto !important;
            z-index: 10 !important;
        }

        .hero-form-col {
            flex: 0 0 var(--h2-hero-form-width) !important;
            position: relative !important;
            z-index: 100 !important;
        }

        .hero-text {
            flex: 1 !important;
            position: relative !important;
            z-index: 80 !important;
            text-align: left !important;
            padding-top: 35px !important;
        }

        .hero-car {
            position: absolute !important;
            right:
                {{ $h2CarRight }}
                % !important;
            top: 635px !important;
            bottom: auto !important;
            width: 70% !important;
            z-index: 0 !important;
            pointer-events: none !important;
            opacity: 0.8 !important;
        }

        /* شريط الثقة أصبح قسماً عادياً تحت الهيرو (.h2-trust-strip) — لا absolute داخل .hero */

        /* Forced Stacking for Sections */
        main section {
            display: block !important;
            float: none !important;
            clear: both !important;
        }

        .section-separator {
            height: 100px;
            background: white;
            position: relative;
            z-index: 5;
        }

        .hero-title {
            font-size: 3.5rem !important;
            line-height: 1.05 !important;
            font-weight: 950 !important;
        }

        .hub-drawer {
            top: calc(100% + 15px) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 30px 70px -10px rgba(3, 22, 41, 0.45) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .sc-btn {
            width: 100%;
            height: 54px !important;
            background: linear-gradient(135deg, #ff6900 0%, #ff8c33 100%) !important;
            color: white !important;
            border-radius: 20px !important;
            font-size: 0.85rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px !important;
            margin: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent !important;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 105, 0, 0.3);
            position: relative;
            overflow: hidden;
            box-sizing: border-box !important;
            line-height: 1 !important;
            align-self: center;
        }

        .sc-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 105, 0, 0.45);
            filter: brightness(1.1);
        }

        .sc-btn::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: rotate(30deg);
            animation: sc-shine 3s infinite;
        }

        @keyframes sc-shine {
            0% {
                transform: translateX(-150%) rotate(30deg);
            }

            100% {
                transform: translateX(150%) rotate(30deg);
            }
        }

        .sc-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .sc-btn:disabled {
            background: linear-gradient(135deg, #ff6900 0%, #ff8c33 100%) !important;
            box-shadow: 0 4px 15px rgba(255, 105, 0, 0.3);
            cursor: pointer;
            opacity: 1;
        }

        .sc-btn-back {
            height: 54px !important;
            width: 54px !important;
            background: #ffffff !important;
            border: 2px solid #e2e8f0 !important;
            color: #64748b !important;
            border-radius: 20px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            margin: 0;
            cursor: pointer;
            transition: all 0.3s ease;
            box-sizing: border-box !important;
            flex-shrink: 0;
            line-height: 1 !important;
            align-self: center;
        }

        .sc-btn-back:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
            transform: translateY(-1px);
        }

        .sc-body .sc-btn {
            top: 15px !important;
        }

        /* يفصل طبقة الهيرو عن بقية الصفحة — كان app.css يكرر .sc-pill-container ويلغي position:absolute */
        body.home2 main>section.hero {
            overflow: visible !important;
        }

        body.home2 main>.h2-main-below-hero {
            position: relative;
            z-index: 30 !important;
            isolation: isolate;
        }

        /* فوتر home2 — إعادة تصميم للتشخيص: خلفية صلبة، طبقة معزولة، بدون blur/shadow يخلط الطبقات */
        body.home2 .h2-footer-root {
            position: relative;
            z-index: 9999;
            isolation: isolate;
            background-color: #0b1220 !important;
            background-image: none !important;
            color: #e2e8f0;
            border-top: 4px solid #ff6900;
            box-shadow: none !important;
            -webkit-font-smoothing: antialiased;
        }

        body.home2 .h2-footer-root a {
            color: #cbd5e1;
        }

        body.home2 .h2-footer-root a:hover {
            color: #fff;
        }
    </style>
</head>

<body class="home2" x-data>

    {{-- ═══ NAVBAR ═══ --}}
    <nav class="nav">

        {{-- ① LEFT: Logo + Pill --}}
        <div class="nav-left">
            <a href="{{ route('home2') }}" class="nav-logo">
                @php
                    $siteLogo = \App\Models\SystemSetting::get('site_logo');
                    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
                @endphp
                @if($siteLogoUrl)
                    <img src="{{ $siteLogoUrl }}" alt="{{ \App\Models\SystemSetting::get('site_name', 'Motor Bazar') }}"
                        style="height:70px;width:auto;object-fit:contain">
                @else
                    <div class="nav-dots">
                        <div class="nav-dot big"></div>
                        <div class="nav-dot sm"></div>
                    </div>
                    <span class="nav-brand">{{ \App\Models\SystemSetting::get('site_name', 'Motor Bazar') }}</span>
                @endif
            </a>

            {{-- Pill nav --}}
            <div class="nav-pill">
                <a href="{{ route('home2') }}" class="nav-pill-item active" title="Home">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
                <div class="nav-pill-sep"></div>
                <a href="{{ route('auctions.index') }}" class="nav-pill-item" title="Auctions">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </a>
                @if($navPhone)
                    <div class="nav-pill-sep"></div>
                    <a href="tel:{{ $navPhone }}" class="nav-pill-item" title="{{ $navPhone }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                        </svg>
                    </a>
                @endif
                @if($navWhatsapp)
                    <div class="nav-pill-sep"></div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $navWhatsapp) }}" target="_blank"
                        class="nav-pill-item" title="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                        </svg>
                    </a>
                @endif
                <div class="nav-pill-sep"></div>
                <a href="#" class="nav-pill-item" title="Location">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- ② CENTER: Dynamic Menu Links --}}
        <div class="nav-center">
            @foreach($navMenuItems as $item)
                <a href="{{ $item->url }}" class="nav-menu-link">{{ $item->label }}</a>
            @endforeach
        </div>

        {{-- ③ RIGHT: Phone + Search + Avatar --}}
        <div class="nav-right">

            {{-- Search Bar --}}
            <div class="nav-search">
                <input type="text" placeholder="Search..."
                    onkeydown="if(event.key==='Enter') window.location='{{ route('auctions.index') }}?search='+encodeURIComponent(this.value)">
                <button class="nav-search-btn"
                    onclick="window.location='{{ route('auctions.index') }}?search='+encodeURIComponent(this.previousElementSibling.value)">
                    Go
                </button>
            </div>

            {{-- User Avatar / Profile --}}
            @auth
                <div style="position:relative" x-data="{open:false}">
                    <button @click="open=!open"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #ff6900;overflow:hidden;cursor:pointer;background:#f1f5f9;flex-shrink:0;display:flex;align-items:center;justify-content:center;padding:0">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
                                style="width:100%;height:100%;object-fit:cover" alt="">
                        @else
                            <span
                                style="font-size:.7rem;font-weight:900;color:#031629">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        @endif
                    </button>
                    <div x-show="open" @click.outside="open=false" x-cloak
                        style="position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #e8ecf0;border-radius:0.75rem;box-shadow:0 16px 40px rgba(3,22,41,.12);min-width:180px;padding:8px;z-index:999">
                        <div style="padding:10px 12px 8px;border-bottom:1px solid #f1f5f9;margin-bottom:4px">
                            <p style="font-size:.72rem;font-weight:800;color:#031629">{{ auth()->user()->name }}</p>
                            <p style="font-size:.62rem;color:#94a3b8">{{ auth()->user()->email }}</p>
                        </div>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:.68rem;font-weight:700;color:#031629;text-decoration:none;transition:background .15s"
                                onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                                </svg>
                                Admin Panel
                            </a>
                        @endif
                        <a href="#"
                            style="display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:.68rem;font-weight:700;color:#031629;text-decoration:none;transition:background .15s"
                            onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            My Profile
                        </a>
                        <div style="height:1px;background:#f1f5f9;margin:4px 0"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                style="width:100%;display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;font-size:.68rem;font-weight:700;color:#ef4444;background:none;border:none;cursor:pointer;text-align:left;transition:background .15s"
                                onmouseover="this.style.background='#fff5f5'"
                                onmouseout="this.style.background='transparent'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    style="display:flex;align-items:center;gap:7px;height:40px;padding:0 18px;background:#031629;color:#fff;border-radius:0.75rem;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;transition:background .2s"
                    onmouseover="this.style.background='#ff6900'" onmouseout="this.style.background='#031629'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign In
                </a>
            @endauth

        </div>

    </nav>

    {{-- ═══ HERO ═══ --}}
    <main class="flex flex-col w-full min-h-screen relative">
        <section class="hero w-full block relative z-10">
            <div class="hero-inner">

                @if($lfShowHero)
                    <div class="hero-form-col">
                        {{-- Left: Sell Card --}}
                        @php
                            $h2Models = $catalogModelsByMake ?? [];
                        @endphp
                        <textarea id="h2-models-data" style="display:none">@json($h2Models)</textarea>
                        <div class="search-card" x-cloak x-data="{
                                    allModels: JSON.parse(document.getElementById('h2-models-data')?.value || '{}'),
                                    heroWizardTab: 'car',
                                    heroWizardStep: 1,
                                    name: '', phone: '', email: '',
                                    make: '', model: '', year: '', condition: 'good',
                                    body: '', engine: '', mileage: '', trim: 'Full option', paint: 'Original', gcc: 'GCC',
                                    inspectionDate: '{{ now()->addDays(1)->format('Y-m-d') }}', inspectionTime: '10:00',
                                    address: '',
                                    inspectionType: 'branch',
                                    plate: '', plateCode: 'A', emirate: 'Dubai',
                                    search: '', modelSearch: '',
                                    scCurrentField: null,
                                    showToast: false,
                                    toastMsg: '',
                                    triggerToast(msg) {
                                        this.toastMsg = msg;
                                        this.showToast = true;
                                        setTimeout(() => { this.showToast = false; }, 3000);
                                    },
                                    init() {
                                        window.addEventListener('sync-pickers', (e) => {
                                            this.inspectionDate = e.detail.date;
                                            this.inspectionTime = e.detail.time;
                                        });
                                    },
                                    plateCodeMap: {
                                        'Dubai': ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z', 'AA','BB','CC','DD','EE','FF','GG','HH','II','JJ','KK','LL','MM','NN','OO','PP','QQ','RR','SS','TT','UU','VV','WW','XX','YY','ZZ', 'Blank'],
                                        'Abu Dhabi': ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20', '50', 'Blank'],
                                        'Sharjah': ['1','2','3','Blank'],
                                        'Ajman': ['A','B','C','D','E','H','Blank'],
                                        'RAK': ['A','C','D','I','K','M','N','S','V','Y','Blank'],
                                        'UAQ': ['A','B','C','D','E','F','G','I','X','Blank'],
                                        'Fujairah': ['A','B','C','D','E','F','G','K','M','P','R','S','T','Blank'],
                                        'Other': ['Blank']
                                    },
                                    get availableCodes() { return this.plateCodeMap[this.emirate] || ['Blank']; },
                                    get plateFile() {
                                        const map = { 
                                            'UAQ': 'quwain', 
                                            'Abu Dhabi': 'abudhabi',
                                            'Fujairah': 'fujaira'
                                        };
                                        return '/images/plates/' + (map[this.emirate] || this.emirate.toLowerCase().replace(/\s+/g, '')) + '.png';
                                    },

                                    get models() {
                                        const k = this.make.toLowerCase().replace(/[^a-z0-9]+/g,'');
                                        return this.allModels[k] || this.allModels['__all__'] || [];
                                    }
                                 }">

                            {{-- Header --}}
                            <div class="sc-header" style="margin-bottom: 25px;">
                                <p class="sc-label"
                                    style="margin-bottom: 4px !important; color: #ff6900; font-size: 0.7rem; font-weight: 1000; letter-spacing: 0.1em;">
                                    {{ $lfHeaderLabel }}
                                </p>
                                <h3 class="sc-title"
                                    style="margin-top: 0 !important; font-size: 1.35rem; font-weight: 900; line-height: 1.1; color: #031629;">
                                    {!! $lfHeaderTitle !!}
                                </h3>
                            </div>


                            <div class="sc-tabs" x-show="heroWizardStep === 1">
                                <button type="button" class="sc-tab" :class="heroWizardTab==='car' ? 'active' : ''"
                                    @click="heroWizardTab='car'; heroWizardStep=1; scCurrentField=null">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round" style="margin-right: 8px;">
                                        <path
                                            d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2" />
                                        <circle cx="7" cy="17" r="2" />
                                        <path d="M9 17h6" />
                                        <circle cx="17" cy="17" r="2" />
                                    </svg>
                                    <span
                                        style="font-size: 0.85rem; font-weight: 1000; letter-spacing: 0.05em;">{{ $lfTabCar }}</span>
                                </button>
                                <button type="button" class="sc-tab" :class="heroWizardTab==='plate' ? 'active' : ''"
                                    @click="heroWizardTab='plate'; heroWizardStep=1; scCurrentField=null">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                        style="margin-right: 8px;">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                                    </svg>
                                    <span
                                        style="font-size: 0.85rem; font-weight: 1000; letter-spacing: 0.05em;">{{ $lfTabPlate }}</span>
                                </button>
                            </div>

                            {{-- ── CAR TAB ── --}}
                            <div class="sc-body" x-show="heroWizardTab==='car'"
                                :style="heroWizardTab==='car' ? 'display:block' : 'display:none'">

                                {{-- STEP 1: Select --}}
                                <div x-show="heroWizardStep === 1">
                                    <p class="sc-label"
                                        style="color:#94a3b8; font-size:0.55rem; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.1em;">
                                        Popular Brands</p>
                                    <div class="grid grid-cols-3 gap-3" style="margin-bottom: 30px !important;">
                                        @foreach(array_slice($brandCardBrands2, 0, 6) as $fb)
                                            <div class="sc-brand-item" @click="make='{{ $fb['name'] }}'; scCurrentField='model'"
                                                :style="make === '{{ $fb['name'] }}' ? 'border-color:#ff6900; background:#fff9f5;' : ''">
                                                <img src="{{ $fb['logo'] }}" alt="{{ $fb['name'] }}"
                                                    :style="make === '{{ $fb['name'] }}' ? 'filter:grayscale(0); opacity:1;' : ''">
                                                <span
                                                    :style="make === '{{ $fb['name'] }}' ? 'color:#031629' : ''">{{ $fb['name'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="sc-field" style="position:relative"
                                        :style="scCurrentField === 'make' ? 'z-index:220; border-color:#ff6900' : 'z-index:10'">
                                        <svg class="sc-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.182 0l4.318-4.318a2.25 2.25 0 0 0 0-3.182L11.159 3.659A2.25 2.25 0 0 0 9.568 3Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                        </svg>
                                        <button type="button"
                                            @click.stop="scCurrentField = (scCurrentField==='make'?null:'make')"
                                            class="flex items-center w-full h-full bg-transparent border-none text-[0.85rem] font-bold text-[#031629] px-0 outline-none cursor-pointer">
                                            <span x-text="make || '{{ $lfBrandLabel }}'"></span>
                                        </button>
                                        <div x-show="scCurrentField === 'make'" @click.away="scCurrentField = null"
                                            class="hub-drawer">
                                            <div class="hub-drawer-header">
                                                <div style="position:relative; flex:1">
                                                    <svg class="hub-search-icon" xmlns="http://www.w3.org/2000/svg"
                                                        width="18" height="18" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                    </svg>
                                                    <input type="text" x-model="search" placeholder="Search brand..."
                                                        class="hub-search">
                                                </div>
                                                <button type="button" @click="make=''; search=''; scCurrentField=null"
                                                    class="hub-btn-link">Reset</button>
                                                <button type="button" @click="scCurrentField=null"
                                                    class="hub-btn-link">Close</button>
                                            </div>
                                            <div class="hub-list">
                                                <div class="space-y-1">
                                                    @foreach($brandSelectBrands2 as $brand)
                                                        @php
                                                            $bKey = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                                        @endphp
                                                        <button type="button"
                                                            x-show="'{{ $bKey }}'.includes(search.toLowerCase())"
                                                            @click="make = '{{ $brand['name'] }}'; scCurrentField = null"
                                                            class="w-full flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-slate-50 group"
                                                            :class="make === '{{ $brand['name'] }}' ? 'bg-orange-50 border border-orange-100' : 'border border-transparent'">
                                                            <div
                                                                class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center p-1.5 border border-slate-100 group-hover:border-orange-200">
                                                                <img src="{{ $brand['logo'] }}" alt=""
                                                                    class="w-full h-full object-contain">
                                                            </div>
                                                            <span class="text-[0.85rem] font-bold text-[#031629]"
                                                                :class="make === '{{ $brand['name'] }}' ? 'text-orange-600' : ''">{{ $brand['name'] }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sc-field" style="position:relative"
                                        :style="scCurrentField === 'model' ? 'z-index:220; border-color:#ff6900' : 'z-index:10'">
                                        <svg class="sc-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177V3.945c0-.621-.504-1.125-1.125-1.125H1.125C.504 2.82 0 3.324 0 3.945v11.805a1.125 1.125 0 0 0 1.125 1.125h2.25" />
                                        </svg>
                                        <button type="button" :disabled="!make"
                                            @click.stop="scCurrentField = (scCurrentField==='model'?null:'model')"
                                            class="flex items-center w-full h-full bg-transparent border-none text-[0.85rem] font-bold text-[#031629] px-0 outline-none cursor-pointer disabled:opacity-50">
                                            <span x-text="model || '{{ $lfModelLabel }}'"></span>
                                        </button>
                                        <div x-show="scCurrentField === 'model'" @click.away="scCurrentField = null"
                                            class="hub-drawer">
                                            <div class="hub-drawer-header">
                                                <div style="position:relative; flex:1">
                                                    <svg class="hub-search-icon" xmlns="http://www.w3.org/2000/svg"
                                                        width="18" height="18" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.8" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                    </svg>
                                                    <input type="text" x-model="modelSearch" placeholder="Search model..."
                                                        class="hub-search">
                                                </div>
                                                <button type="button" @click="scCurrentField=null"
                                                    class="hub-btn-link">Close</button>
                                            </div>
                                            <div class="hub-list">
                                                <div class="grid grid-cols-2 gap-1.5">
                                                    <template
                                                        x-for="m in models.filter(x => x.toLowerCase().includes(modelSearch.toLowerCase()))"
                                                        :key="m">
                                                        <button type="button" @click="model = m; scCurrentField = null"
                                                            class="hub-option" x-text="m"></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="sc-field" style="position:relative"
                                        :style="scCurrentField === 'year' ? 'z-index:220; border-color:#ff6900' : 'z-index:10'">
                                        <svg class="sc-field-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        <button type="button"
                                            @click.stop="scCurrentField = (scCurrentField==='year'?null:'year')"
                                            class="flex items-center w-full h-full bg-transparent border-none text-[0.85rem] font-bold text-[#031629] px-0 outline-none cursor-pointer">
                                            <span x-text="year || '{{ $lfYearLabel }}'"></span>
                                        </button>
                                        <div x-show="scCurrentField === 'year'" @click.away="scCurrentField = null"
                                            class="hub-drawer">
                                            <div class="grid grid-cols-4 gap-1.5 max-h-60 overflow-y-auto">
                                                @foreach($h2Years as $y)
                                                    <button type="button" @click="year = '{{ $y }}'; scCurrentField = null"
                                                        class="hub-option justify-center">{{ $y }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-5 relative">
                                        {{-- Alpine Toast --}}
                                        <div x-show="showToast" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-2"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-200"
                                            class="absolute -top-12 left-0 right-0 py-2.5 px-4 bg-[#031629] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-xl shadow-2xl text-center z-[1000] border border-white/10"
                                            style="width: 100%;">
                                            <span x-text="toastMsg"></span>
                                        </div>

                                        <button type="button" class="sc-btn w-full mt-4"
                                            @click="if(make && model && year) { heroWizardStep=2 } else { triggerToast('Please select Brand, Model and Year first!') }">
                                            CONTINUE TO CUSTOMIZE &rarr;
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 2: Technical Specs --}}
                                <div x-show="heroWizardStep === 2">
                                    <div class="grid grid-cols-2 gap-8 items-start">
                                        {{-- Left Col: Technical Basics (Single Column) --}}
                                        <div class="space-y-6">
                                            <div>
                                                <span class="field-lbl">{{ $lfBodyLabel }}</span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="body"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select {{ $lfBodyLabel }}</option>
                                                        @foreach($lfBodyOptions as $opt)
                                                            <option>{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl">{{ $lfEngineLabel }}</span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="engine"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select {{ $lfEngineLabel }}</option>
                                                        @foreach($lfEngineOptions as $eng)
                                                            <option>{{ $eng }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl">{{ $lfMileageLabel }}</span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="mileage"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select {{ $lfMileageLabel }}</option>
                                                        @foreach($lfMileageOptions as $mile)
                                                            <option>{{ $mile }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl">{{ $lfSpecsLabel }}</span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="gcc"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select {{ $lfSpecsLabel }}</option>
                                                        @foreach($lfSpecsOptions as $spec)
                                                            <option>{{ $spec }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Right Col: Condition Toggles --}}
                                        <div class="space-y-6">
                                            <div>
                                                <span class="field-lbl">Trim Option</span>
                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                    <template x-for="opt in {{ json_encode($lfTrimOptions) }}">
                                                        <button type="button" @click="trim=opt"
                                                            class="h-[52px] rounded-xl border text-[0.65rem] font-black uppercase transition-all"
                                                            :class="trim === opt ? 'bg-[#ff6900] border-[#ff6900] text-white shadow-lg shadow-orange-200' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400'"
                                                            x-text="opt === 'Unknown' ? 'I don\'t know' : (['Basic','Mid','Full'].includes(opt) ? opt + ' option' : opt)">
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>

                                            <div>
                                                <span class="field-lbl">Paint Condition</span>
                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                    <template x-for="p in {{ json_encode($lfPaintOptions) }}">
                                                        <button type="button" @click="paint=p"
                                                            class="h-[52px] rounded-xl border text-[0.6rem] font-black uppercase transition-all"
                                                            :class="paint === p ? 'bg-[#ff6900] border-[#ff6900] text-white shadow-lg shadow-orange-200' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400'"
                                                            x-text="p">
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        style="display:flex; align-items:center; justify-content:between; pt-8 border-t border-slate-100 mt-6 gap-3">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn flex-1" @click="heroWizardStep=3">
                                            CONTINUE TO BOOKING &rarr;
                                        </button>
                                    </div>
                                </div>

                                {{-- STEP 3: Booking & Contact --}}
                                <div x-show="heroWizardStep === 3" x-cloak>
                                    {{-- 1. Service Type Tabs (Enlarged) --}}
                                    <p class="sc-label"
                                        style="color:#94a3b8; font-size:0.55rem; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.1em;">
                                        Where should we meet?</p>
                                    <div class="grid grid-cols-2 gap-4" style="margin-bottom:25px;">
                                        <button type="button" @click="inspectionType='branch'"
                                            class="group relative flex flex-col items-center justify-center gap-1.5 border-2 rounded-2xl transition-all p-2 h-[75px]"
                                            :style="inspectionType === 'branch' ? 'border-color:#ff6900; background:#fff7f0; color:#ff6900; box-shadow:0 10px 30px -10px rgba(255,105,0,0.2)' : 'border-color:#eef2f6; background:#f8fafc; color:#64748b;'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="10" width="20" height="12" rx="2" ry="2" />
                                                <path d="m12 10 4.46-4.46a2 2 0 0 0-2.82-2.82L9 7.32" />
                                                <path d="m7 10 4.46-4.46a2 2 0 0 0-2.82-2.82L4 7.32" />
                                            </svg>
                                            <span
                                                style="font-size: 0.6rem; font-weight: 1000; text-transform: uppercase; letter-spacing: 0.05em;">Visit
                                                Branch</span>
                                            <div x-show="inspectionType === 'branch'"
                                                class="absolute -top-1.5 -right-1.5 bg-[#ff6900] text-white p-0.5 rounded-full shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </div>
                                        </button>
                                        <button type="button" @click="inspectionType='home'"
                                            class="group relative flex flex-col items-center justify-center gap-1.5 border-2 rounded-2xl transition-all p-2 h-[75px]"
                                            :style="inspectionType === 'home' ? 'border-color:#ff6900; background:#fff7f0; color:#ff6900; box-shadow:0 10px 30px -10px rgba(255,105,0,0.2)' : 'border-color:#eef2f6; background:#f8fafc; color:#64748b;'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                <polyline points="9 22 9 12 15 12 15 22" />
                                            </svg>
                                            <span
                                                style="font-size: 0.6rem; font-weight: 1000; text-transform: uppercase; letter-spacing: 0.05em;">Home
                                                Service</span>
                                            <div x-show="inspectionType === 'home'"
                                                class="absolute -top-1.5 -right-1.5 bg-[#ff6900] text-white p-0.5 rounded-full shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                            </div>
                                        </button>
                                    </div>

                                    {{-- 2. Enhanced Visual MAP Section --}}
                                    <div x-data="{ 
                                                mapUrl: 'https://maps.googleapis.com/maps/api/staticmap?center=25.07,55.15&zoom=12&size=600x400&scale=2&style=feature:all|element:labels|visibility:off&style=feature:geometry|color:0xf5f5f5&style=feature:water|color:0xc9c9c9&key={{ \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY')) }}',
                                                isLocating: false,
                                                locate() {
                                                    this.isLocating = true;
                                                    if (navigator.geolocation) {
                                                        navigator.geolocation.getCurrentPosition((position) => {
                                                            const lat = position.coords.latitude;
                                                            const lng = position.coords.longitude;
                                                            address = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                                                            this.mapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lng}&zoom=15&size=600x400&scale=2&markers=color:orange|${lat},${lng}&key={{ \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY')) }}`;
                                                            this.isLocating = false;
                                                        });
                                                    }
                                                }
                                            }"
                                        style="height:270px; position:relative; border-radius:1.5rem; overflow:hidden; border:4px solid #fff; box-shadow:0 25px 60px -20px rgba(3,22,41,0.2); margin-bottom:30px;">
                                        <img :src="mapUrl"
                                            class="w-full h-full object-cover grayscale opacity-80 transition-all duration-700">

                                        {{-- Map Overlay: Search --}}
                                        <div class="absolute top-4 left-4 right-4 group">
                                            <div
                                                class="bg-white/90 backdrop-blur-md rounded-xl p-1 shadow-2xl border border-white/50 flex items-center transition-all group-hover:bg-white">
                                                <div class="w-9 h-9 flex items-center justify-center text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                    </svg>
                                                </div>
                                                <input type="text" x-model="address" placeholder="Search for location..."
                                                    class="flex-1 bg-transparent border-none outline-none text-[0.75rem] font-bold text-[#031629] py-2">
                                                <button type="button"
                                                    class="w-8 h-8 rounded-lg bg-[#ff6900] text-white flex items-center justify-center shadow-lg mr-1 hover:scale-105 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="3"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Map Overlay: Locate Me Button --}}
                                        <div class="absolute bottom-4 right-4" x-data="{ pulse: false }">
                                            <button type="button" @click="locate()"
                                                :class="isLocating ? 'animate-pulse' : ''"
                                                class="w-10 h-10 bg-white shadow-xl rounded-xl flex items-center justify-center text-slate-600 hover:text-orange-600 transition-all border border-slate-100">
                                                <svg x-show="!isLocating" xmlns="http://www.w3.org/2000/svg" width="20"
                                                    height="20" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                </svg>
                                                <svg x-show="isLocating" class="animate-spin"
                                                    xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none"
                                            x-show="!isLocating">
                                            <div
                                                class="w-12 h-12 bg-[#ff6900] rounded-full border-4 border-white shadow-2xl flex items-center justify-center animate-bounce">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 3. Elite Date & Time Picker --}}
                                    <div style="margin-bottom:30px;">
                                        <x-elite-picker dateId="carInspDate" timeId="carInspTime" dateName="inspection_date"
                                            timeName="inspection_time" />
                                    </div>

                                    {{-- 4. Final Contact Data (Grid) --}}
                                    <div class="grid grid-cols-2 gap-3 mb-6" style="margin-top:30px;">
                                        <div class="col-span-2">
                                            <div class="sc-field" style="height:52px; background:#f8fafc;">
                                                <input type="text" x-model="name" placeholder="{{ $lfNameLabel }}"
                                                    class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                            </div>
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="{{ $lfPhoneLabel }}"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="email" x-model="email" placeholder="Email Address"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:10px; margin-top:20px;">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn flex-1" @click="if(name && phone && email) { 
                                                        $refs.carForm.submit(); 
                                                        triggerToast('{{ $lfSuccessMsg }}');
                                                    } else {
                                                        triggerToast('Please fill all fields to continue!');
                                                    }">
                                            {{ $lfFinalBtn }} &rarr;
                                        </button>
                                    </div>
                                </div>

                                <form action="{{ route('sell-car-lead') }}" method="POST" x-ref="carForm">
                                    @csrf
                                    <input type="hidden" name="make" :value="make">
                                    <input type="hidden" name="model" :value="model">
                                    <input type="hidden" name="year" :value="year">
                                    <input type="hidden" name="condition" :value="condition">
                                    <input type="hidden" name="name" :value="name">
                                    <input type="hidden" name="phone" :value="phone">
                                    <input type="hidden" name="email" :value="email">
                                    <input type="hidden" name="body" :value="body">
                                    <input type="hidden" name="engine" :value="engine">
                                    <input type="hidden" name="mileage" :value="mileage">
                                    <input type="hidden" name="trim" :value="trim">
                                    <input type="hidden" name="paint" :value="paint">
                                    <input type="hidden" name="gcc" :value="gcc">
                                    <input type="hidden" name="inspection_type" :value="inspectionType">
                                    <input type="hidden" name="inspection_date" id="final_inspection_date"
                                        :value="inspectionDate">
                                    <input type="hidden" name="inspection_time" id="final_inspection_time"
                                        :value="inspectionTime">
                                    <input type="hidden" name="home_address" :value="address">
                                </form>

                                {{-- Sync Script for Pickers --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const dInput = document.getElementById('carInspDate');
                                        const tInput = document.getElementById('carInspTime');
                                        if (dInput && tInput) {
                                            const observer = new MutationObserver(() => {
                                                // This will be caught by Alpine if we trigger an event or use a watcher
                                                window.dispatchEvent(new CustomEvent('sync-pickers', {
                                                    detail: { date: dInput.value, time: tInput.value }
                                                }));
                                            });
                                            observer.observe(dInput, { attributes: true });
                                            observer.observe(tInput, { attributes: true });

                                            // Also handle manual set
                                            setInterval(() => {
                                                window.dispatchEvent(new CustomEvent('sync-pickers', {
                                                    detail: { date: dInput.value, time: tInput.value }
                                                }));
                                            }, 1000);
                                        }
                                    });
                                </script>
                            </div>

                            {{-- ── PLATE TAB ── --}}
                            <div class="sc-body" x-show="heroWizardTab==='plate'" x-cloak
                                :style="heroWizardTab==='plate' ? 'display:block' : 'display:none'">
                                {{-- Plate Visualizer --}}
                                <div
                                    style="margin-bottom: 20px; position: relative; height: 90px; display: flex; align-items: center; justify-content: center;">
                                    <img :key="emirate" :src="plateFile" class="w-full h-full object-contain">

                                    <div class="absolute inset-0 pointer-events-none">
                                        {{-- 1. ABU DHABI --}}
                                        <template x-if="emirate === 'Abu Dhabi'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[5.5%] top-[48%] -translate-y-1/2 text-[2.4rem] font-bold text-white w-[18%] text-center"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[70%] top-[48%] -translate-x-1/2 -translate-y-1/2 text-[3.8rem] text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 2. DUBAI --}}
                                        <template x-if="emirate === 'Dubai'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[16%] top-[68%] -translate-x-1/2 -translate-y-1/2 text-[2.5rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[65%] top-[50%] -translate-x-1/2 -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.05em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 3. SHARJAH --}}
                                        <template x-if="emirate === 'Sharjah'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[10%] top-[55%] -translate-y-1/2 text-[3.8rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[71%] top-[55%] -translate-x-1/2 -translate-y-1/2 text-[4.2rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 4. AJMAN --}}
                                        <template x-if="emirate === 'Ajman'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[13%] top-[48%] -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[47%] top-[48%] -translate-x-1/2 -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 5. FUJAIRAH --}}
                                        <template x-if="emirate === 'Fujairah'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[11%] top-[48%] -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[71%] top-[48%] -translate-x-1/2 -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 6. UAQ --}}
                                        <template x-if="emirate === 'UAQ'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[11%] top-[48%] -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[71%] top-[48%] -translate-x-1/2 -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 7. RAK --}}
                                        <template x-if="emirate === 'RAK'">
                                            <div>
                                                <div x-text="plateCode"
                                                    class="absolute left-[11%] top-[48%] -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important;">
                                                </div>
                                                <div x-text="plate"
                                                    class="absolute left-[71%] top-[48%] -translate-x-1/2 -translate-y-1/2 text-[4.4rem] font-bold text-[#031629]"
                                                    style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.1em;">
                                                </div>
                                            </div>
                                        </template>

                                        {{-- 8. DEFAULT (OTHER) --}}
                                        <template
                                            x-if="!['Abu Dhabi','Dubai','Sharjah','Ajman','Fujairah','UAQ','RAK'].includes(emirate)">
                                            <div x-text="(plateCode === 'Blank' ? '' : plateCode) + ' ' + plate"
                                                class="absolute top-1/2 left-[58%] -translate-x-1/2 -translate-y-1/2 text-[2.6rem] font-normal text-[#031629]"
                                                style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.15em; text-shadow: 0 1px 2px rgba(255,255,255,0.9);">
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Step 1: Plate Details --}}
                                <div x-show="heroWizardStep===1">
                                    <p class="sc-label" style="color:#94a3b8; margin-bottom:8px">{{ $lfPlateSubtitle }}</p>
                                    <div
                                        style="display:grid; grid-template-columns:120px 1fr; gap:10px; margin-bottom:12px">
                                        <div style="position:relative">
                                            <button type="button"
                                                @click.stop="scCurrentField = (scCurrentField==='plateCode'?null:'plateCode')"
                                                class="sc-field w-full h-[54px] flex items-center justify-between px-4"
                                                :style="scCurrentField === 'plateCode' ? 'border-color:#ff6900; z-index:250' : 'z-index:10'">
                                                <span class="text-[1rem] font-black text-[#ff6900]"
                                                    x-text="plateCode"></span>
                                                <svg class="w-4 h-4 text-slate-400"
                                                    :class="scCurrentField === 'plateCode' ? 'rotate-180' : ''"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                            <div x-show="scCurrentField === 'plateCode'" @click.away="scCurrentField = null"
                                                class="hub-drawer"
                                                style="width: 280px; max-height: 300px; overflow-y: auto; padding: 12px; top: 100%; left: 0; transform: none; z-index: 10000;">
                                                <div class="grid grid-cols-4 gap-2">
                                                    <template x-for="code in availableCodes" :key="code">
                                                        <button type="button"
                                                            @click="plateCode = code; scCurrentField = null"
                                                            class="py-2.5 rounded-lg text-center text-[0.75rem] font-bold text-slate-700 hover:bg-slate-50 hover:text-orange-600 transition-all border border-transparent hover:border-slate-200"
                                                            x-text="code"></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="sc-field" style="margin-bottom:0; height:54px">
                                            <input type="tel" x-model="plate" placeholder="e.g. 12345" maxlength="5"
                                                class="w-full h-full bg-transparent border-none outline-none font-black text-[1rem] tracking-widest text-[#031629]"
                                                @input="plate = plate.replace(/[^0-9]/g, '').slice(0, 5)">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-x-2 gap-y-4 pt-4 mb-4">
                                        @foreach(['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'RAK', 'UAQ', 'Fujairah'] as $em)
                                            <button type="button"
                                                class="h-[42px] rounded-xl border text-[0.6rem] font-black uppercase transition-all"
                                                :class="emirate==='{{ $em }}' ? 'bg-[#ff6900] border-[#ff6900] text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400'"
                                                @click="emirate='{{ $em }}'; plateCode = availableCodes[0]">{{ $em }}</button>
                                        @endforeach
                                    </div>

                                    <button type="button" class="sc-btn w-full mt-4"
                                        @click="if(plate) { heroWizardStep=2 } else { triggerToast('Please enter your plate number first!') }">
                                        {{ $lfPlateBtn }} &rarr;
                                    </button>
                                </div>

                                {{-- Step 2: Contact/Service & Submit --}}
                                <div x-show="heroWizardStep===2" style="display:none">
                                    <div class="sc-summary">
                                        <div class="sc-summary-label">Selected Plate</div>
                                        <div class="sc-summary-val" x-text="emirate + ' ' + plateCode + ' ' + plate"></div>
                                        <div class="sc-summary-sub">Enter contact details and choose inspection service
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="text" x-model="name" placeholder="{{ $lfNameLabel }}"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="{{ $lfPhoneLabel }}"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="email" x-model="email" placeholder="Email Address"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                    </div>

                                    <form action="{{ route('sell-car-lead') }}" method="POST" x-ref="plateForm">
                                        @csrf
                                        <input type="hidden" name="make" :value="'Plate: ' + emirate">
                                        <input type="hidden" name="model" :value="plateCode">
                                        <input type="hidden" name="year" :value="plate">
                                        <input type="hidden" name="name" :value="name">
                                        <input type="hidden" name="phone" :value="phone">
                                        <input type="hidden" name="email" :value="email">
                                        <input type="hidden" name="inspection_type" value="direct_valuation">
                                        <input type="hidden" name="inspection_date" value="{{ now()->format('Y-m-d') }}">
                                        <input type="hidden" name="inspection_time" value="{{ now()->format('H:i') }}">
                                        <input type="hidden" name="lead_type" value="sell_plate">
                                    </form>

                                    <div style="display:flex; align-items:center; gap:10px; margin-top:30px;">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn w-full" style="flex:1" @click="if(plate && name && phone && email) {
                                                        $refs.plateForm.submit();
                                                        triggerToast('{{ $lfSuccessMsg }}');
                                                    } else {
                                                        triggerToast('Please complete your contact details!');
                                                    }">
                                            {{ $lfFinalBtn }} &rarr;
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>{{-- /hero-form-col --}}
                @endif

                {{-- Middle: Text --}}
                <div class="hero-text">
                    <span class="hero-eyebrow">{!! $h2Eyebrow !!}</span>
                    <h1 class="hero-title">{!! $h2Title !!}</h1>
                    <p class="hero-desc">{!! $h2Subtitle !!}</p>
                </div>

            </div>{{-- /hero-inner --}}

            {{-- Right: Car (absolute) --}}
            <div class="hero-car">
                @if($h2CirclesEnabled)
                    <div class="hero-car-decor">
                        <div class="decor-circle decor-1"></div>
                        <div class="decor-circle decor-2"></div>
                        <div class="decor-circle decor-3"></div>
                        <div class="decor-circle decor-4"></div>
                        <div class="decor-circle decor-5"></div>
                    </div>
                @endif

                <img src="{{ $h2CarImage }}" onerror="this.src='/images/cars/mclaren.png'" alt="Featured Car"
                    style="transform: scale({{ $h2CarScale }}) {{ $h2CarMirror ? 'scaleX(-1)' : '' }}; transform-origin: center bottom;">

            </div>{{-- /hero-car --}}

        </section>

        {{-- Trust badges: قسم مستقل في تدفق الصفحة (بدون absolute) + كاروسيل كرت واحد — يمنع تداخل الفوتر --}}
        @if($h2ShowMiddleSections && count($formattedBadges) > 0)
            @php
                $trustStripBg = data_get($page?->content, 'trust_strip_bg', '#f0f2f5');
            @endphp
            <section
                class="h2-trust-strip relative z-10 w-full"
                style="background-color: {{ $trustStripBg }};"
                aria-label="Trust badges">
                <div class="max-w-3xl mx-auto" x-data="{
                            trustIdx: 0,
                            trustCount: {{ count($formattedBadges) }},
                            next() { this.trustIdx = (this.trustIdx + 1) % this.trustCount },
                            prev() { this.trustIdx = (this.trustIdx - 1 + this.trustCount) % this.trustCount }
                         }">
                    <div
                        class="relative rounded-2xl bg-white shadow-[0_12px_40px_-12px_rgba(3,22,41,0.15)] border border-slate-200/60 p-6 md:p-8 min-h-[180px] flex items-center justify-center">
                        @foreach($formattedBadges as $index => $badge)
                            <div x-show="trustIdx === {{ $index }}"
                                class="w-full flex flex-col sm:flex-row items-center justify-center gap-6 sm:gap-10 text-center sm:text-left"
                                x-cloak>
                                <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex items-center justify-center shadow-md"
                                    style="background: {{ $badge['bg'] }}; color: {{ $badge['color'] }};">
                                    <i data-lucide="{{ $badge['icon'] }}" class="w-8 h-8" stroke-width="2.5"></i>
                                </div>
                                <div class="space-y-1 max-w-lg">
                                    <span
                                        class="block text-[#ff6900] text-[0.65rem] font-black uppercase tracking-[0.12em]">{{ $badge['main'] }}</span>
                                    @if($badge['sub'])
                                        <span
                                            class="block text-2xl md:text-3xl font-black text-[#031629]">{{ $badge['sub'] }}</span>
                                    @endif
                                    @if($badge['desc'])
                                        <span class="block text-sm text-slate-500">{{ $badge['desc'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-6">
                        <button type="button"
                            class="h-10 w-10 rounded-full border border-slate-200 bg-white shadow-sm flex items-center justify-center text-slate-600 hover:border-[#ff6900] hover:text-[#ff6900] transition"
                            @click="prev()" aria-label="Previous">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <div class="flex gap-2">
                            @foreach($formattedBadges as $i => $b)
                                <button type="button" @click="trustIdx = {{ $i }}" class="w-2.5 h-2.5 rounded-full transition"
                                    :class="trustIdx === {{ $i }} ? 'bg-[#ff6900]' : 'bg-slate-300'"
                                    aria-label="Slide {{ $i + 1 }}"></button>
                            @endforeach
                        </div>
                        <button type="button"
                            class="h-10 w-10 rounded-full border border-slate-200 bg-white shadow-sm flex items-center justify-center text-slate-600 hover:border-[#ff6900] hover:text-[#ff6900] transition"
                            @click="next()" aria-label="Next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </section>
        @endif

        @if($h2ShowMiddleSections)
            <div class="h2-main-below-hero flex flex-col w-full relative z-20">
                {{-- Google Reviews Highlight --}}
                @php
                    $reviewsConfig = $googleReviewBlock ?? [];
                    $showReviews = data_get($reviewsConfig, 'enabled') && count(data_get($reviewsConfig, 'reviews', []));
                    $reviews = data_get($reviewsConfig, 'reviews', []);
                @endphp
                @if($showReviews)
                    <section class="pt-24 pb-48 px-6 lg:px-12 bg-[#031629] relative overflow-hidden">
                        <div class="absolute inset-0 opacity-30"
                            style="background-image: radial-gradient(circle at top, rgba(255,255,255,0.08), transparent 55%), radial-gradient(circle at bottom, rgba(255,105,0,0.08), transparent 45%);">
                        </div>
                        <div class="relative max-w-[1440px] mx-auto space-y-12">
                            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                                <div class="max-w-full overflow-hidden lg:overflow-visible">
                                    <span
                                        class="inline-flex items-center gap-3 px-4 py-2 rounded-full text-[0.72rem] font-black uppercase tracking-[0.32em] bg-white/10 text-white border border-white/5 mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4.5 h-4.5">
                                            <path fill="#EA4335"
                                                d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.89-6.89C35.82 2.38 30.41 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.99 6.21C12.12 13.39 17.55 9.5 24 9.5z" />
                                            <path fill="#4285F4"
                                                d="M46.5 24.55c0-1.5-.13-2.94-.38-4.35H24v8.23h12.7c-.55 2.81-2.22 5.19-4.72 6.79l7.62 5.92C44.41 36.58 46.5 30.98 46.5 24.55z" />
                                            <path fill="#FBBC05"
                                                d="M10.55 28.43A14.38 14.38 0 0 1 9.5 24c0-1.52.26-2.99.75-4.36l-7.99-6.21A23.884 23.884 0 0 0 0 24c0 3.82.91 7.42 2.51 10.59l8.04-6.16z" />
                                            <path fill="#34A853"
                                                d="M24 47.5c6.11 0 11.24-2.01 14.99-5.46l-7.62-5.92c-2.12 1.42-4.8 2.25-7.37 2.25-5.64 0-10.42-3.79-12.45-9.02l-8.04 6.16C6.56 42.47 14.51 47.5 24 47.5z" />
                                        </svg>
                                        Google Reviews
                                    </span>
                                    <h2 class="text-4xl lg:text-6xl font-black text-white tracking-tight leading-[1.1]">
                                        {{ data_get($reviewsConfig, 'title', 'Loved by real buyers') }}
                                    </h2>
                                    <p class="mt-4 text-slate-400 font-bold text-sm tracking-wide max-w-xl">
                                        {{ data_get($reviewsConfig, 'subtitle', 'Straight from verified Google customers.') }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-start lg:items-end gap-3">
                                    <div
                                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white text-[#031629] font-black text-[0.75rem] uppercase tracking-[0.3em] shadow-lg">
                                        <i data-lucide="badge-check" class="w-4 h-4"></i>
                                        {{ data_get($reviewsConfig, 'badge', '4.9 / 5 • Google Reviews') }}
                                    </div>
                                    <p class="text-[0.6rem] text-slate-400 uppercase tracking-[0.35em]">Latest verified
                                        testimonials</p>
                                </div>
                            </div>

                            <div class="relative group" data-review-slider>
                                <div class="flex gap-6 overflow-x-auto pb-8 snap-x snap-mandatory no-scrollbar scroll-smooth"
                                    data-review-scroll>
                                    @foreach($reviews as $review)
                                        <div class="flex-shrink-0 w-full md:w-[450px] snap-center">
                                            <div
                                                class="bg-white rounded-[1.75rem] border border-slate-100 shadow-[0_20px_60px_-20px_rgba(3,22,41,0.3)] p-8 h-full flex flex-col gap-6">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="w-14 h-14 rounded-full bg-slate-100 overflow-hidden border-2 border-slate-50 shadow-sm flex items-center justify-center">
                                                            @if(data_get($review, 'profile_photo_url'))
                                                                <img src="{{ data_get($review, 'profile_photo_url') }}"
                                                                    class="w-full h-full object-cover">
                                                            @else
                                                                <span
                                                                    class="text-sm font-black text-slate-400">{{ strtoupper(substr(data_get($review, 'author_name', 'G'), 0, 1)) }}</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h4 class="font-bold text-slate-800 text-lg">
                                                                {{ data_get($review, 'author_name', 'Anonymous User') }}
                                                            </h4>
                                                            <span
                                                                class="text-[0.62rem] font-black uppercase tracking-widest text-[#ff6900]">{{ data_get($review, 'relative_time_description', 'Recent') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 rounded-full">
                                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500"></i>
                                                        <span
                                                            class="text-[0.6rem] font-black uppercase tracking-widest text-emerald-600">Verified</span>
                                                    </div>
                                                </div>
                                                <div class="flex gap-1">
                                                    @for($i = 0; $i < 5; $i++)
                                                        <i data-lucide="star" class="w-4 h-4 fill-[#ff6900] text-[#ff6900]"></i>
                                                    @endfor
                                                </div>
                                                <p class="text-slate-600 font-medium leading-relaxed text-[0.95rem]">
                                                    "{{ data_get($review, 'text', 'No review text provided.') }}"
                                                </p>
                                                <div
                                                    class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                                                    <a href="https://google.com/search?q=motorbazar+reviews" target="_blank"
                                                        class="text-[0.65rem] font-black uppercase tracking-widest text-slate-400 hover:text-[#ff6900] transition-colors flex items-center gap-2">
                                                        Read More <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Navigation Buttons --}}
                                <div
                                    class="absolute top-1/2 -translate-y-1/2 -left-4 -right-4 flex justify-between pointer-events-none">
                                    <button data-review-prev
                                        class="w-12 h-12 bg-white rounded-full shadow-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#ff6900] transition-all pointer-events-auto opacity-0 group-hover:opacity-100 -translate-x-4 group-hover:translate-x-0">
                                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                                    </button>
                                    <button data-review-next
                                        class="w-12 h-12 bg-white rounded-full shadow-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#ff6900] transition-all pointer-events-auto opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0">
                                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                @else
                    <section class="py-24 px-6 lg:px-12 bg-slate-100 relative z-20 text-center">
                        <div class="max-w-3xl mx-auto space-y-4">
                            <span
                                class="inline-flex items-center justify-center px-3 py-1 rounded-full text-[0.6rem] font-black uppercase tracking-[0.3em] text-slate-500 bg-white shadow">Google
                                Reviews Placeholder</span>
                            <h2 class="text-3xl font-black text-slate-800">This is a placeholder message confirming the reviews
                                block renders.</h2>
                            <p class="text-slate-500 text-sm">Once Google Reviews are enabled and configured, this placeholder
                                will be replaced by the live testimonials card.</p>
                        </div>
                    </section>
                @endif

                {{-- Body Type Browser: Dynamic CMS Sync --}}
                <section
                    class="block w-full clear-both bg-white relative z-30 py-24 px-6 lg:px-12 border-t border-slate-100 shadow-sm">
                    <div class="max-w-[1440px] mx-auto">
                        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-10">
                            <div>
                                <span
                                    class="text-[#ff6900] font-black uppercase tracking-[0.35em] text-[0.65rem] mb-3 block">Browse
                                    by category</span>
                                <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-[#031629]">Search cars by
                                    body type</h2>
                            </div>
                            <a href="{{ route('auctions.index') }}"
                                class="text-[#031629] font-black text-xs uppercase tracking-[0.22em] border-b-2 border-[#ff6900] pb-1 w-fit">View
                                all inventory</a>
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
                                <div class="body-type-card"
                                    onclick="window.location.href='{{ route('auctions.index', ['body_type' => $type['slug']]) }}'">
                                    <i data-lucide="{{ $type['icon'] ?? 'car' }}"
                                        class="w-10 h-10 mx-auto mb-4 text-[#ff6900]"></i>
                                    <span
                                        class="text-sm font-black uppercase tracking-widest text-[#031629]">{{ $type['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Live Auctions: Direct Migration from Home 1 --}}
                @php
                    $showLiveAuctions = $featuredAuctions->isNotEmpty();
                @endphp
                @if($showLiveAuctions)
                    <section
                        class="block w-full clear-both bg-white relative z-40 py-32 px-6 lg:px-12 border-t border-slate-100 min-h-[600px]"
                        id="live-auctions">
                        <div class="mx-auto max-w-[1440px]">
                            <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-16 gap-6">
                                <div>
                                    <span
                                        class="text-[0.65rem] font-black uppercase tracking-[0.35em] text-[#ff6900] block mb-3">Live
                                        Now</span>
                                    <h2 class="text-4xl lg:text-5xl font-black text-[#031629] tracking-tighter leading-none">
                                        Active <span class="text-[#ff6900]">Auctions</span>
                                    </h2>
                                    <p class="text-slate-400 font-bold text-sm mt-3">Real-time bidding — prices update live</p>
                                </div>
                                <a href="{{ route('auctions.index') }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#031629] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-black transition-all">
                                    View All <i data-lucide="arrow-right" class="w-4 h-4 text-[#ff6900]"></i>
                                </a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                @foreach($featuredAuctions as $fa)
                                    @php
                                        $faImgs = optional($fa->car)->photos;
                                        $faImg = is_string($faImgs) ? (json_decode($faImgs, true)[0] ?? null) : ($faImgs[0] ?? null);
                                        $faImageUrl = $faImg ? asset('storage/' . $faImg) : '/images/cars/navy-mclaren.png';
                                        $isLive = $fa->status === 'active';
                                        $faPrice = $fa->current_price ?? $fa->initial_price;
                                    @endphp
                                    <a href="{{ route('auctions.show', $fa) }}"
                                        class="group block bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                                        <div class="relative h-64 bg-slate-50 overflow-hidden">
                                            <img src="{{ $faImageUrl }}"
                                                alt="{{ optional($fa->car)->make }} {{ optional($fa->car)->model }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                            <div class="absolute top-4 left-4">
                                                @if($isLive)
                                                    <span
                                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[0.6rem] font-black uppercase tracking-widest text-emerald-600 shadow-lg">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Now
                                                    </span>
                                                @else
                                                    <span
                                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[0.6rem] font-black uppercase tracking-widest text-[#ff6900] shadow-lg">
                                                        <span class="w-2 h-2 rounded-full bg-[#ff6900] animate-pulse"></span> Coming
                                                        Soon
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="absolute top-4 right-4">
                                                <span
                                                    class="px-3 py-1.5 bg-[#031629]/90 text-white rounded-full text-[0.6rem] font-black tracking-widest backdrop-blur-sm">
                                                    {{ $fa->bids_count }} Bids
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-8">
                                            <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 mb-2">
                                                {{ optional($fa->car)->year }} · Ref: {{ $fa->reference_code }}
                                            </div>
                                            <h3 class="text-2xl font-black text-[#031629] tracking-tight leading-tight mb-6">
                                                {{ optional($fa->car)->make }} {{ optional($fa->car)->model }}
                                            </h3>
                                            <div class="flex items-end justify-between pt-4 border-t border-slate-50">
                                                <div>
                                                    <div
                                                        class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-1">
                                                        {{ $isLive ? 'Current Bid' : 'Starting Price' }}
                                                    </div>
                                                    <div class="text-2xl font-black text-[#031629]">
                                                        ${{ number_format($faPrice, 0) }}</div>
                                                </div>
                                                <div class="text-right">
                                                    <div
                                                        class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest mb-1">
                                                        {{ $isLive ? 'Ends In' : 'Opens In' }}
                                                    </div>
                                                    <div class="text-sm font-black text-[#ff6900] tabular-nums auction-timer"
                                                        data-expires="{{ $isLive ? $fa->end_at?->toIso8601String() : $fa->start_at?->toIso8601String() }}">
                                                        --:--:--</div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="mt-16 flex justify-center lg:hidden">
                                <a href="{{ route('auctions.index') }}"
                                    class="px-8 py-4 bg-[#031629] text-white rounded-2xl font-black text-[0.7rem] uppercase tracking-widest flex items-center gap-3">
                                    View All Inventory <i data-lucide="arrow-right" class="w-4 h-4 text-[#ff6900]"></i>
                                </a>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        @endif

        {{-- Professional Footer: CMS Controlled --}}
        @php
            $footerColor = data_get($page?->content, 'footer.background_color', '#eef3f9');
            $footerDesc = data_get($page?->content, 'footer.description', "The world's most trusted platform for premium car auctions. We bring the auction room to your screen with transparency and class.");
            $footerAddress = data_get($page?->content, 'footer.address', '123 Luxury Drive, Dubai, UAE');
            $footerEmail = data_get($page?->content, 'footer.email', 'contact@motorbazar.com');
            $footerPhone = data_get($page?->content, 'footer.phone', '+971 4 000 0000');
            $footerCopy = data_get($page?->content, 'footer.copyright', '&copy; ' . date('Y') . ' MOTOR BAZAR. ALL RIGHTS RESERVED.');
            $footerTerms = data_get($page?->content, 'footer.terms_url', '#');
            $footerPrivacy = data_get($page?->content, 'footer.privacy_url', '#');
            $footerCookies = data_get($page?->content, 'footer.cookies_url', '#');
            $footerQuickLinks = data_get($page?->content, 'footer.quick_links', [
                ['label' => 'Home', 'url' => '/'],
                ['label' => 'Browse Auctions', 'url' => route('auctions.index')],
                ['label' => 'How it Works', 'url' => route('how-it-works')],
                ['label' => 'Sell Your Car', 'url' => '#'],
            ]);
            $footerPages = data_get($page?->content, 'footer.pages', []);
        @endphp

        {{-- فوتر مبسّط للتشخيص: بدون blur، بدون ظلال معقّدة، خلفية صلبة + z-index عالٍ --}}
        <footer id="h2-footer-root" class="h2-footer-root block w-full" role="contentinfo">
            <div class="max-w-6xl mx-auto px-5 sm:px-8 py-14">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
                    <div class="space-y-4">
                        <p class="text-lg font-black uppercase tracking-tight text-white">
                            Motor <span class="text-[#ff6900]">Bazar</span>
                        </p>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-sm">{{ $footerDesc }}</p>
                        @if(count($footerSocials))
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach($footerSocials as $fsk => $fsurl)
                                    <a href="{{ $fsurl }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-[#1e293b] text-slate-300 hover:bg-[#ff6900] hover:text-white transition-colors">
                                        <i data-lucide="{{ $fsk === 'x' ? 'twitter' : 'share-2' }}" class="w-4 h-4"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-[#ff6900] mb-4">Navigation
                        </p>
                        <ul class="space-y-2 text-sm font-semibold">
                            @foreach($footerQuickLinks as $link)
                                <li><a href="{{ data_get($link, 'url', '#') }}">{{ data_get($link, 'label', '') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-[#ff6900] mb-4">Resources
                        </p>
                        <ul class="space-y-2 text-sm font-semibold">
                            @foreach($footerPages as $pg)
                                <li><a href="{{ data_get($pg, 'url', '#') }}">{{ data_get($pg, 'label', '') }}</a></li>
                            @endforeach
                            <li><a href="{{ $footerTerms }}">Terms</a></li>
                        </ul>
                    </div>
                    <div class="rounded-xl border border-slate-700 bg-[#111c2e] p-5 lg:p-6">
                        <p class="text-[0.65rem] font-black uppercase tracking-[0.25em] text-[#ff6900] mb-4">Contact</p>
                        <ul class="space-y-4 text-sm">
                            @if($footerPhone)
                                <li>
                                    <span
                                        class="block text-[0.6rem] uppercase tracking-wider text-slate-500 mb-0.5">Phone</span>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $footerPhone) }}"
                                        class="font-bold text-white">{{ $footerPhone }}</a>
                                </li>
                            @endif
                            @if($footerEmail)
                                <li>
                                    <span
                                        class="block text-[0.6rem] uppercase tracking-wider text-slate-500 mb-0.5">Email</span>
                                    <a href="mailto:{{ $footerEmail }}"
                                        class="font-bold text-white break-all">{{ $footerEmail }}</a>
                                </li>
                            @endif
                            @if($footerAddress)
                                <li class="text-slate-400 text-xs leading-relaxed">{{ $footerAddress }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div
                    class="mt-12 pt-8 border-t border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-[0.65rem] text-slate-500 uppercase tracking-wider font-bold">
                    <div>{!! $footerCopy !!}</div>
                    <div class="flex items-center gap-2 text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        SSL
                    </div>
                </div>
            </div>
        </footer>
    </main>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            if (window.initCountdowns) {
                window.initCountdowns();
            }

            /* ═══════════════════════════════════════════════════
               HOME2 SELL WIZARD (REMOVED)
            ═══════════════════════════════════════════════════ */

            // Google Review Slider Script
            const slider = document.querySelector('[data-review-slider]');
            if (slider) {
                const scrollBox = slider.querySelector('[data-review-scroll]');
                const prevBtn = slider.querySelector('[data-review-prev]');
                const nextBtn = slider.querySelector('[data-review-next]');

                const updateState = () => {
                    if (!scrollBox || !prevBtn || !nextBtn) return;
                    const maxScroll = scrollBox.scrollWidth - scrollBox.clientWidth;
                    const left = scrollBox.scrollLeft;
                    prevBtn.disabled = left <= 0;
                    nextBtn.disabled = left >= maxScroll - 5;
                    prevBtn.style.opacity = prevBtn.disabled ? '0.2' : '1';
                    nextBtn.style.opacity = nextBtn.disabled ? '0.2' : '1';
                };

                const scrollByViewport = (direction = 1) => {
                    const amount = scrollBox.clientWidth * direction * 0.8;
                    scrollBox.scrollBy({ left: amount, behavior: 'smooth' });
                };

                if (prevBtn) prevBtn.addEventListener('click', () => scrollByViewport(-1));
                if (nextBtn) nextBtn.addEventListener('click', () => scrollByViewport(1));
                if (scrollBox) scrollBox.addEventListener('scroll', updateState, { passive: true });
                window.addEventListener('resize', updateState);
                updateState();
            }
        });
    </script>
</body>

</html>
