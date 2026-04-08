<?php
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
    $h2CarRight = data_get($heroContent, 'car_right', -20);
    $h2CarTop = data_get($heroContent, 'car_top', 50);
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

    $parseList = function($str, $sep = "\n") {
        if (!$str) return [];
        return array_filter(array_map('trim', explode($sep, $str)));
    };

    $lfBodyOptions = $parseList(data_get($lfContent, 'step2.body_options'));
    if (empty($lfBodyOptions)) $lfBodyOptions = ['Sedan','SUV','Crossover','Coupe','Convertible','Hatchback','Van','Pickup'];
    
    $lfEngineOptions = $parseList(data_get($lfContent, 'step2.engine_options'), ',');
    if (empty($lfEngineOptions)) $lfEngineOptions = ['1.0L','1.2L','1.4L','1.6L','1.8L','2.0L','2.4L','3.0L','4.0L','5.0L','6.0L','Other'];

    $lfMileageOptions = $parseList(data_get($lfContent, 'step2.mileage_options'));
    if (empty($lfMileageOptions)) $lfMileageOptions = ['Up to 10,000 KM','Up to 30,000 KM','Up to 60,000 KM','Up to 100,000 KM','Up to 150,000 KM','More than 200,000 KM'];

    $lfSpecsOptions = $parseList(data_get($lfContent, 'step2.specs_options'));
    if (empty($lfSpecsOptions)) $lfSpecsOptions = ['GCC Specs','American Specs','Japanese Specs','European Specs','Other'];

    $lfPaintOptions = $parseList(data_get($lfContent, 'step2.paint_options'));
    if (empty($lfPaintOptions)) $lfPaintOptions = ['Original Paint','1-2 Panels Repaint','Total Repaint','Unknown'];

    $lfTrimOptions = $parseList(data_get($lfContent, 'step2.trim_options'));
    if (empty($lfTrimOptions)) $lfTrimOptions = ['Basic','Mid','Full','Unknown'];

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(strip_tags($h2Title)); ?> — <?php echo e(\App\Models\SystemSetting::get('site_name', 'Motor Bazar')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&family=Saira+Condensed:wght@700;800;900&family=Bebas+Neue&display=swap"
        rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        :root {
            --h2-nav-bg: <?php echo e($navBgColor); ?>;
            --h2-nav-txt: <?php echo e($navTxtColor); ?>;
            --h2-nav-dot: <?php echo e($navDotColor); ?>;
            --h2-nav-pos: <?php echo e($navSticky ? 'sticky' : 'relative'); ?>;
            --h2-nav-top: <?php echo e($navSticky ? '0' : 'auto'); ?>;
            --h2-nav-z: 1000;
            --h2-hero-form-width: <?php echo e($lfHeroWidth); ?>px;
        }

        .nav-logo img {
            transform: scale(<?php echo e($navLogoScale); ?>);
            transform-origin: left center;
        }

        body {
            background: #e7e7e7 !important;
        }

        .hero {
            <?php echo $heroStyleFinal; ?>

            height: calc(100vh - 80px) !important;
            min-height: 700px !important;
            display: flex !important;
            align-items: flex-start !important;
        }

        .hero-inner {
            width: 100% !important;
            max-width: 1440px !important;
            margin: 0 auto !important;
            padding: 120px 4% 0 !important;
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
            right: <?php echo e($h2CarRight); ?>% !important;
            top: <?php echo e($h2CarTop); ?>% !important;
            transform: translateY(-50%) !important;
            width: 70% !important;
            z-index: 0 !important;
            pointer-events: none !important;
            opacity: 0.8 !important;
        }

        .sc-pill-container {
            position: absolute !important;
            bottom: 30px !important;
            left: 0 !important;
            right: 0 !important;
            display: flex !important;
            justify-content: center !important;
            z-index: 120 !important;
        }

        .sc-pill-stats {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            border-radius: 100px !important;
            padding: 12px 48px !important;
            display: flex !important;
            align-items: center !important;
            gap: 60px !important;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.06) !important;
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
            margin: 0 !important;
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
            0% { transform: translateX(-150%) rotate(30deg); }
            100% { transform: translateX(150%) rotate(30deg); }
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
            margin: 0 !important;
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
    </style>
</head>

<body x-data>

    
    <nav class="nav">

        
        <div class="nav-left">
            <a href="<?php echo e(route('home2')); ?>" class="nav-logo">
                <?php
                    $siteLogo = \App\Models\SystemSetting::get('site_logo');
                    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
                ?>
                <?php if($siteLogoUrl): ?>
                    <img src="<?php echo e($siteLogoUrl); ?>" alt="<?php echo e(\App\Models\SystemSetting::get('site_name', 'Motor Bazar')); ?>"
                        style="height:70px;width:auto;object-fit:contain">
                <?php else: ?>
                    <div class="nav-dots">
                        <div class="nav-dot big"></div>
                        <div class="nav-dot sm"></div>
                    </div>
                    <span class="nav-brand"><?php echo e(\App\Models\SystemSetting::get('site_name', 'Motor Bazar')); ?></span>
                <?php endif; ?>
            </a>

            
            <div class="nav-pill">
                <a href="<?php echo e(route('home2')); ?>" class="nav-pill-item active" title="Home">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
                <div class="nav-pill-sep"></div>
                <a href="<?php echo e(route('auctions.index')); ?>" class="nav-pill-item" title="Auctions">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                </a>
                <?php if($navPhone): ?>
                    <div class="nav-pill-sep"></div>
                    <a href="tel:<?php echo e($navPhone); ?>" class="nav-pill-item" title="<?php echo e($navPhone); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                        </svg>
                    </a>
                <?php endif; ?>
                <?php if($navWhatsapp): ?>
                    <div class="nav-pill-sep"></div>
                    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $navWhatsapp)); ?>" target="_blank"
                        class="nav-pill-item" title="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                        </svg>
                    </a>
                <?php endif; ?>
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

        
        <div class="nav-center">
            <?php $__currentLoopData = $navMenuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($item->url); ?>" class="nav-menu-link"><?php echo e($item->label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="nav-right">

            
            <div class="nav-search">
                <input type="text" placeholder="Search..."
                    onkeydown="if(event.key==='Enter') window.location='<?php echo e(route('auctions.index')); ?>?search='+encodeURIComponent(this.value)">
                <button class="nav-search-btn"
                    onclick="window.location='<?php echo e(route('auctions.index')); ?>?search='+encodeURIComponent(this.previousElementSibling.value)">
                    Go
                </button>
            </div>

            
            <?php if(auth()->guard()->check()): ?>
                <div style="position:relative" x-data="{open:false}">
                    <button @click="open=!open"
                        style="width:40px;height:40px;border-radius:50%;border:2px solid #ff6900;overflow:hidden;cursor:pointer;background:#f1f5f9;flex-shrink:0;display:flex;align-items:center;justify-content:center;padding:0">
                        <?php if(auth()->user()->profile_photo_path): ?>
                            <img src="<?php echo e(Storage::url(auth()->user()->profile_photo_path)); ?>"
                                style="width:100%;height:100%;object-fit:cover" alt="">
                        <?php else: ?>
                            <span
                                style="font-size:.7rem;font-weight:900;color:#031629"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?></span>
                        <?php endif; ?>
                    </button>
                    <div x-show="open" @click.outside="open=false" x-cloak
                        style="position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #e8ecf0;border-radius:0.75rem;box-shadow:0 16px 40px rgba(3,22,41,.12);min-width:180px;padding:8px;z-index:999">
                        <div style="padding:10px 12px 8px;border-bottom:1px solid #f1f5f9;margin-bottom:4px">
                            <p style="font-size:.72rem;font-weight:800;color:#031629"><?php echo e(auth()->user()->name); ?></p>
                            <p style="font-size:.62rem;color:#94a3b8"><?php echo e(auth()->user()->email); ?></p>
                        </div>
                        <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>"
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
                        <?php endif; ?>
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
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
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
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>"
                    style="display:flex;align-items:center;gap:7px;height:40px;padding:0 18px;background:#031629;color:#fff;border-radius:0.75rem;font-size:.65rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;transition:background .2s"
                    onmouseover="this.style.background='#ff6900'" onmouseout="this.style.background='#031629'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24"
                        stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    Sign In
                </a>
            <?php endif; ?>

        </div>

    </nav>

    
    <div class="social-bar">
        <a href="#" title="Account">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </a>
        <a href="#" title="Explore">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
            </svg>
        </a>
        <a href="#" title="Share">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
            </svg>
        </a>
    </div>

    
    <main>
        <section class="hero">
            <div class="hero-inner">

                <?php if($lfShowHero): ?>
                    <div class="hero-form-col">
                        
                        <?php
                            $h2Models = $catalogModelsByMake ?? [];
                        ?>
                        <textarea id="h2-models-data" style="display:none"><?php echo json_encode($h2Models, 15, 512) ?></textarea>
                        <div class="search-card" x-cloak x-data="{
                            allModels: JSON.parse(document.getElementById('h2-models-data')?.value || '{}'),
                            heroWizardTab: 'car',
                            heroWizardStep: 1,
                            name: '', phone: '', email: '',
                            make: '', model: '', year: '', condition: 'good',
                            body: '', engine: '', mileage: '', trim: 'Full option', paint: 'Original', gcc: 'GCC',
                            inspectionDate: '<?php echo e(now()->addDays(1)->format('Y-m-d')); ?>', inspectionTime: '10:00',
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

                            
                            <div class="sc-header" style="margin-bottom: 25px;">
                                <p class="sc-label"
                                    style="margin-bottom: 4px !important; color: #ff6900; font-size: 0.7rem; font-weight: 1000; letter-spacing: 0.1em;">
                                    <?php echo e($lfHeaderLabel); ?></p>
                                <h3 class="sc-title"
                                    style="margin-top: 0 !important; font-size: 1.35rem; font-weight: 900; line-height: 1.1; color: #031629;">
                                    <?php echo $lfHeaderTitle; ?></h3>
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
                                        style="font-size: 0.85rem; font-weight: 1000; letter-spacing: 0.05em;"><?php echo e($lfTabCar); ?></span>
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
                                        style="font-size: 0.85rem; font-weight: 1000; letter-spacing: 0.05em;"><?php echo e($lfTabPlate); ?></span>
                                </button>
                            </div>

                            
                            <div class="sc-body" x-show="heroWizardTab==='car'"
                                :style="heroWizardTab==='car' ? 'display:block' : 'display:none'">

                                
                                <div x-show="heroWizardStep === 1">
                                    <p class="sc-label"
                                        style="color:#94a3b8; font-size:0.55rem; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.1em;">
                                        Popular Brands</p>
                                    <div class="grid grid-cols-3 gap-3" style="margin-bottom: 30px !important;">
                                        <?php $__currentLoopData = array_slice($brandCardBrands2, 0, 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="sc-brand-item" @click="make='<?php echo e($fb['name']); ?>'; scCurrentField='model'"
                                                :style="make === '<?php echo e($fb['name']); ?>' ? 'border-color:#ff6900; background:#fff9f5;' : ''">
                                                <img src="<?php echo e($fb['logo']); ?>" alt="<?php echo e($fb['name']); ?>"
                                                    :style="make === '<?php echo e($fb['name']); ?>' ? 'filter:grayscale(0); opacity:1;' : ''">
                                                <span
                                                    :style="make === '<?php echo e($fb['name']); ?>' ? 'color:#031629' : ''"><?php echo e($fb['name']); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <span x-text="make || '<?php echo e($lfBrandLabel); ?>'"></span>
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
                                                    <?php $__currentLoopData = $brandSelectBrands2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $bKey = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                                        ?>
                                                        <button type="button"
                                                            x-show="'<?php echo e($bKey); ?>'.includes(search.toLowerCase())"
                                                            @click="make = '<?php echo e($brand['name']); ?>'; scCurrentField = null"
                                                            class="w-full flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-slate-50 group"
                                                            :class="make === '<?php echo e($brand['name']); ?>' ? 'bg-orange-50 border border-orange-100' : 'border border-transparent'">
                                                            <div
                                                                class="w-10 h-10 rounded-lg bg-white shadow-sm flex items-center justify-center p-1.5 border border-slate-100 group-hover:border-orange-200">
                                                                <img src="<?php echo e($brand['logo']); ?>" alt=""
                                                                    class="w-full h-full object-contain">
                                                            </div>
                                                            <span class="text-[0.85rem] font-bold text-[#031629]"
                                                                :class="make === '<?php echo e($brand['name']); ?>' ? 'text-orange-600' : ''"><?php echo e($brand['name']); ?></span>
                                                        </button>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                            <span x-text="model || '<?php echo e($lfModelLabel); ?>'"></span>
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
                                            <span x-text="year || '<?php echo e($lfYearLabel); ?>'"></span>
                                        </button>
                                        <div x-show="scCurrentField === 'year'" @click.away="scCurrentField = null"
                                            class="hub-drawer">
                                            <div class="grid grid-cols-4 gap-1.5 max-h-60 overflow-y-auto">
                                                <?php $__currentLoopData = $h2Years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <button type="button" @click="year = '<?php echo e($y); ?>'; scCurrentField = null"
                                                        class="hub-option justify-center"><?php echo e($y); ?></button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-5 relative">
                                        
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

                                
                                <div x-show="heroWizardStep === 2">
                                    <div class="grid grid-cols-2 gap-8 items-start">
                                        
                                        <div class="space-y-6">
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfBodyLabel); ?></span>
                                                <div class="sc-field mt-1" style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="body" class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfBodyLabel); ?></option>
                                                        <?php $__currentLoopData = $lfBodyOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($opt); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfEngineLabel); ?></span>
                                                <div class="sc-field mt-1" style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="engine" class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfEngineLabel); ?></option>
                                                        <?php $__currentLoopData = $lfEngineOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eng): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($eng); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfMileageLabel); ?></span>
                                                <div class="sc-field mt-1" style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="mileage" class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfMileageLabel); ?></option>
                                                        <?php $__currentLoopData = $lfMileageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($mile); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfSpecsLabel); ?></span>
                                                <div class="sc-field mt-1" style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="gcc" class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfSpecsLabel); ?></option>
                                                        <?php $__currentLoopData = $lfSpecsOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($spec); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="space-y-6">
                                            <div>
                                                <span class="field-lbl">Trim Option</span>
                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                    <template x-for="opt in <?php echo e(json_encode($lfTrimOptions)); ?>">
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
                                                    <template x-for="p in <?php echo e(json_encode($lfPaintOptions)); ?>">
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

                                    <div style="display:flex; align-items:center; justify-content:between; pt-8 border-t border-slate-100 mt-6 gap-3">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn flex-1"
                                            @click="heroWizardStep=3">
                                            CONTINUE TO BOOKING &rarr;
                                        </button>
                                    </div>
                                </div>

                                
                                <div x-show="heroWizardStep === 3" x-cloak>
                                    
                                    <p class="sc-label" style="color:#94a3b8; font-size:0.55rem; margin-bottom:12px; text-transform:uppercase; letter-spacing:0.1em;">Where should we meet?</p>
                                    <div class="grid grid-cols-2 gap-4" style="margin-bottom:25px;">
                                        <button type="button" @click="inspectionType='branch'"
                                            class="group relative flex flex-col items-center justify-center gap-1.5 border-2 rounded-2xl transition-all p-2 h-[75px]"
                                            :style="inspectionType === 'branch' ? 'border-color:#ff6900; background:#fff7f0; color:#ff6900; box-shadow:0 10px 30px -10px rgba(255,105,0,0.2)' : 'border-color:#eef2f6; background:#f8fafc; color:#64748b;'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="10" width="20" height="12" rx="2" ry="2"/><path d="m12 10 4.46-4.46a2 2 0 0 0-2.82-2.82L9 7.32"/><path d="m7 10 4.46-4.46a2 2 0 0 0-2.82-2.82L4 7.32"/>
                                            </svg>
                                            <span style="font-size: 0.6rem; font-weight: 1000; text-transform: uppercase; letter-spacing: 0.05em;">Visit Branch</span>
                                            <div x-show="inspectionType === 'branch'" class="absolute -top-1.5 -right-1.5 bg-[#ff6900] text-white p-0.5 rounded-full shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                            </div>
                                        </button>
                                        <button type="button" @click="inspectionType='home'"
                                            class="group relative flex flex-col items-center justify-center gap-1.5 border-2 rounded-2xl transition-all p-2 h-[75px]"
                                            :style="inspectionType === 'home' ? 'border-color:#ff6900; background:#fff7f0; color:#ff6900; box-shadow:0 10px 30px -10px rgba(255,105,0,0.2)' : 'border-color:#eef2f6; background:#f8fafc; color:#64748b;'">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                                            </svg>
                                            <span style="font-size: 0.6rem; font-weight: 1000; text-transform: uppercase; letter-spacing: 0.05em;">Home Service</span>
                                            <div x-show="inspectionType === 'home'" class="absolute -top-1.5 -right-1.5 bg-[#ff6900] text-white p-0.5 rounded-full shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                            </div>
                                        </button>
                                    </div>
 
                                    
                                    <div x-data="{ 
                                        mapUrl: 'https://maps.googleapis.com/maps/api/staticmap?center=25.07,55.15&zoom=12&size=600x400&scale=2&style=feature:all|element:labels|visibility:off&style=feature:geometry|color:0xf5f5f5&style=feature:water|color:0xc9c9c9&key=<?php echo e(\App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'))); ?>',
                                        isLocating: false,
                                        locate() {
                                            this.isLocating = true;
                                            if (navigator.geolocation) {
                                                navigator.geolocation.getCurrentPosition((position) => {
                                                    const lat = position.coords.latitude;
                                                    const lng = position.coords.longitude;
                                                    address = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                                                    this.mapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lng}&zoom=15&size=600x400&scale=2&markers=color:orange|${lat},${lng}&key=<?php echo e(\App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'))); ?>`;
                                                    this.isLocating = false;
                                                });
                                            }
                                        }
                                    }" style="height:270px; position:relative; border-radius:1.5rem; overflow:hidden; border:4px solid #fff; box-shadow:0 25px 60px -20px rgba(3,22,41,0.2); margin-bottom:30px;">
                                        <img :src="mapUrl" class="w-full h-full object-cover grayscale opacity-80 transition-all duration-700">
                                        
                                        
                                        <div class="absolute top-4 left-4 right-4 group">
                                            <div class="bg-white/90 backdrop-blur-md rounded-xl p-1 shadow-2xl border border-white/50 flex items-center transition-all group-hover:bg-white">
                                                <div class="w-9 h-9 flex items-center justify-center text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                                </div>
                                                <input type="text" x-model="address" placeholder="Search for location..." class="flex-1 bg-transparent border-none outline-none text-[0.75rem] font-bold text-[#031629] py-2">
                                                <button type="button" class="w-8 h-8 rounded-lg bg-[#ff6900] text-white flex items-center justify-center shadow-lg mr-1 hover:scale-105 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                                </button>
                                            </div>
                                        </div>
 
                                        
                                        <div class="absolute bottom-4 right-4" x-data="{ pulse: false }">
                                            <button type="button" @click="locate()" :class="isLocating ? 'animate-pulse' : ''" class="w-10 h-10 bg-white shadow-xl rounded-xl flex items-center justify-center text-slate-600 hover:text-orange-600 transition-all border border-slate-100">
                                                <svg x-show="!isLocating" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                                <svg x-show="isLocating" class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                                            </button>
                                        </div>
 
                                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none" x-show="!isLocating">
                                            <div class="w-12 h-12 bg-[#ff6900] rounded-full border-4 border-white shadow-2xl flex items-center justify-center animate-bounce">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
 
                                    
                                    <div style="margin-bottom:30px;">
                                        <?php if (isset($component)) { $__componentOriginalf4891fa44f09df093b640787c7c16efe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf4891fa44f09df093b640787c7c16efe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-picker','data' => ['dateId' => 'carInspDate','timeId' => 'carInspTime','dateName' => 'inspection_date','timeName' => 'inspection_time']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateId' => 'carInspDate','timeId' => 'carInspTime','dateName' => 'inspection_date','timeName' => 'inspection_time']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf4891fa44f09df093b640787c7c16efe)): ?>
