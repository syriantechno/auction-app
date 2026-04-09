<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        // ── Cached layout data (10 min) — prevents N+1 DB hits on every page load ──
        $layoutCache = \Illuminate\Support\Facades\Cache::remember('layout.app.globals', now()->addMinutes(10), function () {
            $siteName  = \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
            $siteLogo  = \App\Models\SystemSetting::get('site_logo');
            $googleKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY', ''));
            $page      = \App\Models\Page::where('slug', 'home')->where('is_published', true)->first();

            // Social links
            $allSocialKeys = ['facebook','instagram','tiktok','youtube','x','linkedin','whatsapp'];
            $footerSocials = [];
            $navSocials    = [];
            foreach ($allSocialKeys as $sk) {
                $url = \App\Models\SystemSetting::get('social_' . $sk, '');
                if ($url) {
                    if (\App\Models\SystemSetting::get('social_' . $sk . '_show_nav', '0') === '1') {
                        $navSocials[$sk] = $url;
                    }
                    if (\App\Models\SystemSetting::get('social_' . $sk . '_show_footer', '0') === '1') {
                        $footerSocials[$sk] = $url;
                    }
                }
            }

            return compact('siteName', 'siteLogo', 'googleKey', 'page', 'footerSocials', 'navSocials');
        });

        $siteName      = $layoutCache['siteName'];
        $siteLogo      = $layoutCache['siteLogo'];
        $googleMapsKey = $layoutCache['googleKey'];
        $page          = $layoutCache['page'];
        $footerSocials = $layoutCache['footerSocials'];
        $navSocials    = $layoutCache['navSocials'];

        $navbarContent   = data_get($page?->content, 'navbar', []);
        $navbarPhone     = data_get($navbarContent, 'phone', '+1 (234) 567 890');
        $navbarHours     = data_get($navbarContent, 'hours', 'Mon - Fri: 9:00 - 18:00');
        $isSticky        = (bool) data_get($navbarContent, 'sticky', true);
        $isGlass         = (bool) data_get($navbarContent, 'glass', true);
        $navbarBgColor   = data_get($navbarContent, 'bg_color', '#ffffff');
        $navbarTextColor = data_get($navbarContent, 'text_color', '#0d121f');
    ?>

    <title><?php echo $__env->yieldContent('seo_title', $page?->seo_title ?: ($siteName . ' - Premium Car Auctions')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', $page?->seo_description ?: 'The world\'s most trusted platform for premium car auctions.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', $page?->seo_keywords ?: 'car auctions, used cars, motor bazar'); ?>">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <meta name="robots" content="index, follow">
    
    
    <meta property="og:title" content="<?php echo $__env->yieldContent('seo_title', $page?->seo_title ?: $siteName); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', $page?->seo_description ?: 'Premium Car Auctions'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('storage/' . ($siteLogo ?? 'default.png'))); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">

    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('seo_title', $page?->seo_title ?: $siteName); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description', $page?->seo_description ?: 'Premium Car Auctions'); ?>">
    <meta name="twitter:image" content="<?php echo $__env->yieldContent('og_image', asset('storage/' . ($siteLogo ?? 'default.png'))); ?>">

    
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('apple-touch-icon.png')); ?>">

    
    <?php echo $__env->yieldContent('seo_schema'); ?>
    
    
    <?php if(request()->is('/')): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?php echo e($siteName); ?>",
      "url": "<?php echo e(url('/')); ?>",
      "logo": "<?php echo e(asset('storage/' . ($siteLogo ?? 'default.png'))); ?>",
      "sameAs": [
        <?php $__currentLoopData = $footerSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($fUrl); ?>"<?php if (! ($loop->last)): ?>,<?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "<?php echo e(url('/')); ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo e(url('/auctions?q={search_term_string}')); ?>",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <?php endif; ?>
    
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <?php if($googleMapsKey): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsKey); ?>&libraries=places"></script>
    <?php endif; ?>

    <script>
        window.googleMapsKey = "<?php echo e($googleMapsKey); ?>";
        window.mapProvider = "<?php echo e(\App\Models\SystemSetting::get('google_maps_provider', 'google')); ?>";
    </script>

    
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="font-sans font-light">

    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => ['variant' => 'modern','siteLogo' => $adminSiteLogo ?? null,'siteName' => $adminSiteName ?? null,'phone' => $navbarPhone ?? null,'hours' => $navbarHours ?? null,'isSticky' => $isSticky ?? true,'menu' => $headerMenu ?? null,'socials' => $navSocials ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'modern','siteLogo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($adminSiteLogo ?? null),'siteName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($adminSiteName ?? null),'phone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navbarPhone ?? null),'hours' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navbarHours ?? null),'isSticky' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isSticky ?? true),'menu' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($headerMenu ?? null),'socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($navSocials ?? null)]); ?>
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

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => ['variant' => 'modern','siteLogo' => $adminSiteLogo ?? null,'siteName' => $adminSiteName ?? null,'description' => $footerDesc ?? null,'address' => $footerAddress ?? null,'email' => $footerEmail ?? null,'phone' => $footerPhone ?? null,'socials' => $footerSocials ?? null,'quickLinks' => $footerQuickLinks ?? [],'pages' => $footerPages ?? [],'copyright' => $footerCopy ?? null,'termsUrl' => $footerTerms ?? '#','privacyUrl' => $footerPrivacy ?? '#','cookiesUrl' => $footerCookies ?? '#','bgColor' => $footerColor ?? '#eef3f9']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'modern','siteLogo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($adminSiteLogo ?? null),'siteName' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($adminSiteName ?? null),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerDesc ?? null),'address' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerAddress ?? null),'email' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerEmail ?? null),'phone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPhone ?? null),'socials' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerSocials ?? null),'quickLinks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerQuickLinks ?? []),'pages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPages ?? []),'copyright' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCopy ?? null),'termsUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerTerms ?? '#'),'privacyUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerPrivacy ?? '#'),'cookiesUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerCookies ?? '#'),'bgColor' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($footerColor ?? '#eef3f9')]); ?>
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

        

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>

<?php /**PATH F:\auction_app\resources\views/layouts/app.blade.php ENDPATH**/ ?>