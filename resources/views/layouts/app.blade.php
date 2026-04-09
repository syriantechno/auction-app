<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
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
    @endphp

    <title>@yield('seo_title', $page?->seo_title ?: ($siteName . ' - Premium Car Auctions'))</title>
    <meta name="description" content="@yield('meta_description', $page?->seo_description ?: 'The world\'s most trusted platform for premium car auctions.')">
    <meta name="keywords" content="@yield('meta_keywords', $page?->seo_keywords ?: 'car auctions, used cars, motor bazar')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index, follow">
    
    {{-- Social Media Injection (Open Graph) --}}
    <meta property="og:title" content="@yield('seo_title', $page?->seo_title ?: $siteName)">
    <meta property="og:description" content="@yield('meta_description', $page?->seo_description ?: 'Premium Car Auctions')">
    <meta property="og:image" content="@yield('og_image', asset('storage/' . ($siteLogo ?? 'default.png')))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Twitter Grid Integration --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', $page?->seo_title ?: $siteName)">
    <meta name="twitter:description" content="@yield('meta_description', $page?->seo_description ?: 'Premium Car Auctions')">
    <meta name="twitter:image" content="@yield('og_image', asset('storage/' . ($siteLogo ?? 'default.png')))">

    {{-- Universal Assets --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    {{-- JSON-LD Schema Injection --}}
    @yield('seo_schema')
    
    {{-- GLOBAL SCHEMAS (Organization & SearchAction) --}}
    @if(request()->is('/'))
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "{{ $siteName }}",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('storage/' . ($siteLogo ?? 'default.png')) }}",
      "sameAs": [
        @foreach($footerSocials as $fUrl) "{{ $fUrl }}"@unless($loop->last),@endunless @endforeach
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/auctions?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    @endif
    
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @if($googleMapsKey)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places"></script>
    @endif

    <script>
        window.googleMapsKey = "{{ $googleMapsKey }}";
        window.mapProvider = "{{ \App\Models\SystemSetting::get('google_maps_provider', 'google') }}";
    </script>

    
    @yield('head')
</head>
<body class="font-sans font-light">

    <x-navbar 
        variant="modern" 
        :siteLogo="$adminSiteLogo ?? null"
        :siteName="$adminSiteName ?? null"
        :phone="$navbarPhone ?? null"
        :hours="$navbarHours ?? null"
        :isSticky="$isSticky ?? true"
        :menu="$headerMenu ?? null"
        :socials="$navSocials ?? null"
    />

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    <x-footer 
        variant="modern"
        :siteLogo="$adminSiteLogo ?? null"
        :siteName="$adminSiteName ?? null"
        :description="$footerDesc ?? null"
        :address="$footerAddress ?? null"
        :email="$footerEmail ?? null"
        :phone="$footerPhone ?? null"
        :socials="$footerSocials ?? null"
        :quickLinks="$footerQuickLinks ?? []"
        :pages="$footerPages ?? []"
        :copyright="$footerCopy ?? null"
        :termsUrl="$footerTerms ?? '#'"
        :privacyUrl="$footerPrivacy ?? '#'"
        :cookiesUrl="$footerCookies ?? '#'"
        :bgColor="$footerColor ?? '#eef3f9'"
    />

        {{-- Decorative Elements --}}

    @yield('scripts')
</body>
</html>