<?php $attributes = $__attributesOriginalf4891fa44f09df093b640787c7c16efe; ?>
<?php unset($__attributesOriginalf4891fa44f09df093b640787c7c16efe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf4891fa44f09df093b640787c7c16efe)): ?>
<?php $component = $__componentOriginalf4891fa44f09df093b640787c7c16efe; ?>
<?php unset($__componentOriginalf4891fa44f09df093b640787c7c16efe); ?>
<?php endif; ?>
                                    </div>
 
                                    
                                    <div class="grid grid-cols-2 gap-3 mb-6" style="margin-top:30px;">
                                        <div class="col-span-2">
                                            <div class="sc-field" style="height:52px; background:#f8fafc;">
                                                <input type="text" x-model="name" placeholder="<?php echo e($lfNameLabel); ?>" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                            </div>
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="<?php echo e($lfPhoneLabel); ?>" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="email" x-model="email" placeholder="Email Address" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:10px; margin-top:20px;">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn flex-1" 
                                            @click="if(name && phone && email) { 
                                                $refs.carForm.submit(); 
                                                triggerToast('<?php echo e($lfSuccessMsg); ?>');
                                            } else {
                                                triggerToast('Please fill all fields to continue!');
                                            }">
                                            <?php echo e($lfFinalBtn); ?> &rarr;
                                        </button>
                                    </div>
                                </div>

                                    <form action="<?php echo e(route('sell-car-lead')); ?>" method="POST" x-ref="carForm">
                                        <?php echo csrf_field(); ?>
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
                                        <input type="hidden" name="inspection_date" id="final_inspection_date" :value="inspectionDate">
                                        <input type="hidden" name="inspection_time" id="final_inspection_time" :value="inspectionTime">
                                        <input type="hidden" name="home_address" :value="address">
                                    </form>
                                    
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', () => {
                                            const dInput = document.getElementById('carInspDate');
                                            const tInput = document.getElementById('carInspTime');
                                            if(dInput && tInput) {
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

                                
                                <div class="sc-body" x-show="heroWizardTab==='plate'" x-cloak
                                    :style="heroWizardTab==='plate' ? 'display:block' : 'display:none'">
                                
                                <div
                                    style="margin-bottom: 20px; position: relative; height: 90px; display: flex; align-items: center; justify-content: center;">
                                    <img :key="emirate" :src="plateFile" class="w-full h-full object-contain">

                                    <div class="absolute inset-0 pointer-events-none">
                                        
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

                                        
                                        <template
                                            x-if="!['Abu Dhabi','Dubai','Sharjah','Ajman','Fujairah','UAQ','RAK'].includes(emirate)">
                                            <div x-text="(plateCode === 'Blank' ? '' : plateCode) + ' ' + plate"
                                                class="absolute top-1/2 left-[58%] -translate-x-1/2 -translate-y-1/2 text-[2.6rem] font-normal text-[#031629]"
                                                style="font-family: 'Bebas Neue', sans-serif !important; letter-spacing: 0.15em; text-shadow: 0 1px 2px rgba(255,255,255,0.9);">
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                
                                <div x-show="heroWizardStep===1">
                                    <p class="sc-label" style="color:#94a3b8; margin-bottom:8px"><?php echo e($lfPlateSubtitle); ?></p>
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

                                    <div class="grid grid-cols-3 gap-2 py-4">
                                        <?php $__currentLoopData = ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'RAK', 'UAQ', 'Fujairah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $em): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button" 
                                                class="h-[42px] rounded-xl border text-[0.6rem] font-black uppercase transition-all"
                                                :class="emirate==='<?php echo e($em); ?>' ? 'bg-[#ff6900] border-[#ff6900] text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400'"
                                                @click="emirate='<?php echo e($em); ?>'; plateCode = availableCodes[0]"><?php echo e($em); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <button type="button" class="sc-btn w-full mt-4" 
                                        @click="if(plate) { heroWizardStep=2 } else { triggerToast('Please enter your plate number first!') }">
                                        <?php echo e($lfPlateBtn); ?> &rarr;
                                    </button>
                                </div>

                                
                                <div x-show="heroWizardStep===2" style="display:none">
                                    <div class="sc-summary">
                                        <div class="sc-summary-label">Selected Plate</div>
                                        <div class="sc-summary-val" x-text="emirate + ' ' + plateCode + ' ' + plate"></div>
                                        <div class="sc-summary-sub">Enter contact details and choose inspection service
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="text" x-model="name" placeholder="<?php echo e($lfNameLabel); ?>" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="<?php echo e($lfPhoneLabel); ?>" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="email" x-model="email" placeholder="Email Address" class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                    </div>

                                    <form action="<?php echo e(route('sell-car-lead')); ?>" method="POST" x-ref="plateForm">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="make" :value="'Plate: ' + emirate">
                                        <input type="hidden" name="model" :value="plateCode">
                                        <input type="hidden" name="year" :value="plate">
                                        <input type="hidden" name="name" :value="name">
                                        <input type="hidden" name="phone" :value="phone">
                                        <input type="hidden" name="email" :value="email">
                                        <input type="hidden" name="inspection_type" value="direct_valuation">
                                        <input type="hidden" name="inspection_date" value="<?php echo e(now()->format('Y-m-d')); ?>">
                                        <input type="hidden" name="inspection_time" value="<?php echo e(now()->format('H:i')); ?>">
                                        <input type="hidden" name="lead_type" value="sell_plate">
                                    </form>

                                    <div style="display:flex; align-items:center; gap:10px; margin-top:30px;">
                                        <button type="button" class="sc-btn-back" @click="heroWizardStep=1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn w-full" style="flex:1"
                                            @click="if(plate && name && phone && email) {
                                                $refs.plateForm.submit();
                                                triggerToast('<?php echo e($lfSuccessMsg); ?>');
                                            } else {
                                                triggerToast('Please complete your contact details!');
                                            }">
                                            <?php echo e($lfFinalBtn); ?> &rarr;
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                
                <div class="hero-text">
                    <span class="hero-eyebrow"><?php echo $h2Eyebrow; ?></span>
                    <h1 class="hero-title"><?php echo $h2Title; ?></h1>
                    <p class="hero-desc"><?php echo $h2Subtitle; ?></p>
                </div>

                
                <div class="hero-car">
                    <?php if($h2CirclesEnabled): ?>
                    <div class="hero-car-decor">
                        <div class="decor-circle decor-1"></div>
                        <div class="decor-circle decor-2"></div>
                        <div class="decor-circle decor-3"></div>
                        <div class="decor-circle decor-4"></div>
                        <div class="decor-circle decor-5"></div>
                    </div>
                    <?php endif; ?>

                    <img src="<?php echo e($h2CarImage); ?>" onerror="this.src='/images/cars/mclaren.png'" alt="Featured Car"
                        style="transform: scale(<?php echo e($h2CarScale); ?>) <?php echo e($h2CarMirror ? 'scaleX(-1)' : ''); ?>; transform-origin: center bottom;">

                </div>

            </div>

            
            <div class="sc-pill-container">
                <div class="sc-pill-stats">
                    <?php $__currentLoopData = $formattedBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sc-pill-item">
                            <div class="sc-pill-icon" style="background: <?php echo e($badge['bg']); ?>; color: <?php echo e($badge['color']); ?>;">
                                <div class="sc-pill-icon-glow" style="background: <?php echo e($badge['color']); ?>;"></div>
                                <i data-lucide="<?php echo e($badge['icon']); ?>" style="stroke-width: 2.5px;"></i>
                            </div>
                            <div class="sc-pill-content">
                                <span class="sc-pill-label"><?php echo e($badge['main']); ?></span>
                                <?php if($badge['sub']): ?>
                                    <span class="sc-pill-val"><?php echo e($badge['sub']); ?></span>
                                <?php endif; ?>
                                <?php if($badge['desc']): ?>
                                    <span class="sc-pill-desc"><?php echo e($badge['desc']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(!$loop->last): ?>
                            <div class="sc-pill-sep"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </section>
    </main>

    
    <?php
        $buySectionBrands = collect($catalogMakesWithLogos ?? [])
            ->filter(fn($b) => !empty(data_get($b, 'name')))
            ->values()->all();
        $buySectionYears = range((int) date('Y') + 1, 2000);
    ?>

    <section style="background:#f0f2f5;padding:0 80px 36px">
        <div style="max-width:1200px;margin:0 auto">
            <div
                style="background:#fff;border-radius:20px;padding:24px 28px;box-shadow:0 2px 16px rgba(44,74,110,.08);border:1px solid #e8ecf0">

                
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
                    <div style="display:flex;align-items:center;gap:14px">
                        <div
                            style="width:36px;height:36px;background:#031629;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                            </svg>
                        </div>
                        <div>
                            <p
                                style="font-size:.55rem;font-weight:800;letter-spacing:.22em;text-transform:uppercase;color:#031629;margin-bottom:2px">
                                Car Sale</p>
                            <p
                                style="font-size:.95rem;font-weight:900;color:#031629;font-family:'Outfit',sans-serif;line-height:1">
                                Find Your Next Car</p>
                        </div>
                    </div>
                    <p
                        style="font-size:.72rem;color:#94a3b8;font-weight:500;max-width:260px;text-align:right;line-height:1.5">
                        Tell us what you're looking for and we'll match you with available stock.</p>
                </div>

                
                <form action="<?php echo e(route('sell-car-lead')); ?>" method="POST" x-data="{
                    allModels: <?php echo e(Js::from($catalogModelsByMake ?? [])); ?>,
                    brand: '',
                    models: [],
                    onBrand(v) { this.brand=v; const k=v.toLowerCase().replace(/[^a-z0-9]+/g,''); this.models=this.allModels[k]||this.allModels['__all__']||[]; }
                }">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="lead_type" value="buy">

                    
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:10px;margin-bottom:10px">

                        
                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Brand</label>
                            <div style="position:relative">
                                <select name="make" x-model="brand" @change="onBrand($event.target.value)"
                                    style="width:100%;height:42px;padding:0 32px 0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;cursor:pointer;appearance:none;outline:none;transition:border-color .2s"
                                    onfocus="this.style.borderColor='#031629'"
                                    onblur="this.style.borderColor='#e8ecf0'">
                                    <option value="">Any Brand</option>
                                    <?php $__currentLoopData = $buySectionBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($b['name']); ?>"><?php echo e($b['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8"
                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        
                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Model</label>
                            <div style="position:relative">
                                <select name="model"
                                    style="width:100%;height:42px;padding:0 32px 0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;cursor:pointer;appearance:none;outline:none;transition:border-color .2s"
                                    onfocus="this.style.borderColor='#031629'"
                                    onblur="this.style.borderColor='#e8ecf0'">
                                    <option value="">Any Model</option>
                                    <template x-for="m in models" :key="m">
                                        <option :value="m" x-text="m"></option>
                                    </template>
                                </select>
                                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8"
                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        
                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Year</label>
                            <div style="position:relative">
                                <select name="year"
                                    style="width:100%;height:42px;padding:0 32px 0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;cursor:pointer;appearance:none;outline:none;transition:border-color .2s"
                                    onfocus="this.style.borderColor='#031629'"
                                    onblur="this.style.borderColor='#e8ecf0'">
                                    <option value="">Any Year</option>
                                    <?php $__currentLoopData = $buySectionYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($yr); ?>"><?php echo e($yr); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8"
                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                        
                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Max
                                Budget</label>
                            <div style="position:relative">
                                <select name="budget"
                                    style="width:100%;height:42px;padding:0 32px 0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;cursor:pointer;appearance:none;outline:none;transition:border-color .2s"
                                    onfocus="this.style.borderColor='#031629'"
                                    onblur="this.style.borderColor='#e8ecf0'">
                                    <option value="">Any Budget</option>
                                    <?php $__currentLoopData = ['Under 30k AED', '30k – 60k AED', '60k – 100k AED', '100k – 150k AED', '150k – 250k AED', 'Over 250k AED']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($b); ?>"><?php echo e($b); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8"
                                    xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>

                    </div>

                    
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:10px;align-items:end">

                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Full
                                Name</label>
                            <input type="text" name="name" placeholder="Your name" required
                                style="width:100%;height:42px;padding:0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;outline:none;transition:border-color .2s"
                                onfocus="this.style.borderColor='#031629'" onblur="this.style.borderColor='#e8ecf0'">
                        </div>

                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Phone</label>
                            <input type="tel" name="phone" placeholder="+971 50 000 0000" required
                                style="width:100%;height:42px;padding:0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;outline:none;transition:border-color .2s"
                                onfocus="this.style.borderColor='#031629'" onblur="this.style.borderColor='#e8ecf0'">
                        </div>

                        <div>
                            <label
                                style="font-size:.52rem;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#94a3b8;display:block;margin-bottom:5px">Email</label>
                            <input type="email" name="email" placeholder="you@example.com"
                                style="width:100%;height:42px;padding:0 12px;border-radius:10px;border:1.5px solid #e8ecf0;background:#f8fafc;font-size:.75rem;font-weight:600;color:#031629;outline:none;transition:border-color .2s"
                                onfocus="this.style.borderColor='#031629'" onblur="this.style.borderColor='#e8ecf0'">
                        </div>

                        <button type="submit"
                            style="height:42px;padding:0 28px;border-radius:10px;background:#031629;color:#fff;border:none;font-size:.62rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;white-space:nowrap;transition:background .2s,transform .15s;flex-shrink:0"
                            onmouseover="this.style.background='#031629';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.background='#031629';this.style.transform='translateY(0)'">
                            Request Car &rarr;
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </section>

    
    <?php
        $sellCarYears2 = $sellCarYears ?? range((int) date('Y') + 1, 1995);
    ?>

    <section class="wiz-section" id="sellSection">
        <div style="max-width:1200px;margin:0 auto">

            
            <div class="wiz-header">
                <p class="wiz-eyebrow"><?php echo e($leadArchitecture['header']); ?></p>
                <div class="wiz-title">
                    <span class="step-word active" id="h2w1"><?php echo e($leadArchitecture['step1']); ?></span>
                    <span class="sep">&bull;</span>
                    <span class="step-word muted" id="h2w2"><?php echo e($leadArchitecture['step2']); ?></span>
                    <span class="sep">&bull;</span>
                    <span class="step-word muted" id="h2w3"><?php echo e($leadArchitecture['step3']); ?></span>
                </div>
            </div>

            
            <div class="wiz-dots">
                <div class="wiz-dot active" id="h2dot0"></div>
                <div class="wiz-dot" id="h2dot1"></div>
                <div class="wiz-dot" id="h2dot2"></div>
            </div>

            <div class="wiz-card" id="h2wizCard">
                <form action="<?php echo e(route('sell-car-lead')); ?>" method="POST" id="h2wizard"
                    data-start-step="<?php echo e($wizardStartStep2); ?>">
                    <?php echo csrf_field(); ?>

                    
                    <div data-step="1" class="wiz-step active">

                        
                        <?php if(!empty($brandCardBrands2)): ?>
                            <div style="margin-bottom:16px">
                                <span class="field-lbl">Popular Brands</span>
                                <div class="brand-grid">
                                    <?php $__currentLoopData = $brandCardBrands2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $bKey2 = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                            $bModels2 = data_get($catalogModelsByMake ?? [], $bKey2, data_get($catalogModelsByMake ?? [], '__all__', []));
                                        ?>
                                        <button type="button" class="brand-card" data-brand-pick="<?php echo e($brand['name']); ?>"
                                            data-brand-models='<?php echo json_encode($bModels2, 15, 512) ?>'>
                                            <img src="<?php echo e($brand['logo']); ?>" alt="<?php echo e($brand['name']); ?>"
                                                onerror="this.style.opacity=0">
                                            <span><?php echo e($brand['name']); ?></span>
                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px">

                            
                            <div>
                                <span class="field-lbl">Brand</span>
                                <input type="hidden" name="make" id="h2make">
                                <div class="field-wrap">
                                    <button type="button" class="hub-toggle" id="h2brandToggle">
                                        <span style="display:flex;align-items:center;gap:10px">
                                            <span id="h2brandIcon"
                                                style="display:none;width:28px;height:28px;border-radius:8px;border:1px solid #e8ecf0;overflow:hidden;flex-shrink:0">
                                                <img id="h2brandIconImg" src=""
                                                    style="width:100%;height:100%;object-fit:contain;padding:3px">
                                            </span>
                                            <span id="h2brandLabel" class="hub-placeholder">Select Brand</span>
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="hub-drawer" id="h2brandDrawer">
                                        <div class="hub-drawer-header">
                                            <div style="position:relative;flex:1">
                                                <svg style="position:absolute;left:22px;top:50%;transform:translateY(-50%);color:#ff6900;width:20px;height:20px"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                </svg>
                                                <input type="text" id="h2brandSearch" placeholder="Search brand..."
                                                    class="hub-search">
                                            </div>
                                            <button type="button" id="h2brandReset" class="hub-btn-link">Reset</button>
                                            <button type="button" id="h2brandClose" class="hub-btn-link"
                                                style="color:#94a3b8">Close</button>
                                        </div>
                                        <div class="hub-list">
                                            <?php $__currentLoopData = $brandSelectBrands2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $bKey2b = mb_strtolower(preg_replace('/[^a-z0-9]+/i', '', $brand['name']));
                                                    $bModels2b = data_get($catalogModelsByMake ?? [], $bKey2b, data_get($catalogModelsByMake ?? [], '__all__', []));
                                                ?>
                                                <button type="button" class="hub-option"
                                                    data-brand-hub-value="<?php echo e($brand['name']); ?>"
                                                    data-brand-hub-logo="<?php echo e($brand['logo']); ?>"
                                                    data-brand-key="<?php echo e($bKey2b); ?>" data-brand-models='<?php echo json_encode($bModels2b, 15, 512) ?>'>
                                                    <img src="<?php echo e($brand['logo']); ?>" alt="<?php echo e($brand['name']); ?>"
                                                        onerror="this.style.display='none'"
                                                        style="width:26px;height:26px;object-fit:contain">
                                                    <span><?php echo e($brand['name']); ?></span>
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div>
                                <span class="field-lbl">Model</span>
                                <input type="hidden" name="model" id="h2model">
                                <div class="field-wrap">
                                    <button type="button" class="hub-toggle disabled" id="h2modelToggle" disabled>
                                        <span id="h2modelLabel" class="hub-placeholder">Select brand first</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="hub-drawer" id="h2modelDrawer">
                                        <div class="hub-drawer-header">
                                            <div style="position:relative;flex:1">
                                                <svg style="position:absolute;left:22px;top:50%;transform:translateY(-50%);color:#ff6900;width:20px;height:20px"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                                </svg>
                                                <input type="text" id="h2modelSearch" placeholder="Search model..."
                                                    class="hub-search">
                                            </div>
                                            <button type="button" id="h2modelClose" class="hub-btn-link"
                                                style="color:#94a3b8">Close</button>
                                        </div>
                                        <div class="hub-list" id="h2modelList"></div>
                                    </div>
                                </div>
                            </div>

                            
                            <div>
                                <span class="field-lbl">Year</span>
                                <input type="hidden" name="year" id="h2year">
                                <div class="field-wrap">
                                    <button type="button" class="hub-toggle" id="h2yearToggle">
                                        <span id="h2yearLabel" class="hub-placeholder">Select Year</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="hub-drawer" id="h2yearDrawer">
                                        <div class="hub-grid3">
                                            <?php $__currentLoopData = $sellCarYears2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" class="h2year-pick hub-option"
                                                    data-year-value="<?php echo e($yr); ?>"
                                                    style="justify-content:center"><?php echo e($yr); ?></button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="wiz-nav">
                            <button type="button" class="wiz-btn-next" data-h2-action="next">Get Free Valuation
                                &rarr;</button>
                        </div>
                    </div>

                    
                    <div data-step="2" class="wiz-step">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px">

                            
                            <div style="display:flex;flex-direction:column;gap:16px">
                                <p class="field-lbl" style="margin-bottom:0">Vehicle Specs</p>

                                
                                <div>
                                    <span class="field-lbl">Regional Specs</span>
                                    <input type="hidden" name="gcc" id="h2gcc" value="GCC">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2gccToggle">
                                            <span id="h2gccLabel">GCC Specs</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2gccDrawer">
                                            <?php $__currentLoopData = [['v' => 'GCC', 'l' => 'GCC Specs'], ['v' => 'European', 'l' => 'European'], ['v' => 'American', 'l' => 'American'], ['v' => 'Canadian', 'l' => 'Canadian'], ['v' => 'Korean', 'l' => 'Korean'], ['v' => 'Other', 'l' => 'Other']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" class="h2gcc-pick hub-option"
                                                    data-gcc-value="<?php echo e($reg['v']); ?>"
                                                    data-gcc-label="<?php echo e($reg['l']); ?>"><?php echo e($reg['l']); ?></button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Body Type</span>
                                    <input type="hidden" name="body" id="h2body">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2bodyToggle">
                                            <span id="h2bodyLabel" class="hub-placeholder">Select Type</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2bodyDrawer">
                                            <?php $__currentLoopData = ['Sedan', 'SUV', 'Coupe', 'Hatchback', 'Pickup', 'Luxury', 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" class="h2body-pick hub-option"
                                                    data-body-value="<?php echo e($type); ?>"><?php echo e($type); ?></button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Engine Size</span>
                                    <input type="hidden" name="engine" id="h2engine">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2engineToggle">
                                            <span id="h2engineLabel" class="hub-placeholder">Select Engine</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2engineDrawer">
                                            <?php $__currentLoopData = ['1.0L - 1.5L', '1.6L - 2.0L', '2.1L - 3.0L', '3.1L - 4.0L', 'Over 4.0L', 'EV / Electric', 'Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" class="h2engine-pick hub-option"
                                                    data-engine-value="<?php echo e($sz); ?>"><?php echo e($sz); ?></button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Mileage (KM)</span>
                                    <input type="hidden" name="mileage" id="h2mileage">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2mileageToggle">
                                            <span id="h2mileageLabel" class="hub-placeholder">Select Mileage</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2mileageDrawer">
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px">
                                                <?php $__currentLoopData = ['0-20k', '20k-50k', '50k-100k', '100k-150k', '150k-200k', 'Over 200k', 'Unknown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $range): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <button type="button" class="h2mileage-pick hub-option"
                                                        data-mileage-value="<?php echo e($range); ?>"
                                                        style="justify-content:center"><?php echo e($range); ?></button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div
                                style="border-left:1px solid #f1f5f9;padding-left:28px;display:flex;flex-direction:column;gap:16px">
                                <p class="field-lbl" style="margin-bottom:0">Vehicle Options</p>

                                
                                <div>
                                    <span class="field-lbl">Trim / Options</span>
                                    <input type="hidden" name="trim" id="h2trim" value="Full option">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                        <?php $__currentLoopData = ['Basic', 'Mid option', 'Full option', 'Unknown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button"
                                                class="pick-btn h2trim-pick <?php echo e($opt === 'Full option' ? 'active' : ''); ?>"
                                                data-trim-value="<?php echo e($opt); ?>"><?php echo e($opt); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Paint Condition</span>
                                    <input type="hidden" name="paint" id="h2paint" value="Original">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                        <?php $__currentLoopData = ['Original', 'Partial', 'Total', 'Unknown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button"
                                                class="pick-btn h2paint-pick <?php echo e($opt === 'Original' ? 'active' : ''); ?>"
                                                data-paint-value="<?php echo e($opt); ?>"><?php echo e($opt); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Overall Condition</span>
                                    <input type="hidden" name="condition" id="h2condition" value="good">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                        <?php $__currentLoopData = ['excellent' => 'Elite', 'good' => 'Good', 'fair' => 'Fair', 'needs_work' => 'Needs Work']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <button type="button"
                                                class="pick-btn h2condition-pick <?php echo e($val === 'good' ? 'active' : ''); ?>"
                                                data-condition-value="<?php echo e($val); ?>"><?php echo e($lbl); ?></button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="wiz-nav">
                            <button type="button" class="wiz-btn-back" data-h2-action="back">&larr; Back</button>
                            <button type="button" class="wiz-btn-next" data-h2-action="next">Next Stage &rarr;</button>
                        </div>
                    </div>

                    
                    <div data-step="3" class="wiz-step">
                        <input type="hidden" name="inspection_type" id="h2inspType" value="branch">

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start">

                            
                            <div style="display:flex;flex-direction:column;gap:14px">
                                <p class="field-lbl" style="margin-bottom:0">Your Details</p>

                                <div>
                                    <span class="field-lbl">Full Name</span>
                                    <input type="text" name="name" placeholder="Enter your full name" class="wiz-input">
                                </div>
                                <div>
                                    <span class="field-lbl">Mobile Number</span>
                                    <input type="tel" name="phone" placeholder="+971 50 000 0000" class="wiz-input">
                                </div>
                                <div>
                                    <span class="field-lbl">Email Address</span>
                                    <input type="email" name="email" placeholder="you@example.com" class="wiz-input">
                                </div>
                            </div>

                            
                            <div style="display:flex;flex-direction:column;gap:14px">
                                <p class="field-lbl" style="margin-bottom:0">Inspection Appointment</p>

                                
                                <div>
                                    <span class="field-lbl">Preferred Date</span>
                                    <input type="hidden" name="inspection_date" id="h2inspDate">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2dateToggle">
                                            <span id="h2dateLabel" class="hub-placeholder">Select Date</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2dateDrawer" style="padding:22px">
                                            <div
                                                style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
                                                <button type="button" id="h2calPrev"
                                                    style="width:28px;height:28px;border-radius:8px;border:1px solid #e8ecf0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.75 19.5 8.25 12l7.5-7.5" />
                                                    </svg>
                                                </button>
                                                <span id="h2calMonthYear"
                                                    style="font-size:.7rem;font-weight:800;color:#031629;letter-spacing:.1em;text-transform:uppercase"></span>
                                                <button type="button" id="h2calNext"
                                                    style="width:28px;height:28px;border-radius:8px;border:1px solid #e8ecf0;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div
                                                style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:6px">
                                                <?php $__currentLoopData = ['S', 'M', 'T', 'W', 'T', 'F', 'S']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div
                                                        style="text-align:center;font-size:.5rem;font-weight:800;color:#94a3b8;padding:4px 0">
                                                        <?php echo e($d); ?>

                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <div id="h2calGrid"
                                                style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px"></div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <span class="field-lbl">Preferred Time</span>
                                    <input type="hidden" name="inspection_time" id="h2inspTime">
                                    <div class="field-wrap">
                                        <button type="button" class="hub-toggle" id="h2timeToggle">
                                            <span id="h2timeLabel" class="hub-placeholder">Select Time</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <div class="hub-drawer" id="h2timeDrawer" style="padding:22px">
                                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px">
                                                <?php $__currentLoopData = ['08:00 AM', '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM', '06:00 PM', '07:00 PM']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <button type="button" class="h2time-pick" data-time-value="<?php echo e($slot); ?>"
                                                        style="padding:8px 4px;border-radius:8px;border:1.5px solid #e8ecf0;background:#fff;font-size:.65rem;font-weight:700;color:#4a5568;cursor:pointer;transition:all .15s">
                                                        <?php echo e($slot); ?>

                                                    </button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div style="margin-top:8px">
                                    <button type="submit" data-h2-action="submit" class="wiz-btn-next"
                                        style="width:100%;height:52px;font-size:.75rem">
                                        Request Free Valuation
                                    </button>
                                    <button type="button" data-h2-action="back" class="wiz-btn-back"
                                        style="width:100%;margin-top:8px;justify-content:center;display:flex">&larr;
                                        Adjust Specs</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            /* ═══════════════════════════════════════════════════
               HOME2 SELL WIZARD
            ═══════════════════════════════════════════════════ */
            const wizard = document.getElementById('h2wizard');
            if (!wizard) return;

            const brandModelMap = <?php echo json_encode($catalogModelsByMake ?? [], 15, 512) ?>;
            const startStep = Math.max(0, (parseInt(wizard.dataset.startStep || '1', 10) || 1) - 1);
            const steps = Array.from(wizard.querySelectorAll('[data-step]'));
            let currentIdx = startStep;

            /* ── helpers ── */
            const norm = v => String(v).toLowerCase().replace(/[^a-z0-9]+/g, '');
            const parseM = raw => { try { const p = JSON.parse(raw); return Array.isArray(p) ? p : []; } catch (e) { return []; } };
            const getModels = make => brandModelMap[norm(make)] || brandModelMap.__all__ || [];

            /* ── DOM refs ── */
            const h2make = document.getElementById('h2make');
            const h2model = document.getElementById('h2model');
            const h2year = document.getElementById('h2year');
            const h2gcc = document.getElementById('h2gcc');
            const h2body = document.getElementById('h2body');
            const h2engine = document.getElementById('h2engine');
            const h2mileage = document.getElementById('h2mileage');
            const h2trim = document.getElementById('h2trim');
            const h2paint = document.getElementById('h2paint');
            const h2condition = document.getElementById('h2condition');
            const h2inspDate = document.getElementById('h2inspDate');
            const h2inspTime = document.getElementById('h2inspTime');

            /* ── drawer toggle factory ── */
            function makeHub(toggleId, drawerId) {
                const tog = document.getElementById(toggleId);
                const drw = document.getElementById(drawerId);
                if (!tog || !drw) return { open: () => { }, close: () => { }, toggle: () => { } };
                return {
                    open() { drw.classList.add('hub-open'); },
                    close() { drw.classList.remove('hub-open'); },
                    toggle() { drw.classList.contains('hub-open') ? this.close() : this.open(); }
                };
            }
            const hubs = {
                brand: makeHub('h2brandToggle', 'h2brandDrawer'),
                model: makeHub('h2modelToggle', 'h2modelDrawer'),
                year: makeHub('h2yearToggle', 'h2yearDrawer'),
                gcc: makeHub('h2gccToggle', 'h2gccDrawer'),
                body: makeHub('h2bodyToggle', 'h2bodyDrawer'),
                engine: makeHub('h2engineToggle', 'h2engineDrawer'),
                mileage: makeHub('h2mileageToggle', 'h2mileageDrawer'),
                date: makeHub('h2dateToggle', 'h2dateDrawer'),
                time: makeHub('h2timeToggle', 'h2timeDrawer'),
            };
            function closeAll(except = '') { Object.entries(hubs).forEach(([k, h]) => { if (k !== except) h.close(); }); }

            /* ── toggle clicks ── */
            ['brand', 'model', 'year', 'gcc', 'body', 'engine', 'mileage', 'date', 'time'].forEach(k => {
                const tog = document.getElementById('h2' + k + 'Toggle');
                if (tog) tog.addEventListener('click', e => { e.stopPropagation(); closeAll(k); hubs[k].toggle(); });
            });

            /* close on outside click */
            document.addEventListener('click', () => closeAll());
            document.querySelectorAll('.hub-drawer').forEach(d => d.addEventListener('click', e => e.stopPropagation()));

            /* ── Brand selection ── */
            function setBrand(value, logo = '', models = []) {
                h2make.value = value;
                const lbl = document.getElementById('h2brandLabel');
                const icon = document.getElementById('h2brandIcon');
                const iconImg = document.getElementById('h2brandIconImg');
                if (lbl) { lbl.textContent = value || 'Select Brand'; lbl.classList.toggle('hub-placeholder', !value); }
                if (icon && iconImg) { if (logo) { iconImg.src = logo; icon.style.display = ''; } else { icon.style.display = 'none'; } }
                document.querySelectorAll('[data-brand-hub-value]').forEach(b => b.classList.toggle('selected', b.getAttribute('data-brand-hub-value') === value));
                document.querySelectorAll('[data-brand-pick]').forEach(b => b.classList.toggle('selected', b.getAttribute('data-brand-pick') === value));
                populateModels(models.length ? models : getModels(value));
                setModel('');
            }
            function clearBrand() { setBrand(''); populateModels([]); }

            document.getElementById('h2brandClose')?.addEventListener('click', () => hubs.brand.close());
            document.getElementById('h2brandReset')?.addEventListener('click', () => { clearBrand(); hubs.brand.open(); });

            document.querySelectorAll('[data-brand-hub-value]').forEach(btn => {
                btn.addEventListener('click', () => {
                    setBrand(btn.getAttribute('data-brand-hub-value'), btn.getAttribute('data-brand-hub-logo'), parseM(btn.getAttribute('data-brand-models')));
                    hubs.brand.close();
                });
            });
            document.querySelectorAll('[data-brand-pick]').forEach(btn => {
                btn.addEventListener('click', () => {
                    setBrand(btn.getAttribute('data-brand-pick'), btn.querySelector('img')?.src || '', parseM(btn.getAttribute('data-brand-models')));
                });
            });

            const brandSearch = document.getElementById('h2brandSearch');
            if (brandSearch) brandSearch.addEventListener('input', e => {
                const q = e.target.value.toLowerCase();
                document.querySelectorAll('[data-brand-hub-value]').forEach(b => {
                    b.style.display = (b.getAttribute('data-brand-hub-value') || '').toLowerCase().includes(q) ? '' : 'none';
                });
            });

            /* ── Model selection ── */
            function populateModels(models) {
                const list = document.getElementById('h2modelList');
                const tog = document.getElementById('h2modelToggle');
                if (!list || !tog) return;
                list.innerHTML = '';
                if (!models.length) { tog.disabled = true; tog.classList.add('disabled'); document.getElementById('h2modelLabel').textContent = 'No models found'; return; }
                tog.disabled = false; tog.classList.remove('disabled');
                if (!h2model.value) document.getElementById('h2modelLabel').textContent = 'Select Model';
                models.forEach(m => {
                    const btn = document.createElement('button');
                    btn.type = 'button'; btn.className = 'hub-option'; btn.setAttribute('data-model-value', m);
                    btn.innerHTML = `<span style="width:8px;height:8px;border-radius:50%;background:#e8ecf0;flex-shrink:0"></span><span>${m}</span>`;
                    btn.addEventListener('click', () => { setModel(m); hubs.model.close(); });
                    list.appendChild(btn);
                });
                if (h2model.value) setModel(h2model.value);
            }
            function setModel(value) {
                h2model.value = value;
                const lbl = document.getElementById('h2modelLabel');
                if (lbl) { lbl.textContent = value || 'Select Model'; lbl.classList.toggle('hub-placeholder', !value); }
                document.querySelectorAll('[data-model-value]').forEach(b => b.classList.toggle('selected', b.getAttribute('data-model-value') === value));
            }
            document.getElementById('h2modelClose')?.addEventListener('click', () => hubs.model.close());
            const modelSearch = document.getElementById('h2modelSearch');
            if (modelSearch) modelSearch.addEventListener('input', e => {
                const q = e.target.value.toLowerCase();
                document.querySelectorAll('[data-model-value]').forEach(b => { b.style.display = b.getAttribute('data-model-value').toLowerCase().includes(q) ? '' : 'none'; });
            });

            /* ── Year selection ── */
            document.querySelectorAll('.h2year-pick').forEach(btn => {
                btn.addEventListener('click', () => {
                    const v = btn.getAttribute('data-year-value');
                    h2year.value = v;
                    const lbl = document.getElementById('h2yearLabel');
                    if (lbl) { lbl.textContent = v; lbl.classList.remove('hub-placeholder'); }
                    document.querySelectorAll('.h2year-pick').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    hubs.year.close();
                });
            });

            /* ── Simple hub pickers ── */
            function simplePick(cls, hiddenId, labelId, hub, displayFn) {
                document.querySelectorAll('.' + cls).forEach(btn => {
                    btn.addEventListener('click', () => {
                        const v = btn.dataset[cls.replace('h2', '').replace('-pick', '').replace(/Pick$/, '') + 'Value'] || btn.dataset[Object.keys(btn.dataset)[0]];
                        document.getElementById(hiddenId).value = v;
                        const lbl = document.getElementById(labelId);
                        if (lbl) { lbl.textContent = displayFn ? displayFn(v, btn) : v; lbl.classList.remove('hub-placeholder'); }
                        document.querySelectorAll('.' + cls).forEach(b => b.classList.remove('selected'));
                        btn.classList.add('selected');
                        hubs[hub].close();
                    });
                });
            }
            simplePick('h2gcc-pick', 'h2gcc', 'h2gccLabel', 'gcc', (v, btn) => btn.getAttribute('data-gcc-label') || v);
            simplePick('h2body-pick', 'h2body', 'h2bodyLabel', 'body');
            simplePick('h2engine-pick', 'h2engine', 'h2engineLabel', 'engine');
            simplePick('h2mileage-pick', 'h2mileage', 'h2mileageLabel', 'mileage', v => v + ' KM');

            /* ── Pick buttons (trim/paint/condition) ── */
            ['h2trim-pick', 'h2paint-pick', 'h2condition-pick'].forEach(cls => {
                const hiddenId = cls.replace('-pick', '').replace('h2', 'h2');
                document.querySelectorAll('.' + cls).forEach(btn => {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.' + cls).forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        document.getElementById(hiddenId).value = btn.getAttribute('data-' + cls.replace('h2', '').replace('-pick', '').replace(/-/g, '') + '-value') || Object.values(btn.dataset)[0];
                    });
                });
            });
            // simpler pick for trim
            document.querySelectorAll('.h2trim-pick').forEach(btn => btn.addEventListener('click', () => { h2trim.value = btn.getAttribute('data-trim-value'); }));
            document.querySelectorAll('.h2paint-pick').forEach(btn => btn.addEventListener('click', () => { h2paint.value = btn.getAttribute('data-paint-value'); }));
            document.querySelectorAll('.h2condition-pick').forEach(btn => btn.addEventListener('click', () => { h2condition.value = btn.getAttribute('data-condition-value'); }));

            /* ── Calendar ── */
            let calYear = new Date().getFullYear(), calMonth = new Date().getMonth();
            function renderCal() {
                const grid = document.getElementById('h2calGrid');
                const lbl = document.getElementById('h2calMonthYear');
                if (!grid || !lbl) return;
                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                lbl.textContent = months[calMonth] + ' ' + calYear;
                const first = new Date(calYear, calMonth, 1).getDay();
                const days = new Date(calYear, calMonth + 1, 0).getDate();
                const today = new Date(); today.setHours(0, 0, 0, 0);
                grid.innerHTML = '';
                for (let i = 0; i < first; i++) { const e = document.createElement('div'); grid.appendChild(e); }
                for (let d = 1; d <= days; d++) {
                    const dt = new Date(calYear, calMonth, d);
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.style.cssText = 'padding:6px 2px;border-radius:8px;border:1.5px solid transparent;font-size:.7rem;font-weight:700;cursor:pointer;text-align:center;width:100%;background:transparent;';
                    btn.textContent = d;
                    if (dt < today) { btn.disabled = true; btn.style.color = '#d1d5db'; }
                    else {
                        btn.style.color = '#031629';
                        btn.addEventListener('click', () => {
                            const y = calYear, mo = String(calMonth + 1).padStart(2, '0'), day = String(d).padStart(2, '0');
                            h2inspDate.value = `${y}-${mo}-${day}`;
                            const lbl2 = document.getElementById('h2dateLabel');
                            if (lbl2) { lbl2.textContent = `${day} ${months[calMonth].slice(0, 3)} ${y}`; lbl2.classList.remove('hub-placeholder'); }
                            grid.querySelectorAll('button').forEach(b => b.style.background = 'transparent');
                            btn.style.background = '#031629'; btn.style.color = '#fff'; btn.style.borderColor = '#031629';
                            hubs.date.close();
                        });
                    }
                    grid.appendChild(btn);
                }
            }
            document.getElementById('h2calPrev')?.addEventListener('click', e => { e.stopPropagation(); calMonth--; if (calMonth < 0) { calMonth = 11; calYear--; } renderCal(); });
            document.getElementById('h2calNext')?.addEventListener('click', e => { e.stopPropagation(); calMonth++; if (calMonth > 11) { calMonth = 0; calYear++; } renderCal(); });
            renderCal();

            /* ── Time slots ── */
            document.querySelectorAll('.h2time-pick').forEach(btn => {
                btn.addEventListener('click', () => {
                    const v = btn.getAttribute('data-time-value');
                    h2inspTime.value = v;
                    const lbl = document.getElementById('h2timeLabel');
                    if (lbl) { lbl.textContent = v; lbl.classList.remove('hub-placeholder'); }
                    document.querySelectorAll('.h2time-pick').forEach(b => { b.style.background = '#fff'; b.style.borderColor = '#e8ecf0'; b.style.color = '#4a5568'; });
                    btn.style.background = '#031629'; btn.style.borderColor = '#031629'; btn.style.color = '#fff';
                    hubs.time.close();
                });
            });

            /* ── Step navigation ── */
            function updateUI() {
                steps.forEach((s, i) => { s.classList.toggle('active', i === currentIdx); });
                // dots
                [0, 1, 2].forEach(i => {
                    const dot = document.getElementById('h2dot' + i);
                    if (dot) { dot.classList.toggle('active', i === currentIdx); }
                });
                // title words
                ['h2w1', 'h2w2', 'h2w3'].forEach((id, i) => {
                    const el = document.getElementById(id);
                    if (el) { el.className = 'step-word ' + (i === currentIdx ? 'active' : 'muted'); }
                });
            }

            function showWarn(msg) {
                alert(msg); // simple fallback — could be replaced with a custom toast
            }

            function validate(idx) {
                if (idx === 0) {
                    if (!h2make.value || !h2model.value || !h2year.value) { showWarn('Please select Brand, Model and Year to continue.'); return false; }
                }
                if (idx === 1) {
                    if (!h2gcc.value || !h2body.value || !h2engine.value || !h2mileage.value) { showWarn('Please complete all vehicle specs.'); return false; }
                }
                if (idx === 2) {
                    const name = wizard.querySelector('[name="name"]')?.value;
                    const phone = wizard.querySelector('[name="phone"]')?.value;
                    if (!name || !phone) { showWarn('Please enter your name and phone number.'); return false; }
                    if (!h2inspDate.value || !h2inspTime.value) { showWarn('Please select a date and time for inspection.'); return false; }
                }
                return true;
            }

            wizard.querySelectorAll('[data-h2-action="next"]').forEach(btn => {
                btn.addEventListener('click', () => { if (validate(currentIdx)) { currentIdx++; updateUI(); } });
            });
            wizard.querySelectorAll('[data-h2-action="back"]').forEach(btn => {
                btn.addEventListener('click', () => { currentIdx = Math.max(0, currentIdx - 1); updateUI(); });
            });

            /* ── Submit ── */
            wizard.addEventListener('submit', async e => {
                e.preventDefault();
                if (!validate(2)) return;
                const submitBtn = wizard.querySelector('[data-h2-action="submit"]');
                if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending...'; }
                try {
                    const fd = new FormData(wizard);
                    const mileageMap = { '0-20k': 20000, '20k-50k': 50000, '50k-100k': 100000, '100k-150k': 150000, '150k-200k': 200000, 'Over 200k': 250000, 'Unknown': 0 };
                    const rawMi = fd.get('mileage');
                    fd.set('mileage', mileageMap[rawMi] !== undefined ? mileageMap[rawMi] : (parseInt(rawMi) || 0));
                    if (!fd.get('inspection_date')) fd.set('inspection_date', new Date().toISOString().split('T')[0]);
                    if (!fd.get('inspection_time')) fd.set('inspection_time', 'ASAP');
                    const res = await fetch(wizard.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' } });
                    if (!res.ok) { const err = await res.json().catch(() => ({})); throw new Error(err.message || 'Submission failed'); }
                    const card = document.getElementById('h2wizCard');
                    if (card) card.innerHTML = `<div class="wiz-success"><div class="wiz-success-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg></div><h3>Request Submitted!</h3><p>Your details were received. An operator will contact you shortly.</p><button onclick="location.reload()" style="margin-top:24px;font-size:.65rem;font-weight:800;letter-spacing:.15em;text-transform:uppercase;color:#031629;background:none;border:none;cursor:pointer;border-bottom:2px solid #c5d3e0;padding-bottom:3px">Submit New Lead</button></div>`;
                } catch (err) {
                    if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = 'Request Free Valuation'; }
                    showWarn('Submission failed: ' + err.message);
                }
            });

            updateUI();
        });
    </script>
</body>

</html><?php /**PATH F:\auction_app\resources\views/home2.blade.php ENDPATH**/ ?>