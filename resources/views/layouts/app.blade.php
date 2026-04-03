<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteName = \App\Models\SystemSetting::get('site_name', 'Laravel');
        $siteLogo = \App\Models\SystemSetting::get('site_logo');
        $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
        $page = \App\Models\Page::where('slug', 'home')->first();
        $navbarContent = data_get($page?->content, 'navbar', []);
        $navbarPhone = data_get($navbarContent, 'phone', '+1 (234) 567 890');
        $navbarHours = data_get($navbarContent, 'hours', 'Mon - Fri: 9:00 - 18:00');
        $isSticky = (bool) data_get($navbarContent, 'sticky', true);
        $isGlass = (bool) data_get($navbarContent, 'glass', true);
    @endphp
    <title>@yield('title', $siteName . ' - Premium Car Auctions')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/js/app.js'])

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @if($googleMapsKey)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places"></script>
    @endif

    <script>
        window.googleMapsKey = "{{ $googleMapsKey }}";
        window.mapProvider = "{{ \App\Models\SystemSetting::get('google_maps_provider', 'google') }}";
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        bazar: {
                            500: '#ff4605',
                            600: '#e03d04',
                        },
                        deep: {
                            800: '#1a1d26',
                            900: '#12141b',
                            950: '#0e1017',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #e7e7e7;
            color: #12141b;
            -webkit-font-smoothing: antialiased;
        }
        .nav-link {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            transition: all 0.3s ease;
            padding: 8px 12px;
        }
        .nav-link:hover { color: #ff4605; }
        
        .sticky-nav {
            background: {{ $isGlass ? 'rgba(255, 255, 255, 0.7)' : 'white' }};
            backdrop-filter: {{ $isGlass ? 'blur(12px)' : 'none' }};
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .static-nav {
            background: white;
            position: relative !important;
            box-shadow: none;
            border-bottom: 1px solid #f1f5f9;
        }

        .btn-bazar {
            background: #ff4605;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-bazar:hover {
            background: #e03d04;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(255, 70, 5, 0.4);
        }

        .floating-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
        }
    </style>
    @yield('head')
</head>
<body class="font-sans">

    {{-- Universal Header: Vehica Style --}}
    <nav class="{{ $isSticky ? 'sticky-nav' : 'static-nav' }} fixed w-full z-50 px-2 lg:px-4 top-0 transition-all duration-300">
        <div class="w-full flex justify-start items-center h-24 gap-8">
            {{-- Brand Logo --}}
            <a href="/" class="flex items-center gap-2 group">
                {{-- Logo Container --}}
                <div class="h-20 flex items-center">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" class="h-full w-auto object-contain filter invert-[1] hue-rotate-[180deg] brightness-[1.1] contrast-[1.5] saturate-[1.8]">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-white flex items-center justify-center text-[#031629] shadow-xl shadow-slate-200/70 border border-slate-200">
                            <i data-lucide="car-front" class="w-10 h-10 text-[#031629]"></i>
                        </div>
                    @endif
                </div>
            </a>

            {{-- Navigation Links (Left-aligned) --}}
            <div class="hidden lg:flex items-center gap-3">
                @if(isset($headerMenu) && $headerMenu->items)
                    @foreach($headerMenu->items as $item)
                        @if($item->children->count() > 0)
                            <div class="relative group flex items-center h-full">
                                <button class="nav-link flex items-center gap-1.5 h-full">
                                    {{ $item->label }}
                                    <i data-lucide="chevron-down" class="w-3.5 opacity-50 group-hover:rotate-180 transition-transform"></i>
                                </button>
                                {{-- Dropdown --}}
                                <div class="absolute top-[80%] left-0 w-56 bg-white rounded-lg shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60]">
                                    @foreach($item->children as $child)
                                        <a href="{{ $child->url }}" class="block px-4 py-3 rounded-md text-[0.75rem] font-bold text-deep-900 hover:bg-gray-50 transition-all">
                                            {{ $child->label }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->url }}" class="nav-link {{ request()->url() == $item->url ? 'text-bazar-500' : '' }}">
                                {{ $item->label }}
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>

            {{-- Right Side Actions (moved to the end) --}}
            <div class="flex items-center gap-3 ml-auto">
                <div class="hidden md:flex flex-col items-end gap-0.5">
                    <div class="flex items-center gap-1.5 font-black text-[0.85rem] text-slate-900 group">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-bazar-500 fill-bazar-500/10"></i>
                        <a href="tel:{{ $navbarPhone }}" class="hover:text-bazar-500 transition-colors">{{ $navbarPhone }}</a>
                    </div>
                    <div class="text-[0.55rem] font-bold text-slate-400 uppercase tracking-widest">{{ $navbarHours }}</div>
                </div>

                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-3 bg-gray-50 hover:bg-gray-100 p-1.5 pr-4 rounded-lg transition-all border border-gray-100">
                            <div class="w-9 h-9 rounded-md overflow-hidden border-2 border-white shadow-sm shrink-0">
                                <img src="https://i.pravatar.cc/100?u={{ auth()->id() }}" class="w-full h-full object-cover">
                            </div>
                            <div class="text-left">
                                <div class="text-[0.7rem] font-black text-deep-900 leading-none truncate max-w-[100px]">{{ auth()->user()->name }}</div>
                                <div class="text-[0.55rem] text-bazar-500 font-bold uppercase tracking-widest mt-1">Authorized</div>
                            </div>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 group-hover:rotate-180 transition-transform ml-1"></i>
                        </button>
                        {{-- Profile Dropdown --}}
                        <div class="absolute right-0 mt-3 w-56 bg-white rounded-lg shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60]">
                            <div class="p-4 border-b border-gray-50 mb-1">
                                <div class="text-sm font-black text-deep-900 truncate">{{ auth()->user()->name }}</div>
                                <div class="text-[0.6rem] text-gray-400 font-bold uppercase mt-0.5 tracking-widest">Premium Member</div>
                            </div>
                            <a href="{{ route('user.bids') }}" class="flex items-center gap-3 px-4 py-3 rounded-md text-xs font-bold text-gray-600 hover:bg-gray-50">
                                <i data-lucide="gavel" class="w-4"></i> My Bids
                            </a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-md text-xs font-bold text-gray-600 hover:bg-gray-50">
                                    <i data-lucide="shield-check" class="w-4"></i> Dashboard
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="mt-1 border-t border-gray-50 pt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-md text-xs font-bold text-red-500 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-4"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-[0.85rem] font-bold text-deep-900 border-b-2 border-transparent hover:border-bazar-500 transition-all pb-1 hidden sm:block">Login</a>
                    <a href="#" class="btn-bazar flex items-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Sell My Car</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Professional Footer: CMS Controlled --}}
    @php
        $footerColor   = data_get($page?->content, 'footer.background_color', '#eef3f9');
        $footerDesc    = data_get($page?->content, 'footer.description', "The world's most trusted platform for premium car auctions. We bring the auction room to your screen with transparency and class.");
        $footerAddress = data_get($page?->content, 'footer.address', '123 Luxury Drive, Dubai, UAE');
        $footerEmail   = data_get($page?->content, 'footer.email', 'contact@motorbazar.com');
        $footerPhone   = data_get($page?->content, 'footer.phone', '+971 4 000 0000');
        $footerCopy    = data_get($page?->content, 'footer.copyright', '&copy; ' . date('Y') . ' MOTOR BAZAR. ALL RIGHTS RESERVED.');
        $footerTerms   = data_get($page?->content, 'footer.terms_url', '#');
        $footerPrivacy = data_get($page?->content, 'footer.privacy_url', '#');
        $footerCookies = data_get($page?->content, 'footer.cookies_url', '#');
        $socialFb      = data_get($page?->content, 'footer.social.facebook', '');
        $socialIg      = data_get($page?->content, 'footer.social.instagram', '');
        $socialWa      = data_get($page?->content, 'footer.social.whatsapp', '');
        $socialYt      = data_get($page?->content, 'footer.social.youtube', '');
        $footerQuickLinks = data_get($page?->content, 'footer.quick_links', [
            ['label' => 'Home',            'url' => '/'],
            ['label' => 'Browse Auctions', 'url' => route('auctions.index')],
            ['label' => 'How it Works',    'url' => route('how-it-works')],
            ['label' => 'Sell Your Car',   'url' => '#'],
        ]);
        $footerPages = data_get($page?->content, 'footer.pages', []);
    @endphp

    <footer class="text-slate-900 pt-20 pb-12 overflow-hidden relative transition-colors duration-500" style="background-color: {{ $footerColor }};">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-bazar-500/5 to-transparent pointer-events-none"></div>
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">

                {{-- Column 1: Brand --}}
                <div class="lg:col-span-2 space-y-6">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-xl border border-slate-200">
                            <i data-lucide="car-front" class="w-7 h-7 text-[#031629]"></i>
                        </div>
                    </a>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium max-w-sm">{{ $footerDesc }}</p>
                    <div class="flex gap-3 flex-wrap">
                        @if($socialFb)
                        <a href="{{ $socialFb }}" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-bazar-500 hover:text-white transition-all border border-slate-200 shadow-sm" title="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if($socialIg)
                        <a href="{{ $socialIg }}" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-bazar-500 hover:text-white transition-all border border-slate-200 shadow-sm" title="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        @endif
                        @if($socialWa)
                        <a href="{{ $socialWa }}" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-green-500 hover:text-white transition-all border border-slate-200 shadow-sm" title="WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        @endif
                        @if($socialYt)
                        <a href="{{ $socialYt }}" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-red-500 hover:text-white transition-all border border-slate-200 shadow-sm" title="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Quick Links
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        @foreach($footerQuickLinks as $link)
                        <li>
                            <a href="{{ data_get($link,'url','#') }}"
                               class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform shrink-0"></i>
                                {{ data_get($link,'label','') }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Column 3: Pages (Internal — page builder) --}}
                <div>
                    @if(!empty($footerPages))
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Pages
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        @foreach($footerPages as $pg)
                        <li>
                            <a href="{{ data_get($pg,'url','#') }}"
                               class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform shrink-0"></i>
                                {{ data_get($pg,'label','') }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- Column 4: Contact --}}
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Contact Us
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-4">
                        @if($footerAddress)
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-bazar-500 mt-0.5 shrink-0"></i>
                            <span class="text-sm font-medium text-slate-600">{{ $footerAddress }}</span>
                        </li>
                        @endif
                        @if($footerEmail)
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-bazar-500 shrink-0"></i>
                            <a href="mailto:{{ $footerEmail }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">{{ $footerEmail }}</a>
                        </li>
                        @endif
                        @if($footerPhone)
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-bazar-500 shrink-0"></i>
                            <a href="tel:{{ $footerPhone }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">{{ $footerPhone }}</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">{!! $footerCopy !!}</p>
                <div class="flex gap-6">
                    @if($footerTerms && $footerTerms !== '#')
                    <a href="{{ $footerTerms }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Terms</a>
                    @endif
                    @if($footerPrivacy && $footerPrivacy !== '#')
                    <a href="{{ $footerPrivacy }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Privacy</a>
                    @endif
                    @if($footerCookies && $footerCookies !== '#')
                    <a href="{{ $footerCookies }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Cookies</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>

        {{-- Decorative Elements --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>

