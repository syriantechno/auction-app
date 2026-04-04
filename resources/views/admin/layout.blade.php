<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        // These are now shared via AppServiceProvider — safe fallback if not set
        if (!isset($adminSiteName)) $adminSiteName = \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
        if (!isset($adminSiteLogo)) $adminSiteLogo = \App\Models\SystemSetting::get('site_logo');
        if (!isset($adminSiteFavicon)) $adminSiteFavicon = \App\Models\SystemSetting::get('site_favicon');
        if (!isset($appCurrencySymbol)) $appCurrencySymbol = \App\Helpers\CurrencyHelper::symbol();
        if (!isset($appCurrencyCode)) $appCurrencyCode = \App\Models\SystemSetting::get('site_currency', 'AED');
        if (!isset($appCurrencyPos)) $appCurrencyPos = \App\Models\SystemSetting::get('currency_position', 'before');
        if (!isset($appDateFormat)) $appDateFormat = \App\Models\SystemSetting::get('date_format', 'd/m/Y');
        $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
    @endphp
    <title>{{ $adminSiteName }} Admin | @yield('title')</title>
    @if($adminSiteFavicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $adminSiteFavicon) }}">
    @endif


    <!-- Design System: Jakarta Sans & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Premium Pickers: Flatpickr Matrix -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.googleMapsKey = "{{ $googleMapsKey }}";
        window.mapProvider = "{{ \App\Models\SystemSetting::get('google_maps_provider', 'google') }}";
        // Currency globals — used by any JS that formats money
        window.appCurrency = {
            code:     "{{ $appCurrencyCode }}",
            symbol:   "{{ $appCurrencySymbol }}",
            position: "{{ $appCurrencyPos }}",
        };
        window.appDateFormat = "{{ $appDateFormat }}";

    </script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @if($googleMapsKey)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places"></script>
    @endif

    <!-- Alerts & Notifications: SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-orange: #ff6900;
            --primary-navy: #1d293d;
            --bg-slate: #f1f5f9;
            --text-main: #111827;
        }

        /* Flatpickr Custom Theme (Bazar-Orange #ff6900) */
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
            background: #ff6900 !important; border-color: #ff6900 !important;
        }
        .flatpickr-calendar {
            border-radius: 0.5rem !important;
            box-shadow: 0 25px 50px -12px rgba(255, 105, 0, 0.15) !important;
            border: 1px solid #f1f5f9 !important;
        }
        .flatpickr-calendar.noCalendar {
            width: 150px !important; min-width: 150px !important; border-radius: 1rem !important;
        }
        .flatpickr-time input { font-weight: 800 !important; color: #ff4605 !important; }
        .flatpickr-time .flatpickr-am-pm { font-weight: 900 !important; color: #64748b !important; }

        /* The Geometric Standard (Compact Policy) */
        .rounded-lg { border-radius: 0.5rem !important; }
        .rounded-md { border-radius: 0.375rem !important; }

        [x-cloak] { display: none !important; }

        /*
         * ANTI-FLASH SIDEBAR: width is controlled by CSS class on <html>,
         * set synchronously before first paint (see script below).
         * Alpine only toggles the class — never sets width directly.
         */
        html:not(.sidebar-collapsed) #admin-sidebar { width: 260px; }
        html.sidebar-collapsed        #admin-sidebar { width: 80px;  }
        /* Transition added only AFTER Alpine loads to prevent initial animation */
        #admin-sidebar.sidebar-ready  { transition: width 0.25s cubic-bezier(0.4,0,0.2,1); }

        /* Global Typography Policy (Elegant Clean) */
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 400; }
        .font-bold, .font-black, .font-extrabold { font-weight: 500 !important; }
        
        .sidebar-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-scroll::-webkit-scrollbar { width: 0px; }
        
        /* Modern Input Global Reset */
        input, select, textarea { 
            @apply rounded-md border-slate-200 transition-all focus:border-[#ff6900] focus:ring-4 focus:ring-orange-500/5;
        }
    </style>

    @stack('head')

    {{-- ANTI-FLASH: runs synchronously before first paint --}}
    <script>
        (function(){
            // 1. Set sidebar collapsed class immediately
            if (localStorage.getItem('sidebarOpen') === 'false') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
            // 2. Hide body until Alpine is ready (prevents all x-show FOUC)
            document.documentElement.classList.add('alpine-loading');
        })();
    </script>
    <style>
        /* Hide page until Alpine initializes — prevents ALL x-show/x-cloak flash */
        html.alpine-loading body { opacity: 0; }
        html body { transition: opacity 0.1s ease; }
    </style>
</head>

<body class="antialiased text-[#111827] bg-[#e7e7e7]">

    <div class="flex h-screen overflow-hidden" x-data="{
        sidebarOpen: !document.documentElement.classList.contains('sidebar-collapsed'),
        openCRM: {{ request()->routeIs('admin.leads.*') || request()->routeIs('admin.inspections.*') ? 'true' : 'false' }}
    }" x-init="
        // Remove loading class — page becomes visible after Alpine processes all x-show
        $nextTick(() => {
            document.documentElement.classList.remove('alpine-loading');
            // Enable sidebar transition AFTER initial render
            setTimeout(() => document.getElementById('admin-sidebar')?.classList.add('sidebar-ready'), 0);
        });
        $watch('sidebarOpen', v => {
            localStorage.setItem('sidebarOpen', v);
            document.documentElement.classList.toggle('sidebar-collapsed', !v);
        });
    ">

        <!-- Sidebar -->
        <aside id="admin-sidebar"
            class="bg-white border-r border-[#f1f5f9] flex flex-col relative z-40 overflow-hidden shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            
            @php
                $primaryAdminWord = explode(' ', $adminSiteName)[0] ?? 'Motor';
                $secondaryAdminWord = explode(' ', $adminSiteName)[1] ?? 'Bazar';
            @endphp
            <div class="h-[120px] flex items-center px-4 border-b border-[#f1f5f9] overflow-hidden whitespace-nowrap flex-shrink-0">
                <div class="flex items-center gap-1 min-w-max">
                    @if($adminSiteLogo)
                        <img src="{{ asset('storage/' . $adminSiteLogo) }}" class="w-20 h-20 object-contain rounded-lg filter invert-[1] hue-rotate-[180deg] brightness-[1.1] contrast-[1.5] saturate-[1.8]">
                    @else
                        <div class="w-16 h-16 bg-slate-800 rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-white font-medium text-2xl tracking-tighter italic">{{ strtoupper(substr($adminSiteName, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="-ml-1">
                        <h1 class="font-bold text-[1.1rem] tracking-tight leading-none italic uppercase" style="color: #1d293d !important;">
                            {{ $primaryAdminWord }}<span style="color: #ff6900 !important;">{{ $secondaryAdminWord }}</span>
                        </h1>
                        <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-[0.1em] mt-2 block italic opacity-60">Elite Admin Suite</span>
                    </div>
                </div>
            </div>

            <!-- Navigation: Absolute Restore -->
            <nav class="flex-1 overflow-y-auto p-4 sidebar-scroll space-y-6 mt-2 pb-10">

                {{-- Global Dashboard --}}
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.dashboard') ? 'text-slate-900 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-[#ff6900]' : 'text-slate-400' }}"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span x-show="sidebarOpen" x-cloak class="truncate">Dashboard</span>
                    </a>
                </div>

                {{-- Group 1: CRM & Operations (Dropdown Mode) --}}
                <div class="space-y-1">
                    <button @click="openCRM = !openCRM" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-slate-500 hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 flex-shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span x-show="sidebarOpen" x-cloak>CRM & Operations</span>
                        </div>
                        {{-- Inline SVG chevron - no lucide re-render --}}
                        <svg x-show="sidebarOpen" x-cloak :class="openCRM ? 'rotate-180' : ''"
                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="transition-transform duration-200 text-slate-400 flex-shrink-0">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    
                    <ul x-show="openCRM" x-cloak x-collapse class="pl-12 space-y-1 mt-1 border-l-2 border-slate-50 ml-6">
                        <li>
                            <a href="{{ route('admin.leads.index') }}" class="block py-2 text-[0.75rem] font-medium {{ request()->routeIs('admin.leads.*') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800' }}">Leads</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.inspections.calendar') }}" class="block py-2 text-[0.75rem] font-medium {{ request()->routeIs('admin.inspections.calendar') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800' }}">Inspections Calendar</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.inspections.tasks') }}" class="block py-2 text-[0.75rem] font-medium {{ request()->routeIs('admin.inspections.tasks') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800' }}">Field Tasks</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.inspections.index') }}" class="block py-2 text-[0.75rem] font-medium {{ (request()->routeIs('admin.inspections.*') && !request()->routeIs('admin.inspections.calendar') && !request()->routeIs('admin.inspections.tasks')) ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800' }}">Appraisal Reports</a>
                        </li>
                    </ul>
                </div>

                {{-- Group 2: Fleet Management --}}
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">Fleet Management</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.cars.index') }}"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.cars.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.cars.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11 2 11.1 2 11.2V16c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Vehicles</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.auctions.index') }}"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.auctions.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.auctions.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><path d="m6 15-4-4 6.7-6.7a2.1 2.1 0 1 1 3 3L5 14"/><path d="m15 13 4 4"/><path d="m21 11-8 8"/><path d="m21 15-8 8"/><path d="m10 11 8-8"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Auctions</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.stock.index') }}"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.stock.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.stock.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><path d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"/><path d="M6 18h12"/><path d="M6 14h12"/><rect x="8" y="10" width="8" height="12"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Stock</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dealers.index') }}"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.dealers.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.dealers.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Dealers</span>
                            </a>
                        </li>

                        {{-- ── Accounting Dropdown ──────────────────── --}}
                        <li x-data="{ open: {{ request()->routeIs('admin.finance.*') ? 'true' : 'false' }} }">
                            {{-- Dropdown Toggle --}}
                            <button @click="open = !open"
                                class="w-full sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold transition-all
                                    {{ request()->routeIs('admin.finance.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 {{ request()->routeIs('admin.finance.*') ? 'text-[#ff6900]' : 'text-slate-400' }}">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                <span x-show="sidebarOpen" x-cloak class="flex-1 text-left truncate">Accounting</span>
                                <svg x-show="sidebarOpen" x-cloak :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 text-slate-400 flex-shrink-0"><path d="m6 9 6 6 6-6"/></svg>
                            </button>

                            {{-- Sub-items --}}
                            <ul x-show="open && sidebarOpen" x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-1 ml-9 space-y-0.5 border-l-2 border-slate-100 pl-3">

                                @php
                                    $financeLinks = [
                                        ['route' => 'admin.finance.dashboard', 'label' => 'Overview',             'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                                        ['route' => 'admin.finance.invoices',  'label' => 'Invoices',             'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
                                        ['route' => 'admin.finance.receipts',  'label' => 'Receipts',             'svg' => '<line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/>'],
                                        ['route' => 'admin.finance.vouchers',  'label' => 'Payment Vouchers',     'svg' => '<line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>'],
                                        ['route' => 'admin.finance.accounts',  'label' => 'Cash & Bank Accounts', 'svg' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
                                    ];
                                @endphp

                                @foreach($financeLinks as $fl)
                                <li>
                                    <a href="{{ route($fl['route']) }}"
                                        class="flex items-center gap-2.5 px-2 py-2 rounded-md text-[0.72rem] font-bold transition-all
                                            {{ request()->routeIs($fl['route']) ? 'text-[#ff6900] bg-orange-50' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">{!! $fl['svg'] !!}</svg>
                                        {{ $fl['label'] }}
                                    </a>
                                </li>
                                @endforeach

                            </ul>
                        </li>

                    </ul>
                </div>

                {{-- Group 3: Editorial --}}
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">Content</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.cms.home') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium {{ request()->routeIs('admin.cms.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                                <i data-lucide="home" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Home CMS</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.posts.index') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium {{ request()->routeIs('admin.posts.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Blog Posts</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.pages.index') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium {{ request()->routeIs('admin.pages.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                                <i data-lucide="layers" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Static Pages</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.menus.index') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium {{ request()->routeIs('admin.menus.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                                <i data-lucide="menu" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Site Navigation</span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Group 4: Settings --}}
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">System</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.seo.dashboard') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium {{ request()->routeIs('admin.seo.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.seo.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <span x-show="sidebarOpen" x-cloak>SEO Intelligence</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.hub') }}" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold {{ request()->routeIs('admin.settings.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-[#ff6900]' : 'text-slate-400' }}"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span x-show="sidebarOpen" x-cloak>Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Group 5: Auth --}}
                <div class="pt-6 border-t border-slate-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-item w-full flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-red-500 hover:bg-red-50 transition-all italic">
                                <span x-show="sidebarOpen" x-cloak>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Toggle Controller -->
            <div class="p-4 border-t border-[#f1f5f9] flex justify-center flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm active:scale-90">
                    {{-- Inline SVG: no Lucide dependency, no re-render --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path x-show="!sidebarOpen" x-cloak d="m9 18 6-6-6-6"/>
                        <path x-show="sidebarOpen" x-cloak d="m15 18-6-6 6-6"/>
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#e7e7e7] overflow-hidden">
            <header class="h-[74px] bg-white border-b border-slate-100 flex items-center justify-between px-8 relative z-30 flex-shrink-0">
                <div class="flex items-center gap-4">
                     <div class="text-slate-500 font-bold text-[0.7rem] uppercase tracking-widest italic flex items-center gap-2">
                        {{ __('messages.location') }} <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i> 
                        <span class="text-slate-900">
                            @if(request()->segment(2) == 'leads') Leads
                            @elseif(request()->segment(2) == 'inspections')
                                @if(request()->segment(3) == 'calendar') Inspections Calendar
                                @elseif(request()->segment(3) == 'tasks') Field Tasks
                                @else Appraisal Reports
                                @endif
                            @else @yield('page_title', 'Dashboard')
                            @endif
                        </span>
                     </div>
                </div>

                <div class="flex items-center gap-6">

                    {{-- ── Notification Center Bell ── --}}
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-bell" onclick="toggleNotifPanel()"
                                class="relative w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                            <span id="notif-badge"
                                  class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] rounded-full bg-[#ff6900] text-white text-[0.5rem] font-black flex items-center justify-center px-1 hidden animate-bounce">0</span>
                        </button>

                        {{-- Notification Panel --}}
                        <div id="notif-panel"
                             class="hidden absolute top-[calc(100%+12px)] right-0 w-96 bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden z-50">
                            {{-- Header --}}
                            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                                <div>
                                    <div class="text-[0.7rem] font-black uppercase tracking-widest text-[#031629]">Notification Center</div>
                                    <div class="text-[0.55rem] text-slate-400 font-bold mt-0.5" id="notif-count-label">Loading...</div>
                                </div>
                                <button onclick="markAllRead()"
                                        class="px-3 py-1.5 bg-slate-50 rounded-lg text-[0.6rem] font-black uppercase tracking-widest text-slate-500 hover:bg-[#1d293d] hover:text-white transition-all">
                                    Mark all read
                                </button>
                            </div>

                            {{-- List --}}
                            <div id="notif-list" class="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
                                <div class="py-12 text-center text-[0.65rem] font-black uppercase tracking-widest text-slate-300">
                                    Loading...
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="px-6 py-3 border-t border-slate-50 text-center">
                                <span class="text-[0.55rem] font-black uppercase tracking-widest text-slate-300">Auto-refreshes every 15 seconds</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
                        <div class="text-right">
                            <p class="text-[0.75rem] font-bold text-slate-800 leading-none italic">{{ Auth::user()->name ?? 'Operator' }}</p>
                            <p class="text-[0.55rem] text-slate-400 uppercase tracking-widest mt-1">Administrator Access</p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-white text-xs shadow-md">
                           {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts Matrix -->
    <script>
        window.initBazarPickers = function(container = document) {
            if (window.flatpickr) {
                container.querySelectorAll('.bazar-date').forEach(el => {
                    flatpickr(el, { dateFormat: "d M Y", minDate: "today", disableMobile: true });
                });
                container.querySelectorAll('.bazar-time').forEach(el => {
                    flatpickr(el, { enableTime: true, noCalendar: true, dateFormat: "h:i K", time_24hr: false, disableMobile: true });
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            window.initBazarPickers();
        });
        
        window.addEventListener('resize', () => {
             const xDataElement = document.querySelector('[x-data]');
             if(xDataElement && xDataElement.__x && window.innerWidth < 1024) {
                 xDataElement.__x.$data.sidebarOpen = false;
             }
        });

        // ── Premium Stacked Glass Toast Engine ──────────────────────────────
        (function() {
            // Create toast container — top-right, below navbar
            const _tc = document.createElement('div');
            _tc.id = 'bazarToastContainer';
            _tc.style.cssText = 'position:fixed;top:86px;right:1.5rem;z-index:99999;display:flex;flex-direction:column;gap:0.6rem;max-width:360px;pointer-events:none;';
            document.body.appendChild(_tc);

            // Icon paths per type
            const _icons = {
                success: '<polyline points="20 6 9 17 4 12"/>',
                error:   '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
                warning: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
                info:    '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'
            };
            // One unified dark glass bg — only icon color changes
            const _typeConfig = {
                success: { icon: '#34d399', bg: 'rgba(52,211,153,0.18)',  label: 'Success' },
                error:   { icon: '#f87171', bg: 'rgba(239,68,68,0.18)',   label: 'Error'   },
                warning: { icon: '#fbbf24', bg: 'rgba(251,191,36,0.18)',  label: 'Warning' },
                info:    { icon: '#60a5fa', bg: 'rgba(96,165,250,0.18)',  label: 'Info'    },
            };

            window.showToast = function(msg, type = 'success', duration = 4500) {
                const t  = _typeConfig[type] || _typeConfig.info;
                const ic = _icons[type]      || _icons.info;

                const wrap = document.createElement('div');
                wrap.style.cssText = 'pointer-events:auto;';
                wrap.innerHTML = `
                    <div style="
                        display:flex;align-items:center;gap:12px;
                        background:rgba(10,15,28,0.82);
                        backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
                        border:1px solid rgba(255,255,255,0.09);
                        border-left:3px solid ${t.icon};
                        color:white;
                        padding:13px 16px;
                        border-radius:14px;
                        box-shadow:0 8px 32px rgba(0,0,0,0.5);
                        font-family:'Plus Jakarta Sans',sans-serif;
                        min-width:280px;max-width:340px;
                        opacity:0;
                        transform:translateX(1rem) scale(0.97);
                        transition:all 0.28s cubic-bezier(0.34,1.3,0.64,1);
                    ">
                        <div style="width:32px;height:32px;border-radius:9px;background:${t.bg};flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="${t.icon}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">${ic}</svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:${t.icon};line-height:1;">${t.label}</div>
                            <div style="font-size:0.78rem;color:rgba(255,255,255,0.82);font-weight:500;margin-top:3px;line-height:1.4;">${msg}</div>
                        </div>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="2.5"
                             style="flex-shrink:0;cursor:pointer;transition:stroke 0.15s;"
                             onmouseenter="this.setAttribute('stroke','rgba(255,255,255,0.7)')"
                             onmouseleave="this.setAttribute('stroke','rgba(255,255,255,0.25)')"
                             onclick="this.closest('div[style]').parentElement.remove()">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </div>`;

                _tc.appendChild(wrap);
                const inner = wrap.firstElementChild;

                // Animate IN — delay 10ms so browser registers initial opacity:0 state
                setTimeout(() => {
                    inner.style.opacity = '1';
                    inner.style.transform = 'translateX(0) scale(1)';
                }, 10);

                // Auto dismiss — slide OUT
                setTimeout(() => {
                    inner.style.transition = 'all 0.22s ease-in';
                    inner.style.opacity = '0';
                    inner.style.transform = 'translateX(2rem) scale(0.96)';
                    setTimeout(() => wrap.remove(), 230);
                }, duration);
            };

            // Backwards-compat
            window.notify = {
                success: (msg) => showToast(msg, 'success'),
                error:   (msg) => showToast(msg, 'error'),
                warning: (msg) => showToast(msg, 'warning'),
                info:    (msg) => showToast(msg, 'info'),
            };

            // Alpine.js $dispatch('show-toast', {message, type})
            window.addEventListener('show-toast', e => {
                const d = e.detail || {};
                showToast(d.message || d.msg || '', d.type || 'success');
            });

            // PHP session flash
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    showToast("{{ addslashes(session('success')) }}", 'success');
                @endif
                @if(session('error'))
                    showToast("{{ addslashes(session('error')) }}", 'error');
                @endif
                @if(session('warning'))
                    showToast("{{ addslashes(session('warning')) }}", 'warning');
                @endif
            });
        })();
    </script>

    {{-- ══════════════════════════════════════
         NOTIFICATION CENTER SYSTEM
    ══════════════════════════════════════ --}}
    <script>
    (function() {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const API  = {
            list:    '{{ route("admin.notifications.index") }}',
            count:   '{{ route("admin.notifications.count") }}',
            readAll: '{{ route("admin.notifications.read-all") }}',
            read: (id) => '{{ url("admin/notifications") }}/' + id + '/read',
        };
        const FETCH_OPTS = { credentials: 'same-origin', headers: { 'Accept': 'application/json' } };

        let panelOpen = false;
        let audioCtx  = null;

        // ── Server-side initial count (no fetch needed on first load) ──
        @auth
        let lastCount = {{ auth()->user()->unreadNotifications()->count() }};
        @else
        let lastCount = 0;
        @endauth

        // ── Tone alert ──
        function playAlertTone() {
            try {
                if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator(), gain = audioCtx.createGain();
                osc.connect(gain); gain.connect(audioCtx.destination);
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(440, audioCtx.currentTime + 0.25);
                gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.5);
                osc.start(audioCtx.currentTime);
                osc.stop(audioCtx.currentTime + 0.5);
            } catch(e) {}
        }

        // ── Badge ──
        function setBadge(count) {
            const badge = document.getElementById('notif-badge');
            const label = document.getElementById('notif-count-label');
            if (!badge) return;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.textContent = '0';
                badge.classList.add('hidden');
            }
            if (label) label.textContent = count > 0
                ? `${count} unread notification${count !== 1 ? 's' : ''}`
                : 'All caught up!';
        }

        // ── Render one notification row ──
        function renderItem(n) {
            const icons  = { 'user-round-plus': '👤', 'gavel': '🔨', 'bell': '🔔', 'dollar-sign': '💰' };
            const colors = { 'orange': 'bg-orange-50 text-orange-500', 'emerald': 'bg-emerald-50 text-emerald-500' };
            const icon  = icons[n.icon]  ?? '🔔';
            const color = colors[n.color] ?? 'bg-slate-50 text-slate-500';
            const unreadDot = !n.read ? '<span class="w-2 h-2 rounded-full bg-[#ff6900] flex-shrink-0"></span>' : '';
            const safeUrl = (n.url && n.url !== 'undefined' && n.url !== 'null') ? n.url : '#';
            return `<div class="flex gap-4 px-6 py-4 hover:bg-slate-50/70 transition-all cursor-pointer ${n.read ? 'opacity-60' : ''}"
                         onclick="window.readAndGo('${n.id}', '${safeUrl}')">
                <div class="w-9 h-9 rounded-xl ${color} flex items-center justify-center text-base flex-shrink-0">${icon}</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-[0.7rem] font-black text-[#031629]">${n.title}</span>
                        ${unreadDot}
                    </div>
                    <p class="text-[0.65rem] text-slate-500 font-medium leading-snug mt-0.5">${n.message}</p>
                    <span class="text-[0.55rem] text-slate-300 font-bold uppercase tracking-widest">${n.created_at}</span>
                </div>
            </div>`;
        }

        // ── Show toast ──
        function showNotifToast(n) {
            if (typeof showToast !== 'function') return;
            const title = n.title || 'Notification';
            const body  = (n.message || '').substring(0, 80);
            showToast(`<strong style="font-size:0.7rem">${title}</strong><br><span style="font-size:0.65rem;opacity:0.75">${body}</span>`, 'info', 6000);
        }

        // ── Fetch full notification list ──
        async function loadNotifications() {
            try {
                const res  = await fetch(API.list, FETCH_OPTS);
                if (!res.ok) { console.warn('[Notif] API returned', res.status); return; }
                const data = await res.json();
                const newCount = data.unread_count ?? 0;

                // Alert only when count INCREASES (new notification while on page)
                if (newCount > lastCount) {
                    playAlertTone();
                    const newest = (data.notifications ?? []).find(n => !n.read);
                    if (newest) showNotifToast(newest);
                }

                lastCount = newCount;
                setBadge(newCount);

                // Render list if panel is open
                const list = document.getElementById('notif-list');
                if (list && panelOpen) {
                    if (!data.notifications?.length) {
                        list.innerHTML = '<div class="py-12 text-center text-[0.65rem] font-black uppercase tracking-widest text-slate-300">No notifications yet.</div>';
                    } else {
                        list.innerHTML = data.notifications.map(renderItem).join('');
                    }
                }
            } catch(e) {
                console.warn('[Notif] Fetch error:', e.message);
            }
        }

        // ── Toggle panel ──
        window.toggleNotifPanel = function() {
            const panel = document.getElementById('notif-panel');
            if (!panel) return;
            panelOpen = !panelOpen;
            panel.classList.toggle('hidden', !panelOpen);
            if (panelOpen) loadNotifications();
        };

        // ── Mark read & navigate ──
        window.readAndGo = function(id, url) {
            fetch(API.read(id), {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            }).then(() => loadNotifications()).catch(() => {});
            if (url && url !== '#') window.location.href = url;
        };

        // ── Mark all read ──
        window.markAllRead = function() {
            fetch(API.readAll, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            }).then(() => { lastCount = 0; setBadge(0); loadNotifications(); }).catch(() => {});
        };

        // ── Close on outside click ──
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target) && panelOpen) {
                panelOpen = false;
                document.getElementById('notif-panel')?.classList.add('hidden');
            }
        });
    })();
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
    @stack('scripts')
</body>
</html>
