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
    $h2SecondaryCtaLabel = data_get($heroContent, 'secondary_cta_label');
    $h2SecondaryCtaUrl = data_get($heroContent, 'secondary_cta_url') ?: '#';
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
    $lfHeroWidth = (int) data_get($lfContent, 'hero_form_width', 480);
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
    } elseif ($heroBgMode === 'image' || $heroBgMode === 'blend') {
        $overlayColor = $heroBgMode === 'blend' ? $heroBgColor : 'rgba(14,16,23, ' . ($heroOverlayOpacity ?: '0.72') . ')';
        $overlay = $heroOverlayEnabled ? "linear-gradient({$overlayColor}, {$overlayColor}), " : "";
        $heroStyleFinal = "background: {$overlay} url('{$heroBgImage}') !important; background-size: cover !important; background-position: center !important;";
    } elseif ($heroBgMode === 'custom' && $heroCustomCss) {
        $heroStyleFinal = $heroCustomCss;
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
    $navGlass = (bool) data_get($navbarContent, 'glass', true);
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(strip_tags($h2Title)); ?> — <?php echo e(\App\Models\SystemSetting::get('site_name', 'Motor Bazar')); ?></title>
    
    <meta name="app-home-template" content="home2">
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
            --h2-hero-text-left: <?php echo e($lfShowHero ? ($lfHeroWidth + 90) : 44); ?>px;
            --h2-hero-car-left: <?php echo e($lfShowHero ? ($lfHeroWidth - 110) : 0); ?>px;
        }

        .nav-logo img {
            transform: scale(<?php echo e($navLogoScale); ?>);
            transform-origin: left center;
        }

        body {
            background: #e7e7e7 !important;
        }

        .hero {
            height: 950px !important;
            min-height: 950px !important;
            display: flex !important;
            align-items: flex-start !important;
            position: relative !important;
            overflow: visible !important;
            <?php if($heroBgMode !== 'custom'): ?>
                <?php echo $heroStyleFinal; ?>

            <?php endif; ?>
        }

        .hub-drawer-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            background: #fff;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        .hub-btn-link {
            background: none;
            border: none;
            color: #ff6900;
            font-size: 0.65rem;
            font-weight: 1000;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .hub-btn-link:hover {
            background: rgba(255,105,0,0.05);
            transform: translateY(-1px);
        }

        /* Developer Lab Custom Injection */
        <?php if($heroCustomCss): ?>
            <?php if(strpos($heroCustomCss, '{') !== false): ?>
                <?php echo $heroCustomCss; ?>

            <?php else: ?>
                #hero-master { <?php echo $heroCustomCss; ?> }
            <?php endif; ?>
        <?php endif; ?>

        .hero-inner {
            width: 100% !important;
            max-width: 1600px !important;
            margin: 0 auto !important;
            padding: 40px 4% 0 4% !important; /* Removed bottom padding */
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
            top: 620px !important; /* Fixed vertical position to prevent movement on resize */
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

        .h2-trust-strip {
            margin-top: -350px !important; /* Managed overlap */
            padding: 80px 0 !important;
            position: relative;
            z-index: 60; /* Higher than hero (z-50) to show badges on top of car/hero */
        }

        .h2-trust-strip .grid > div + div {
            border-left: 1px solid rgba(255, 105, 0, 0.4);
        }

        .services-card {
            position: relative;
            overflow: hidden;
        }
        .services-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,105,0,0.15), transparent);
            transition: left 0.6s ease;
            pointer-events: none;
        }
        .services-card:hover::before {
            left: 100%;
        }

        .h2-main-below-hero {
            display: block !important;
            width: 100% !important;
            position: relative;
            z-index: 20;
        }

        .h2-main-below-hero section {
            margin-bottom: 120px !important;
            width: 100% !important;
            display: block !important;
        }

        .h2-section-container {
            width: 100% !important;
            max-width: 1600px !important;
            margin: 0 auto !important;
            padding: 0 4% !important;
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
            border-radius: 1rem !important;
            box-shadow: 0 30px 70px -10px rgba(3, 22, 41, 0.45) !important;
            border: 1px solid rgba(0, 0, 0, 0.05) !important;
            z-index: 1000 !important;
            max-height: 480px !important;
            overflow-y: auto !important;
        }

        .sc-btn {
            width: 100%;
            height: 54px !important;
            background: linear-gradient(135deg, #ff6900 0%, #ff8c33 100%) !important;
            color: white !important;
            border-radius: 1rem !important;
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
            border-radius: 1rem !important;
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

        /* CENTER MIDDLE SECTIONS & FOOTER (EXCLUDING HERO) */
        main {
            display: block !important;
            width: 100% !important;
        }

        main section:not(.hero), main footer {
            width: 100% !important;
        }

        /* Fix for Body Type Cards clumping */
        .body-type-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .body-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(3,22,41,0.08);
            border-color: #ff6900;
        }

        /* Center the Review Slider content */
        [data-review-scroll] {
            display: flex !important;
            justify-content: flex-start !important;
            padding-left: calc((100vw - 1600px) / 2);
        }
        @media (max-width: 1600px) {
            [data-review-scroll] { padding-left: 4%; }
        }

        /* --- ULTRA-PREMIUM FOOTER DESIGN --- */
        .h2-footer-root {
            background-color: #e7e7e7 !important;
            color: #031629 !important;
            border-top: 1px solid rgba(0,0,0,0.05) !important;
            padding-top: 100px !important;
            padding-bottom: 60px !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            position: relative;
            z-index: 50;
        }

        .footer-card {
            background: #e7e7e7 !important;
            border-radius: 1rem !important;
            padding: 40px !important;
            box-shadow: 0 20px 50px -15px rgba(3,22,41,0.08) !important;
            border: 1px solid rgba(0,0,0,0.05) !important;
        }

        .footer-header-lux {
            font-size: 0.75rem !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.25em !important;
            color: #ff6900 !important;
            margin-bottom: 25px !important;
            display: block !important;
        }

        .footer-link-lux {
            color: #64748b !important;
            text-decoration: none !important;
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            display: inline-block !important;
            margin-bottom: 12px !important;
        }

        .footer-link-lux:hover {
            color: #ff6900 !important;
            transform: translateX(5px);
        }

        .footer-bottom-lux {
            width: 100% !important;
            max-width: 1600px !important;
            margin-top: 80px !important;
            padding: 30px 4% 0 4% !important;
            border-top: 1px solid rgba(0,0,0,0.05) !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            font-size: 0.8rem !important;
            color: #94a3b8 !important;
            font-weight: 700 !important;
        }

        .footer-social-lux {
            display: flex;
            gap: 12px;
        }

        .social-item-lux {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #031629;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9;
        }

        .social-item-lux:hover {
            background: #ff6900;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 105, 0, 0.2);
        }
    </style>
</head>


<?php
    // CMS Binding: Prioritize /admin/cms/home settings
    $footerDesc = data_get($page?->content, 'footer.description') ?: \App\Models\SystemSetting::get('footer_description', 'The world\'s most trusted platform for premium car auctions.');
    
    $footerSocials = data_get($page?->content, 'footer.socials', []);
    $footerQuickLinks = data_get($page?->content, 'footer.quick_links', [
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Browse Auctions', 'url' => route('auctions.index')],
        ['label' => 'Sell Your Car', 'url' => '#'],
    ]);
    
    $footerPages = data_get($page?->content, 'footer.pages', [
        ['label' => 'Terms of Service', 'url' => '#'],
        ['label' => 'Privacy Policy', 'url' => '#'],
    ]);
    
    $footerPhone = data_get($page?->content, 'footer.phone') ?: \App\Models\SystemSetting::get('site_phone', '');
    $footerEmail = data_get($page?->content, 'footer.email') ?: \App\Models\SystemSetting::get('site_email', '');
    
    $footerCopy = data_get($page?->content, 'footer.copyright', "© ".now()->year." MOTOR BAZAR. ALL RIGHTS RESERVED.");
    $footerTerms = data_get($page?->content, 'footer.terms_url', '#');
    
    // Site Logo from Global Settings
    $siteLogo = \App\Models\SystemSetting::get('site_logo');
    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
    $siteName = \App\Models\SystemSetting::get('site_name', 'MOTOR BAZAR');
?>

<body class="home2" x-data>

    
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => ['variant' => 'modern','siteLogo' => $siteLogo ?? null,'siteName' => $siteName ?? null,'phone' => $navPhone ?? null,'whatsapp' => $navWhatsapp ?? null,'menu' => (object)['items' => $navMenuItems ?? []],'bgColor' => $navBgColor ?? '#e7e7e7','textColor' => $navTxtColor ?? '#031629','dotColor' => $navDotColor ?? '#ff6900','logoScale' => $navLogoScale ?? 1,'isSticky' => $navSticky,'isGlass' => $navGlass]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'modern','siteLogo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siteLogo ?? null),'siteName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siteName ?? null),'phone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navPhone ?? null),'whatsapp' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navWhatsapp ?? null),'menu' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((object)['items' => $navMenuItems ?? []]),'bgColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navBgColor ?? '#e7e7e7'),'textColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navTxtColor ?? '#031629'),'dotColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navDotColor ?? '#ff6900'),'logoScale' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navLogoScale ?? 1),'isSticky' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navSticky),'isGlass' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navGlass)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    
    <main class="flex flex-col w-full min-h-screen relative">
        <section id="hero-master" class="hero w-full block relative z-50">
            
            <?php 
                $showHeroOverlay = $heroOverlayEnabled && ($heroBgMode !== 'custom');
                $finalOverlayColor = ($heroBgMode === 'blend') ? $heroBgColor : 'rgba(14,16,23,1)';
            ?>
            <?php if($showHeroOverlay): ?>
                <div class="absolute inset-0 z-[1]" 
                     style="background: <?php echo e($finalOverlayColor); ?>; opacity: <?php echo e($heroOverlayOpacity); ?>; pointer-events: none;"></div>
            <?php endif; ?>
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
                                    <?php echo e($lfHeaderLabel); ?>

                                </p>
                                <h3 class="sc-title"
                                    style="margin-top: 0 !important; font-size: 1.35rem; font-weight: 900; line-height: 1.1; color: #031629;">
                                    <?php echo $lfHeaderTitle; ?>

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
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="body"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfBodyLabel); ?></option>
                                                        <?php $__currentLoopData = $lfBodyOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($opt); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfEngineLabel); ?></span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="engine"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfEngineLabel); ?></option>
                                                        <?php $__currentLoopData = $lfEngineOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eng): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($eng); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfMileageLabel); ?></span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="mileage"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
                                                        <option value="">Select <?php echo e($lfMileageLabel); ?></option>
                                                        <?php $__currentLoopData = $lfMileageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option><?php echo e($mile); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="field-lbl"><?php echo e($lfSpecsLabel); ?></span>
                                                <div class="sc-field mt-1"
                                                    style="height:52px; background:#f8fafc; border-radius:1rem;">
                                                    <select x-model="gcc"
                                                        class="wiz-input-sm w-full h-full bg-transparent border-none outline-none px-4 font-bold text-[#031629]">
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

                                
                                <div x-show="heroWizardStep === 3" x-cloak>
                                    
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
                                                    }"
                                        style="height:270px; position:relative; border-radius:1.5rem; overflow:hidden; border:4px solid #fff; box-shadow:0 25px 60px -20px rgba(3,22,41,0.2); margin-bottom:30px;">
                                        <img :src="mapUrl"
                                            class="w-full h-full object-cover grayscale opacity-80 transition-all duration-700">

                                        
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
                                                <input type="text" x-model="name" placeholder="<?php echo e($lfNameLabel); ?>"
                                                    class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                            </div>
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="<?php echo e($lfPhoneLabel); ?>"
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
                                    <input type="hidden" name="inspection_date" id="final_inspection_date"
                                        :value="inspectionDate">
                                    <input type="hidden" name="inspection_time" id="final_inspection_time"
                                        :value="inspectionTime">
                                    <input type="hidden" name="home_address" :value="address">
                                </form>

                                
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

                                    <div class="grid grid-cols-3 gap-x-2 gap-y-4 pt-4 mb-4">
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
                                            <input type="text" x-model="name" placeholder="<?php echo e($lfNameLabel); ?>"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="tel" x-model="phone" placeholder="<?php echo e($lfPhoneLabel); ?>"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
                                        </div>
                                        <div class="sc-field" style="height:52px; background:#f8fafc;">
                                            <input type="email" x-model="email" placeholder="Email Address"
                                                class="w-full h-full bg-transparent border-none outline-none text-[0.85rem] font-bold text-[#031629] px-6">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                            </svg>
                                        </button>
                                        <button type="button" class="sc-btn w-full" style="flex:1" @click="if(plate && name && phone && email) {
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
                    <?php if($h2SecondaryCtaLabel): ?>
                    <div class="mt-6">
                        <a href="<?php echo e($h2SecondaryCtaUrl); ?>" class="inline-flex items-center gap-3 px-8 py-4 bg-white/10 backdrop-blur border border-white/30 text-white rounded-full text-sm font-bold hover:bg-white/20 transition-all">
                            <?php echo $h2SecondaryCtaLabel; ?>

                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

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

        </section>

        <?php if($h2ShowMiddleSections): ?>
            <?php
                $sectionOrder = data_get($page?->content, 'section_order', [
                    'trust_badges' => 1,
                    'services' => 2,
                    'google_reviews' => 3,
                    'location' => 4,
                    'featured_cars' => 5,
                    'brand_logos' => 6,
                    'blog' => 7,
                ]);
                
                $showSection = function($section, $defaultOrder = 1) use ($sectionOrder) {
                    $order = (int) ($sectionOrder[$section] ?? $defaultOrder);
                    return $order > 0;
                };
                
                $sectionSort = function($a, $b) use ($sectionOrder) {
                    $orderA = (int) ($sectionOrder[$a] ?? 999);
                    $orderB = (int) ($sectionOrder[$b] ?? 999);
                    return $orderA <=> $orderB;
                };
            ?>
            <div class="h2-main-below-hero" id="sectionsContainer" data-section-order='<?php echo json_encode($sectionOrder, 15, 512) ?>'>

            
            <?php if(count($formattedBadges) > 0 && ($sectionOrder['trust_badges'] ?? 1) > 0): ?>
        <section
            id="section-trust_badges"
            class="h2-trust-strip relative w-full"
            style="background-color: <?php echo e(data_get($page?->content, 'trust_strip_bg', '#e7e7e7')); ?>;"
            aria-label="Trust badges">
                <?php if(data_get($page?->content, 'trust_badges_title')): ?>
                <div class="text-center pt-6 pb-4">
                    <h3 class="text-lg font-black text-[#031629] tracking-tight"><?php echo e(data_get($page?->content, 'trust_badges_title', 'We built our business on trust')); ?></h3>
                </div>
                <?php endif; ?>
                
                <div class="w-full flex justify-center pt-4 pb-8">
                    <div class="flex flex-row justify-center items-start gap-24">
                        <?php $__currentLoopData = $formattedBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col items-center text-center space-y-4 group min-w-[280px]">
                                <div class="flex items-center justify-center transition-transform duration-300 group-hover:scale-110 mb-4"
                                    style="color: <?php echo e($badge['color']); ?>;">
                                    <i data-lucide="<?php echo e($badge['icon']); ?>" class="w-14 h-14 stroke-width-2"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex flex-col">
                                        <span class="text-[#ff6900] text-[0.8rem] font-black uppercase tracking-[0.25em] mb-2"><?php echo e($badge['main']); ?></span>
                                        <?php if($badge['sub']): ?>
                                            <span class="text-[1.4rem] font-black text-[#031629] leading-tight block"><?php echo e($badge['sub']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($badge['desc']): ?>
                                        <p class="text-[0.9rem] leading-relaxed text-slate-500 max-w-[280px]"><?php echo e($badge['desc']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        
            <?php if(($sectionOrder['services'] ?? 2) > 0): ?>
            <section id="section-services" class="w-full py-4 relative z-30">
                <div class="h2-section-container flex flex-col items-center">
                    <div class="text-center relative mb-16">
                        <i data-lucide="car" class="absolute -top-10 -right-4 w-64 h-64 text-slate-200/40 -z-10 hidden lg:block rotate-12"></i>
                        <?php
                            $servicesSubtitle = data_get($page?->content, 'services_subtitle', 'We Offer Best Repair Services');
                            $servicesTitle = data_get($page?->content, 'services_title', 'Our Services');
                        ?>
                        <span class="inline-block text-slate-500 text-[0.65rem] font-bold uppercase tracking-[0.1em] px-4 py-1.5 mb-5">
                            <?php echo e(strtoupper($servicesSubtitle)); ?>

                        </span>
                        <h2 class="text-4xl lg:text-5xl font-black text-[#031629] tracking-tight"><?php echo e($servicesTitle); ?></h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full justify-items-center">
                         
                        <?php
                            $defaultServices = [
                                ['title' => 'Oil Changes', 'icon' => 'droplet', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.'],
                                ['title' => 'Wash & Clean', 'icon' => 'waves', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.'],
                                ['title' => 'ABS Brakes', 'icon' => 'disc', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.'],
                                ['title' => 'Transmission', 'icon' => 'settings-2', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.'],
                                ['title' => 'Tires & Wheels', 'icon' => 'life-buoy', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.'],
                                ['title' => 'Engine Tuning', 'icon' => 'activity', 'description' => 'Curabitur at arcu sed ex venenatis laoreet.']
                            ];
                            $servicesList = data_get($page?->content, 'services_items', []) ?: $defaultServices;
                        ?>
                        <?php $__currentLoopData = $servicesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="services-card bg-white p-12 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] hover:shadow-xl transition-all duration-300 flex flex-col items-center text-center group border border-transparent hover:border-[#ff6900]/20 w-full max-w-[420px]">
                                <div class="w-16 h-16 mb-6 flex items-center justify-center">
                                    <div class="relative">
                                        <i data-lucide="<?php echo e($service['icon'] ?? 'settings'); ?>" class="w-12 h-12 text-[#031629] stroke-[1.5]"></i>
                                        <div class="absolute bottom-1 right-1 w-2.5 h-2.5 bg-[#ff6900] rounded-full"></div>
                                    </div>
                                </div>
                                <h3 class="text-[1.35rem] font-black text-[#031629] mb-4 group-hover:text-[#ff6900] transition-colors"><?php echo e($service['title']); ?></h3>
                                <p class="text-slate-500 text-sm leading-relaxed max-w-[260px]"><?php echo e($service['description']); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                <div class="w-full flex justify-center py-4 mt-4">
                     <div class="w-full max-w-5xl h-[2px] bg-gradient-to-r from-transparent via-[#ff6900] to-transparent opacity-60"></div>
                </div>
            </section>
            <?php endif; ?>

            
            <?php
                $reviewsConfig = $googleReviewBlock ?? [];
                $showReviews = data_get($reviewsConfig, 'enabled') && count(data_get($reviewsConfig, 'reviews', []));
                $allReviews = data_get($reviewsConfig, 'reviews', []);
                $reviewsCount = (int) data_get($reviewsConfig, 'reviews_count', 6);
                $onlyFiveStars = (bool) data_get($reviewsConfig, 'show_only_5_stars', false);
                $reviews = collect($allReviews);
                if ($onlyFiveStars) {
                    $reviews = $reviews->filter(fn($r) => (int) data_get($r, 'rating', 5) === 5);
                }
                $reviews = $reviews->sortBy(fn($r) => (int) data_get($r, 'sort_order', 999))->values();
                $reviews = $reviews->take($reviewsCount);
            ?>
            <?php if($showReviews && ($sectionOrder['google_reviews'] ?? 3) > 0): ?>
                <section id="section-google_reviews" class="py-8 relative overflow-hidden bg-[#e7e7e7]">
                    <div class="h2-section-container relative">
                        
                        <div class="flex flex-col items-center text-center mb-16 px-6">
                            <div class="max-w-2xl">
                                <span class="inline-flex items-center gap-3 px-4 py-2 rounded-full text-[0.72rem] font-black uppercase tracking-[0.32em] bg-white text-slate-600 border border-slate-200 shadow-sm mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4.5 h-4.5">
                                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.89-6.89C35.82 2.38 30.41 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.99 6.21C12.12 13.39 17.55 9.5 24 9.5z" />
                                        <path fill="#4285F4" d="M46.5 24.55c0-1.5-.13-2.94-.38-4.35H24v8.23h12.7c-.55 2.81-2.22 5.19-4.72 6.79l7.62 5.92C44.41 36.58 46.5 30.98 46.5 24.55z" />
                                        <path fill="#FBBC05" d="M10.55 28.43A14.38 14.38 0 0 1 9.5 24c0-1.52.26-2.99.75-4.36l-7.99-6.21A23.884 23.884 0 0 0 0 24c0 3.82.91 7.42 2.51 10.59l8.04-6.16z" />
                                        <path fill="#34A853" d="M24 47.5c6.11 0 11.24-2.01 14.99-5.46l-7.62-5.92c-2.12 1.42-4.8 2.25-7.37 2.25-5.64 0-10.42-3.79-12.45-9.02l-8.04 6.16C6.56 42.47 14.51 47.5 24 47.5z" />
                                    </svg>
                                    <?php echo e(data_get($reviewsConfig, 'badge', 'Google Reviews')); ?>

                                </span>
                                <h2 class="text-4xl lg:text-5xl font-black text-[#031629] tracking-tight leading-[1.1] mb-4">
                                    <?php echo e(data_get($reviewsConfig, 'title', 'Loved by real buyers')); ?>

                                </h2>
                                <?php if(data_get($reviewsConfig, 'show_rating_badge', true) && (data_get($reviewsConfig, 'average_rating') || data_get($reviewsConfig, 'reviews_count_total'))): ?>
                                    <div class="flex items-center justify-center gap-3 mb-4">
                                        <div class="flex items-center gap-1">
                                            <?php for($i = 0; $i < 5; $i++): ?>
                                                <i data-lucide="star" class="w-4 h-4 fill-[#ff9900] text-[#ff9900]"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-xl font-black text-[#031629]"><?php echo e(data_get($reviewsConfig, 'average_rating', '4.9')); ?></span>
                                        <span class="text-sm font-medium text-slate-400">(<?php echo e(data_get($reviewsConfig, 'reviews_count_total', '500+')); ?> reviews)</span>
                                    </div>
                                <?php endif; ?>
                                <p class="text-slate-500 font-bold text-sm tracking-wide">
                                    <?php echo e(data_get($reviewsConfig, 'subtitle', 'Straight from verified Google customers.')); ?>

                                </p>
                            </div>
                        </div>

                        
                        
                        <div 
                            x-data="{ 
                                active: 0, 
                                total: <?php echo e(count($reviews)); ?>,
                                autoplay() {
                                    this.timer = setInterval(() => {
                                        this.active = (this.active + 1) % this.total;
                                    }, 4000);
                                },
                                stop() { clearInterval(this.timer); }
                            }"
                            x-init="autoplay()"
                            @mouseenter="stop()"
                            @mouseleave="autoplay()"
                            class="relative min-h-[400px] flex items-center justify-center overflow-hidden py-8"
                        >
                            <div class="relative w-full max-w-5xl h-[320px]">
                                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div 
                                        class="absolute inset-0 flex items-center justify-center transition-all duration-700 ease-in-out cursor-pointer"
                                        x-cloak
                                        :class="{
                                            'z-30 opacity-100 scale-100 blur-0 translate-x-0 brightness-100': active === <?php echo e($idx); ?>,
                                            'z-20 opacity-50 scale-[0.88] translate-x-[-15%] md:translate-x-[-25%] brightness-90 grayscale-[0.2] pointer-events-none': active === (<?php echo e($idx); ?> + 1) % total || (active === 0 && <?php echo e($idx); ?> === total - 1),
                                            'z-20 opacity-50 scale-[0.88] translate-x-[15%] md:translate-x-[25%] brightness-90 grayscale-[0.2] pointer-events-none': active === (<?php echo e($idx); ?> - 1 + total) % total || (active === total - 1 && <?php echo e($idx); ?> === 0),
                                            'z-10 opacity-30 scale-[0.75] translate-x-[-40%] md:translate-x-[-50%] translate-y-[10px] brightness-85 grayscale-[0.3] pointer-events-none': active === (<?php echo e($idx); ?> + 2) % total || (active === 0 && <?php echo e($idx); ?> === total - 2) || (active === 1 && <?php echo e($idx); ?> === total - 1),
                                            'z-10 opacity-30 scale-[0.75] translate-x-[40%] md:translate-x-[50%] translate-y-[10px] brightness-85 grayscale-[0.3] pointer-events-none': active === (<?php echo e($idx); ?> - 2 + total) % total || (active === total - 2 && <?php echo e($idx); ?> === 0) || (active === total - 1 && <?php echo e($idx); ?> === 1),
                                            'z-0 opacity-0 scale-50 blur-sm': active !== <?php echo e($idx); ?> && active !== (<?php echo e($idx); ?> + 1) % total && active !== (<?php echo e($idx); ?> - 1 + total) % total && active !== (<?php echo e($idx); ?> + 2) % total && active !== (<?php echo e($idx); ?> - 2 + total) % total
                                        }"
                                        @click="active = <?php echo e($idx); ?>"
                                    >
                                        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.08)] p-6 md:p-8 w-[90vw] md:w-[400px] flex flex-col h-full relative">
                                             
                                             <div class="absolute top-5 right-5 opacity-20">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-4 h-4">
                                                    <path fill="#757575" d="M24 9.5c3.54 0 6.72 1.22 9.23 3.6l6.89-6.89C35.82 2.38 30.41 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.99 6.21C12.12 13.39 17.55 9.5 24 9.5z" />
                                                    <path fill="#757575" d="M46.5 24.55c0-1.5-.13-2.94-.38-4.35H24v8.23h12.7c-.55 2.81-2.22 5.19-4.72 6.79l7.62 5.92C44.41 36.58 46.5 30.98 46.5 24.55z" />
                                                    <path fill="#757575" d="M10.55 28.43A14.38 14.38 0 0 1 9.5 24c0-1.52.26-2.99.75-4.36l-7.99-6.21A23.884 23.884 0 0 0 0 24c0 3.82.91 7.42 2.51 10.59l8.04-6.16z" />
                                                    <path fill="#757575" d="M24 47.5c6.11 0 11.24-2.01 14.99-5.46l-7.62-5.92c-2.12 1.42-4.8 2.25-7.37 2.25-5.64 0-10.42-3.79-12.45-9.02l-8.04 6.16C6.56 42.47 14.51 47.5 24 47.5z" />
                                                </svg>
                                            </div>

                                            <div class="flex items-center gap-4 mb-4">
                                                <div class="relative">
                                                    <div class="w-14 h-14 rounded-full bg-slate-50 overflow-hidden border border-slate-100 flex items-center justify-center shadow-inner">
                                                        <?php if(data_get($review, 'photo_url')): ?>
                                                            <img src="<?php echo e(data_get($review, 'photo_url')); ?>" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <span class="text-xs font-bold text-slate-400"><?php echo e(strtoupper(substr(data_get($review, 'author', 'G'), 0, 1))); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="absolute -bottom-0.5 -right-0.5 w-6 h-6 bg-[#ff6900] rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                                                        <i data-lucide="star" class="w-3 h-3 text-white fill-current"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-[#3366cc] text-[1.05rem] leading-none mb-1">
                                                        <?php echo e(data_get($review, 'author', 'Anonymous')); ?>

                                                    </h4>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-xs font-medium text-slate-400">
                                                            <?php echo e(data_get($review, 'time', 'Recently')); ?>

                                                        </span>
                                                        <?php if(data_get($reviewsConfig, 'reviews_count_total') || data_get($reviewsConfig, 'average_rating')): ?>
                                                            <span class="text-xs text-slate-300">|</span>
                                                            <span class="text-xs font-bold text-[#ff6900]">
                                                                <?php echo e(data_get($reviewsConfig, 'average_rating', '4.9')); ?> <?php echo e(data_get($reviewsConfig, 'reviews_count_total') ? '/ ' . data_get($reviewsConfig, 'reviews_count_total') . ' reviews' : ''); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex gap-0.5 mb-4">
                                                <?php $rating = (int) data_get($review, 'rating', 5); ?>
                                                <?php for($i = 0; $i < 5; $i++): ?>
                                                    <i data-lucide="star" class="w-4 h-4 <?php echo e($i < $rating ? 'fill-[#ff9900] text-[#ff9900]' : 'text-slate-200'); ?>"></i>
                                                <?php endfor; ?>
                                            </div>

                                            <p class="text-slate-600 font-normal leading-relaxed text-[0.95rem] flex-grow italic">
                                                “<?php echo e(data_get($review, 'text', '')); ?>”
                                                <?php if(strlen(data_get($review, 'text', '')) > 100): ?>
                                                    <a href="https://google.com/search?q=motorbazar+reviews" target="_blank" class="text-[#3366cc] font-bold hover:underline ml-1">read more</a>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            
                            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 md:px-12 pointer-events-none">
                                <button @click="active = (active - 1 + total) % total" class="w-12 h-12 rounded-full bg-white shadow-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#ff6900] transition-colors pointer-events-auto">
                                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                                </button>
                                <button @click="active = (active + 1) % total" class="w-12 h-12 rounded-full bg-white shadow-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#ff6900] transition-colors pointer-events-auto">
                                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                                </button>
                            </div>

                            
                            <div class="absolute bottom-4 flex justify-center gap-2">
                                <template x-for="(r, i) in total" :key="i">
                                    <button 
                                        @click="active = i"
                                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                        :class="active === i ? 'bg-[#ff6900] w-6' : 'bg-slate-300'"
                                    ></button>
                                </template>
                            </div>
                        </div>
                        </div>
                    </section>

                <?php else: ?>
                    <section class="py-6 px-6 lg:px-12 bg-[#e7e7e7] relative z-20 text-center">
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
                <?php endif; ?>

                
                <div class="w-full flex justify-center py-4">
                    <div class="w-full max-w-5xl h-[2px] bg-gradient-to-r from-transparent via-[#ff6900] to-transparent opacity-60"></div>
                </div>

                
                <?php if(($sectionOrder['featured_cars'] ?? 5) > 0): ?>
                <section id="section-featured_cars"
                    class="block w-full clear-both relative z-30 py-6 px-6 lg:px-12 border-t border-slate-200/50">
                    <div class="max-w-[1440px] mx-auto">
                        <div class="flex flex-col items-center text-center mb-12">
                            <span
                                class="text-[#ff6900] font-black uppercase tracking-[0.35em] text-[0.65rem] mb-3 block">Browse
                                by category</span>
                            <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-[#031629] mb-4">Search cars by
                                body type</h2>
                            <a href="<?php echo e(route('auctions.index')); ?>"
                                class="text-[#031629] font-black text-xs uppercase tracking-[0.22em] border-b-2 border-[#ff6900] pb-1 w-fit">View
                                all inventory</a>
                        </div>
                        <?php
                            $defaultBodyTypes = [
                                ['label' => 'Sedan', 'icon' => 'car', 'slug' => 'sedan'],
                                ['label' => 'SUV', 'icon' => 'shield', 'slug' => 'suv'],
                                ['label' => 'Coupe', 'icon' => 'zap', 'slug' => 'coupe'],
                                ['label' => 'Hatch', 'icon' => 'box', 'slug' => 'hatchback'],
                                ['label' => 'Cabrio', 'icon' => 'sun', 'slug' => 'cabrio'],
                                ['label' => 'Pickup', 'icon' => 'truck', 'slug' => 'pickup'],
                            ];
                            $bodyTypes = data_get($page?->content, 'body_types', []) ?: $defaultBodyTypes;
                            $bodyTypesDisplayMode = data_get($page?->content, 'body_types_display_mode', 'cards');
                            $bodyTypesShowGrid = data_get($page?->content, 'body_types_show_grid', true);
                        ?>
                        
                        <?php if($bodyTypesDisplayMode === 'images_only'): ?>
                        <style>
                            .body-type-grid {
                                display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                                gap: 1.5rem;
                            }
                            .body-type-grid.with-grid {
                                gap: 0;
                            }
                            .body-type-grid.with-grid .type-image-item {
                                border: 1px solid #e5e7eb;
                            }
                            .type-image-item {
                                position: relative;
                                aspect-ratio: 16/10;
                                border-radius: 1rem;
                                overflow: hidden;
                                background: #f3f4f6;
                            }
                            .type-image-item img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                transition: transform 0.5s ease;
                            }
                            .type-image-item:hover img {
                                transform: scale(1.08);
                            }
                            .type-image-overlay {
                                position: absolute;
                                bottom: 0;
                                left: 0;
                                right: 0;
                                background: linear-gradient(transparent, rgba(0,0,0,0.8));
                                padding: 2rem 1rem 1rem;
                                color: white;
                            }
                        </style>
                        <div class="body-type-grid <?php echo e($bodyTypesShowGrid ? 'with-grid' : ''); ?>">
                            <?php $__currentLoopData = $bodyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($type['image'])): ?>
                                <a href="<?php echo e(route('auctions.index', ['body_type' => $type['slug']])); ?>" class="type-image-item group">
                                    <img src="<?php echo e($type['image']); ?>" alt="<?php echo e($type['label']); ?>">
                                    <div class="type-image-overlay">
                                        <span class="text-sm font-black tracking-tight"><?php echo e($type['label']); ?></span>
                                    </div>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php else: ?>
                        <div class="flex flex-col lg:flex-row gap-12" x-data="{ activeType: '<?php echo e($bodyTypes[0]['slug']); ?>' }">
                            <!-- Sidebar List -->
                            <div class="w-full lg:w-1/3 flex flex-col gap-4">
                                <?php $__currentLoopData = $bodyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button 
                                    @click="activeType = '<?php echo e($type['slug']); ?>'"
                                    :class="activeType === '<?php echo e($type['slug']); ?>' ? 'bg-[#031629] text-white ring-4 ring-[#ff6900]/10 scale-[1.02]' : 'bg-white/80 text-[#031629] hover:bg-white hover:shadow-lg'"
                                    class="flex items-center justify-between p-6 rounded-2xl border border-black/5 transition-all duration-500 text-left group">
                                    <div class="flex items-center gap-6">
                                        <div :class="activeType === '<?php echo e($type['slug']); ?>' ? 'bg-[#ff6900]' : 'bg-slate-100 group-hover:bg-[#ff6900]/10'" 
                                             class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500">
                                            <i data-lucide="<?php echo e($type['icon'] ?? 'car'); ?>" 
                                               :class="activeType === '<?php echo e($type['slug']); ?>' ? 'text-white rotate-0' : 'text-[#ff6900] -rotate-12 group-hover:rotate-0'" 
                                               class="w-6 h-6 transition-all duration-500"></i>
                                        </div>
                                        <div>
                                            <span class="block text-lg font-black tracking-tight leading-none mb-1"><?php echo e($type['label']); ?></span>
                                            <span class="text-[0.6rem] font-bold uppercase tracking-[0.2em] opacity-50">Explore Collection</span>
                                        </div>
                                    </div>
                                    <div :class="activeType === '<?php echo e($type['slug']); ?>' ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-4'"
                                         class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center transition-all duration-500">
                                        <i data-lucide="arrow-right" class="w-4 h-4 text-[#ff6900]"></i>
                                    </div>
                                </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <!-- Cars Panel -->
                            <div class="w-full lg:w-2/3">
                                <div class="bg-white/50 backdrop-blur-xl rounded-2xl p-4 md:p-12 border border-white/50 shadow-2xl h-full min-h-[600px] relative overflow-hidden">
                                    
                                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#ff6900]/5 blur-[100px] rounded-full"></div>
                                    
                                    <?php $__currentLoopData = $bodyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div x-show="activeType === '<?php echo e($type['slug']); ?>'" 
                                         x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-700" 
                                         x-transition:enter-start="opacity-0 translate-y-8"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="relative z-10">
                                        
                                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                                            <div>
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="w-8 h-1 bg-[#ff6900] rounded-full"></span>
                                                    <span class="text-[0.65rem] font-black uppercase tracking-[0.3em] text-[#ff6900]">Premium Inventory</span>
                                                </div>
                                                <h3 class="text-3xl md:text-4xl font-black text-[#031629] tracking-tight">
                                                    The <span class="italic text-[#ff6900]"><?php echo e($type['label']); ?></span> Experience
                                                </h3>
                                            </div>
                                            <a href="<?php echo e(route('auctions.index', ['body_type' => $type['slug']])); ?>" 
                                               class="px-8 py-4 bg-[#031629] text-white rounded-full text-[0.65rem] font-black uppercase tracking-widest flex items-center gap-3 hover:bg-[#ff6900] transition-all duration-500 shadow-lg hover:shadow-[#ff6900]/25">
                                                View Catalog
                                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                            </a>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <?php
                                                // Ideally this would be dynamic, but for now we show featured ones
                                                $filtered = $featuredAuctions->shuffle()->take(2);
                                            ?>

                                            <?php $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('auctions.show', $auction)); ?>" class="group block">
                                                <div class="bg-white rounded-2xl p-8 border border-black/5 shadow-xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-2xl">
                                                    <div class="aspect-[16/10] mb-8 overflow-hidden rounded-2xl bg-[#f8fafc] relative">
                                                        <img src="<?php echo e($auction->car->image_url ?? '/images/cars/car-main.jpg'); ?>" 
                                                             class="w-full h-full object-contain p-4 transition-transform duration-700 group-hover:scale-110">
                                                        <div class="absolute bottom-4 left-4">
                                                            <span class="px-4 py-2 bg-white/90 backdrop-blur rounded-full text-[0.6rem] font-black uppercase tracking-widest shadow-sm">
                                                                <?php echo e($auction->car->year); ?> Model
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-between gap-4">
                                                        <div class="flex-1 min-w-0">
                                                            <span class="text-[0.6rem] font-black uppercase tracking-[0.25em] text-[#ff6900] block mb-1">
                                                                <?php echo e($auction->car->brand?->name ?? $auction->car->make); ?>

                                                            </span>
                                                            <h4 class="text-xl font-black text-[#031629] tracking-tight truncate">
                                                                <?php echo e($auction->car->carModel?->name ?? $auction->car->model); ?>

                                                            </h4>
                                                        </div>
                                                        <div class="text-right shrink-0">
                                                            <span class="text-[0.55rem] font-bold text-slate-400 block uppercase tracking-tighter mb-1">Starting at</span>
                                                            <span class="text-xl font-black text-[#031629] tabular-nums">$<?php echo e(number_format($auction->initial_price)); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                
                <div class="w-full flex justify-center py-12">
                    <div class="w-full max-w-5xl h-[2px] bg-gradient-to-r from-transparent via-[#ff6900] to-transparent opacity-60"></div>
                </div>
                <?php endif; ?>

                
                <?php
                    $locHeaderTitle = data_get($page?->content, 'location.section_header_title', 'Find Our Hub');
                    $locHeaderSub = data_get($page?->content, 'location.section_header_subtitle', 'Visit our main showroom in the heart of Dubai');
                    $locLabel = data_get($page?->content, 'location.section_label', 'Location');
                    $locIframe = data_get($page?->content, 'location.iframe_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3610.1234!2d55.2708!3d25.2048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDEyJzE3LjMiTiA1NcKwMTYnMTQuOSJF!5e0!3m2!1sen!2sae!4v1680000000000!5m2!1sen!2sae');
                    $locTitle = data_get($page?->content, 'location.title', 'Visit Motor');
                    $locTitleAccent = data_get($page?->content, 'location.title_accent', 'Bazar');
                    $locSub = data_get($page?->content, 'location.subtitle', 'Our team of experts is ready to welcome you and assist with your luxury car needs.');
                    $locAddr = data_get($page?->content, 'location.address', 'Al Quoz Industrial Area 3, Dubai, UAE');
                    $locPhone = data_get($page?->content, 'location.phone', \App\Models\SystemSetting::get('site_phone', '+971 4 000 0000'));
                    $locHours = data_get($page?->content, 'location.hours', 'Mon – Sat: 9:00 AM – 7:00 PM');
                    $locMapsUrl = data_get($page?->content, 'location.maps_url', 'https://maps.google.com');
                    $locBtnLabel = data_get($page?->content, 'location.button_label', 'Get Directions');
                ?>
                <?php if(($sectionOrder['location'] ?? 4) > 0): ?>
                <section id="section-location" class="w-full py-6 relative z-30 overflow-hidden">
                    <div class="h2-section-container">
                        
                        <div class="text-center mb-16 relative">
                            <span class="inline-block border border-slate-300 text-[#ff6900] text-[0.65rem] font-black uppercase tracking-[0.3em] px-5 py-2 mb-6 rounded-full bg-white shadow-sm">
                                <?php echo e($locLabel); ?>

                            </span>
                            <h2 class="text-4xl lg:text-5xl font-extrabold text-[#031629] tracking-tight mb-4"><?php echo e($locHeaderTitle); ?></h2>
                            <p class="text-slate-500 font-bold text-sm tracking-wide max-w-xl mx-auto"><?php echo e($locHeaderSub); ?></p>
                        </div>

                        
                        <div class="relative rounded-2xl overflow-hidden shadow-[0_45px_100px_-30px_rgba(3,22,41,0.25)] border-[6px] border-white min-h-[550px] group">
                            
                            <iframe 
                                src="<?php echo e($locIframe); ?>" 
                                width="100%" height="100%" 
                                class="absolute inset-0 w-full h-full object-cover grayscale opacity-80 group-hover:grayscale-0 transition-all duration-1000"
                                style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                            
                            <div class="absolute inset-0 bg-gradient-to-r from-[#e7e7e7]/95 via-[#e7e7e7]/40 to-transparent flex items-center px-10 pointer-events-none">
                                <div class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/20 p-10 rounded-2xl shadow-2xl pointer-events-auto transition-transform duration-700 group-hover:translate-x-4">
                                    <h3 class="text-3xl lg:text-4xl font-black text-[#031629] leading-tight mb-4">
                                        <?php echo e($locTitle); ?> <span class="text-[#ff6900]"><?php echo e($locTitleAccent); ?></span>
                                    </h3>
                                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-10">
                                        <?php echo e($locSub); ?>

                                    </p>

                                    <div class="space-y-6 mb-10">
                                        <div class="flex items-center gap-5 group/item">
                                            <div class="w-11 h-11 rounded-2xl bg-[#ff6900]/10 border border-[#ff6900]/20 flex items-center justify-center text-[#ff6900] group-hover/item:bg-[#ff6900] group-hover/item:text-white transition-all">
                                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[0.55rem] font-black uppercase tracking-widest text-[#ff6900] mb-0.5">Physical HQ</span>
                                                <span class="text-sm font-bold text-[#031629]"><?php echo e($locAddr); ?></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-5 group/item">
                                            <div class="w-11 h-11 rounded-2xl bg-[#ff6900]/10 border border-[#ff6900]/20 flex items-center justify-center text-[#ff6900] group-hover/item:bg-[#ff6900] group-hover/item:text-white transition-all">
                                                <i data-lucide="phone" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[0.55rem] font-black uppercase tracking-widest text-[#ff6900] mb-0.5">Support Center</span>
                                                <span class="text-sm font-bold text-[#031629]"><?php echo e($locPhone); ?></span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-5 group/item">
                                            <div class="w-11 h-11 rounded-2xl bg-[#ff6900]/10 border border-[#ff6900]/20 flex items-center justify-center text-[#ff6900] group-hover/item:bg-[#ff6900] group-hover/item:text-white transition-all">
                                                <i data-lucide="clock" class="w-5 h-5"></i>
                                            </div>
                                            <div>
                                                <span class="block text-[0.55rem] font-black uppercase tracking-widest text-[#ff6900] mb-0.5">Opening Hours</span>
                                                <span class="text-sm font-bold text-[#031629]"><?php echo e($locHours); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="<?php echo e($locMapsUrl); ?>" target="_blank" class="flex items-center justify-center gap-3 bg-[#ff6900] text-white font-black text-xs uppercase tracking-[0.2em] py-4 px-8 rounded-xl hover:bg-white hover:text-[#031629] transition-all shadow-xl shadow-orange-500/20 group/btn">
                                        <i data-lucide="navigation" class="w-4 h-4 group-hover/btn:scale-125 transition-transform"></i>
                                        <?php echo e($locBtnLabel); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                
                <div class="w-full flex justify-center py-4">
                    <div class="w-full max-w-5xl h-[2px] bg-gradient-to-r from-transparent via-[#ff6900] to-transparent opacity-60"></div>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        
        <?php if(isset($latestPosts)): ?>
            <?php if(count($latestPosts) > 0 && ($sectionOrder['blog'] ?? 7) > 0): ?>
                <section id="section-blog" class="py-6 bg-[#e7e7e7] relative overflow-hidden">
                    <div class="h2-section-container relative z-10">
                        <div class="flex flex-col items-center text-center mb-16 px-6">
                            <span class="text-[#ff6900] text-[0.7rem] font-black uppercase tracking-[0.3em] mb-4">
                                <?php echo e(data_get($page?->content, 'blog_title', 'Latest Insights')); ?>

                            </span>
                            <h2 class="text-4xl lg:text-5xl font-black text-[#031629] tracking-tight">
                                <?php echo e(data_get($page?->content, 'blog_subtitle', 'Blog & Articles')); ?>

                            </h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
                            <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="/blog/<?php echo e($post->slug); ?>" class="group">
                                    <div class="bg-white rounded-2xl overflow-hidden border border-black/5 shadow-xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.15)] h-full flex flex-col">
                                        <div class="aspect-[16/10] overflow-hidden relative">
                                            <?php if($post->featured_image): ?>
                                                <img src="<?php echo e(asset('storage/'.$post->featured_image)); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                                    <i data-lucide="image" class="w-12 h-12 text-slate-200"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#031629]/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        </div>
                                        <div class="p-8 flex-1 flex flex-col">
                                            <div class="flex items-center gap-3 mb-4">
                                                <span class="px-3 py-1 bg-[#ff6900]/10 text-[#ff6900] text-[0.6rem] font-black uppercase tracking-widest rounded-full">
                                                    <?php echo e($post->category->name ?? 'Article'); ?>

                                                </span>
                                                <span class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">
                                                    <?php echo e($post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y')); ?>

                                                </span>
                                            </div>
                                            <h3 class="text-xl font-black text-[#031629] leading-tight group-hover:text-[#ff6900] transition-colors line-clamp-2 mb-6">
                                                <?php echo e($post->title); ?>

                                            </h3>
                                            <div class="mt-auto flex items-center gap-2 text-[#ff6900] text-[0.7rem] font-black uppercase tracking-widest">
                                                Read More
                                                <i data-lucide="arrow-right" class="w-3 h-3 transition-transform group-hover:translate-x-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                
                <div class="w-full flex justify-center py-12">
                    <div class="w-full max-w-5xl h-px bg-gradient-to-r from-transparent via-[#ff6900] to-transparent opacity-20"></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        
        <?php
            $eliteBrands = data_get($page?->content, 'brands', []);
            $eliteBrandImages = [
                'mercedes-benz' => '/images/brands/mercedes.svg',
                'bmw' => '/images/brands/bmw.svg',
                'audi' => '/images/brands/audi.svg',
                'porsche' => '/images/brands/porsche.svg',
                'toyota' => '/images/brands/toyota.svg',
                'honda' => '/images/brands/honda.svg',
                'ford' => '/images/brands/ford.svg',
                'nissan' => '/images/brands/nissan.svg',
                'hyundai' => '/images/brands/hyundai.svg',
                'mazda' => '/images/brands/mazda.svg',
                'tesla' => '/images/brands/tesla.svg',
                'volkswagen' => '/images/brands/volkswagen.svg',
                'suzuki' => '/images/brands/suzuki.svg',
                'lamborghini' => '/images/brands/lamborghini.svg',
                'land-rover' => '/images/brands/land-rover.svg',
            ];
        ?>
        <?php if(count($eliteBrands) > 0 && ($sectionOrder['brand_logos'] ?? 6) > 0): ?>
            <div id="section-brand_logos">
            <style>
                @keyframes lux-marquee {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(calc(-240px * <?php echo e(count($eliteBrands)); ?> - 4rem * <?php echo e(count($eliteBrands)); ?>)); }
                }
                .lux-slider-track {
                    display: flex;
                    width: max-content;
                    animation: lux-marquee 40s linear infinite;
                    padding: 2rem 0;
                }
                .lux-slider-track:hover {
                    animation-play-state: paused;
                }
                .lux-brand-item {
                    width: 240px;
                    flex-shrink: 0;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    margin-right: 4rem;
                    gap: 1.5rem;
                    cursor: pointer;
                }
                .lux-brand-logo {
                    height: 100px;
                    width: 180px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                    filter: grayscale(100%) brightness(0) opacity(0.35);
                    transform: scale(0.95);
                }
                .lux-brand-item:hover .lux-brand-logo {
                    filter: grayscale(0%) brightness(1) opacity(1) drop-shadow(0 10px 15px rgba(255,105,0,0.15));
                    transform: scale(1.1) translateY(-5px);
                }
                .lux-brand-logo img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }
                .lux-brand-name {
                    font-size: 0.75rem;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: 0.25em;
                    color: #64748b;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                }
                .lux-brand-item:hover .lux-brand-name {
                    color: #ff6900;
                }
            </style>
            <div class="w-full relative z-40 bg-transparent pb-6">
                <div class="text-center mb-4">
                    <span class="text-[0.65rem] font-black uppercase tracking-[0.2em] text-[#ff6900]">Elite Architecture</span>
                    <h3 class="text-xl font-black text-[#031629] mt-1 tracking-tight">Trusted by Premium Brands</h3>
                </div>
                <div class="overflow-hidden w-full relative group">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-32 md:w-64 bg-gradient-to-r from-[#e7e7e7] to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-32 md:w-64 bg-gradient-to-l from-[#e7e7e7] to-transparent z-10 pointer-events-none"></div>
                    
                    <div class="lux-slider-track">
                        
                        <?php for($i = 0; $i < 2; $i++): ?>
                            <?php $__currentLoopData = $eliteBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="lux-brand-item">
                                    <div class="lux-brand-logo">
                                        <?php if(isset($eliteBrandImages[$brand['slug']])): ?>
                                            <img src="<?php echo e($eliteBrandImages[$brand['slug']]); ?>" alt="<?php echo e($brand['name']); ?>">
                                        <?php else: ?>
                                            <span class="text-sm font-black text-slate-400 uppercase tracking-widest"><?php echo e($brand['name']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="lux-brand-name"><?php echo e($brand['name']); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            </div>
        <?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => ['variant' => 'modern','siteLogo' => $siteLogo ?? null,'siteName' => $siteName ?? null,'description' => $footerDesc ?? null,'address' => $footerAddress ?? null,'email' => $footerEmail ?? null,'phone' => $footerPhone ?? null,'socials' => $footerSocials ?? null,'quickLinks' => $footerQuickLinks ?? [],'pages' => $footerPages ?? [],'copyright' => $footerCopy ?? null,'termsUrl' => $footerTerms ?? '#','privacyUrl' => $footerPrivacy ?? '#','cookiesUrl' => $footerCookies ?? '#','bgColor' => '#e7e7e7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'modern','siteLogo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siteLogo ?? null),'siteName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($siteName ?? null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerDesc ?? null),'address' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerAddress ?? null),'email' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerEmail ?? null),'phone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPhone ?? null),'socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerSocials ?? null),'quickLinks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerQuickLinks ?? []),'pages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPages ?? []),'copyright' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCopy ?? null),'termsUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerTerms ?? '#'),'privacyUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPrivacy ?? '#'),'cookiesUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCookies ?? '#'),'bgColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('#e7e7e7')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
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

        // Section Order Reordering
        (function() {
            const container = document.getElementById('sectionsContainer');
            if (!container) return;
            
            const sectionOrder = JSON.parse(container.dataset.sectionOrder || '{}');
            const sectionIds = ['trust_badges', 'services', 'google_reviews', 'location', 'featured_cars', 'brand_logos', 'blog'];
            
            // Get all section elements
            const sectionElements = [];
            sectionIds.forEach(key => {
                const el = document.getElementById('section-' + key);
                if (el) {
                    sectionElements.push({
                        key,
                        el,
                        order: parseInt(sectionOrder[key]) || 999
                    });
                }
            });
            
            // Sort by order
            sectionElements.sort((a, b) => a.order - b.order);
            
            // Reorder inside container
            sectionElements.forEach((section) => {
                container.appendChild(section.el);
            });
        })();
    </script>
</body>

</html>
<?php
    $trustStripBg = data_get($page?->content, 'trust_strip_bg', '#e7e7e7');
?>
<?php /**PATH F:\auction_app\resources\views/home2.blade.php ENDPATH**/ ?>