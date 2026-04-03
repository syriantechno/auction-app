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

    {{-- Professional Footer: Vehica Style --}}
    @php
        $footerColor = data_get($page?->content, 'footer.background_color', '#eef3f9');
    @endphp
    <footer class="text-slate-900 pt-24 pb-12 overflow-hidden relative transition-colors duration-500" style="background-color: {{ $footerColor }};">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-bazar-500/5 to-transparent pointer-events-none"></div>
        
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                {{-- Column 1: Brand --}}
                <div class="space-y-8">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-[#031629] shadow-xl shadow-slate-200/70 border border-slate-200">
                            <i data-lucide="car-front" class="w-7 h-7 text-[#031629]"></i>
                        </div>
                    </a>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium">
                        The world's most trusted platform for premium car auctions. We bring the auction room to your screen with transparency and class.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-bazar-500 transition-all border border-slate-200 shadow-sm">
                            <i data-lucide="share-2" class="w-4 h-4 text-slate-900"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-bazar-500 transition-all border border-slate-200 shadow-sm">
                            <i data-lucide="camera" class="w-4 h-4 text-slate-900"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:bg-bazar-500 transition-all border border-slate-200 shadow-sm">
                            <i data-lucide="message-circle" class="w-4 h-4 text-slate-900"></i>
                        </a>
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div>
                    <h4 class="text-lg font-black mb-8 relative inline-block text-slate-900">
                        Quick Links
                        <span class="absolute -bottom-2 left-0 w-8 h-1 bg-bazar-500 rounded-full"></span>
                    </h4>
                    <ul class="space-y-4">
                        <li><a href="/" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-bold flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform"></i> Home</a></li>
                        <li><a href="{{ route('auctions.index') }}" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-bold flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform"></i> Browse Auctions</a></li>
                        <li><a href="{{ route('how-it-works') }}" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-bold flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform"></i> How it Works</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-bold flex items-center gap-2 group"><i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform"></i> Sell Your Car</a></li>
                    </ul>
                </div>

                {{-- Column 3: Contact Info --}}
                <div>
                    <h4 class="text-lg font-black mb-8 relative inline-block text-slate-900">
                        Contact Us
                        <span class="absolute -bottom-2 left-0 w-8 h-1 bg-bazar-500 rounded-full"></span>
                    </h4>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-md bg-bazar-500/10 flex items-center justify-center shrink-0 border border-bazar-500/10">
                                <i data-lucide="map-pin" class="w-5 h-5 text-bazar-500"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black uppercase text-slate-500 mb-1">Our Location</div>
                                <div class="text-sm font-bold text-slate-900">123 Luxury Drive, Dubai, UAE</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-md bg-bazar-500/10 flex items-center justify-center shrink-0 border border-bazar-500/10">
                                <i data-lucide="mail" class="w-5 h-5 text-bazar-500"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black uppercase text-slate-500 mb-1">Email Us</div>
                                <div class="text-sm font-bold text-slate-900">contact@motorbazar.com</div>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Column 4: Newsletter --}}
                <div>
                    <h4 class="text-lg font-black mb-8 relative inline-block text-slate-900">
                        Stay Updated
                        <span class="absolute -bottom-2 left-0 w-8 h-1 bg-bazar-500 rounded-full"></span>
                    </h4>
                    <p class="text-slate-600 text-sm mb-6 font-medium">Subscribe to receive the latest auction alerts.</p>
                    <form class="relative">
                        <input type="email" placeholder="Your email address" class="w-full bg-white border border-slate-200 rounded-lg py-4 px-6 text-sm font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-bazar-500 transition-all shadow-sm">
                        <button class="absolute right-2 top-2 bottom-2 bg-bazar-500 p-3 rounded-md hover:bg-bazar-600 transition-all text-white">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div class="pt-12 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest leading-loose">
                    &copy; {{ date('Y') }} MOTOR BAZAR. ALL RIGHTS RESERVED.
                </p>
                <div class="flex gap-8">
                    <a href="#" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Terms</a>
                    <a href="#" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Privacy</a>
                    <a href="#" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>

