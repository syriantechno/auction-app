<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        // These are now shared via AppServiceProvider — safe fallback if not set
        if (!isset($adminSiteName)) $adminSiteName = \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
        if (!isset($adminSiteLogo)) $adminSiteLogo = \App\Models\SystemSetting::get('site_logo');
        if (!isset($adminSiteFavicon)) $adminSiteFavicon = \App\Models\SystemSetting::get('site_favicon');
        if (!isset($appCurrencySymbol)) $appCurrencySymbol = \App\Helpers\CurrencyHelper::symbol();
        if (!isset($appCurrencyCode)) $appCurrencyCode = \App\Models\SystemSetting::get('site_currency', 'AED');
        if (!isset($appCurrencyPos)) $appCurrencyPos = \App\Models\SystemSetting::get('currency_position', 'before');
        if (!isset($appDateFormat)) $appDateFormat = \App\Models\SystemSetting::get('date_format', 'd/m/Y');
        $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
    ?>
    <title><?php echo e($adminSiteName); ?> Admin | <?php echo $__env->yieldContent('title'); ?></title>
    <?php if($adminSiteFavicon): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $adminSiteFavicon)); ?>">
    <?php endif; ?>


    <!-- Design System: Jakarta Sans & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Premium Pickers: Flatpickr Matrix -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css', 'resources/js/admin.js']); ?>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.googleMapsKey = "<?php echo e($googleMapsKey); ?>";
        window.mapProvider = "<?php echo e(\App\Models\SystemSetting::get('google_maps_provider', 'google')); ?>";
        // Currency globals — used by any JS that formats money
        window.appCurrency = {
            code:     "<?php echo e($appCurrencyCode); ?>",
            symbol:   "<?php echo e($appCurrencySymbol); ?>",
            position: "<?php echo e($appCurrencyPos); ?>",
        };
        window.appDateFormat = "<?php echo e($appDateFormat); ?>";

    </script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <?php if($googleMapsKey): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsKey); ?>&libraries=places"></script>
    <?php endif; ?>

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

    <?php echo $__env->yieldPushContent('head'); ?>

    
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
        openCRM: <?php echo e(request()->routeIs('admin.leads.*') || request()->routeIs('admin.inspections.*') ? 'true' : 'false'); ?>

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
            
            <?php
                $primaryAdminWord = explode(' ', $adminSiteName)[0] ?? 'Motor';
                $secondaryAdminWord = explode(' ', $adminSiteName)[1] ?? 'Bazar';
            ?>
            <div class="h-[120px] flex items-center px-4 border-b border-[#f1f5f9] overflow-hidden whitespace-nowrap flex-shrink-0">
                <div class="flex items-center gap-1 min-w-max">
                    <?php if($adminSiteLogo): ?>
                        <img src="<?php echo e(asset('storage/' . $adminSiteLogo)); ?>" class="w-20 h-20 object-contain rounded-lg filter invert-[1] hue-rotate-[180deg] brightness-[1.1] contrast-[1.5] saturate-[1.8]">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-slate-800 rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-white font-medium text-2xl tracking-tighter italic"><?php echo e(strtoupper(substr($adminSiteName, 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="-ml-1">
                        <h1 class="font-bold text-[1.1rem] tracking-tight leading-none italic uppercase" style="color: #1d293d !important;">
                            <?php echo e($primaryAdminWord); ?><span style="color: #ff6900 !important;"><?php echo e($secondaryAdminWord); ?></span>
                        </h1>
                        <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-[0.1em] mt-2 block italic opacity-60">Elite Admin Suite</span>
                    </div>
                </div>
            </div>

            <!-- Navigation: Absolute Restore -->
            <nav class="flex-1 overflow-y-auto p-4 sidebar-scroll space-y-6 mt-2 pb-10">

                
                <div class="space-y-1">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.dashboard') ? 'text-slate-900 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.dashboard') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span x-show="sidebarOpen" x-cloak class="truncate">Dashboard</span>
                    </a>
                </div>

                
                <div class="space-y-1">
                    <button @click="openCRM = !openCRM" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-slate-500 hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 flex-shrink-0"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span x-show="sidebarOpen" x-cloak>CRM & Operations</span>
                        </div>
                        
                        <svg x-show="sidebarOpen" x-cloak :class="openCRM ? 'rotate-180' : ''"
                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="transition-transform duration-200 text-slate-400 flex-shrink-0">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                    
                    <ul x-show="openCRM" x-cloak x-collapse class="pl-12 space-y-1 mt-1 border-l-2 border-slate-50 ml-6">
                        <li>
                            <a href="<?php echo e(route('admin.leads.index')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.leads.*') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Leads</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.calendar')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.calendar') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Inspections Calendar</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.tasks')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.tasks') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Field Tasks</a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.index')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e((request()->routeIs('admin.inspections.*') && !request()->routeIs('admin.inspections.calendar') && !request()->routeIs('admin.inspections.tasks')) ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Appraisal Reports</a>
                        </li>
                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">Fleet Management</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.cars.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.cars.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.cars.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11 2 11.1 2 11.2V16c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Vehicles</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.auctions.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="m6 15-4-4 6.7-6.7a2.1 2.1 0 1 1 3 3L5 14"/><path d="m15 13 4 4"/><path d="m21 11-8 8"/><path d="m21 15-8 8"/><path d="m10 11 8-8"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Auctions</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.stock.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.stock.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.stock.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z"/><path d="M6 18h12"/><path d="M6 14h12"/><rect x="8" y="10" width="8" height="12"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Stock</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.dealers.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.dealers.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.dealers.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span x-show="sidebarOpen" x-cloak class="truncate">Dealers</span>
                            </a>
                        </li>

                        
                        <li x-data="{ open: <?php echo e(request()->routeIs('admin.finance.*') ? 'true' : 'false'); ?> }">
                            
                            <button @click="open = !open"
                                class="w-full sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold transition-all
                                    <?php echo e(request()->routeIs('admin.finance.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="flex-shrink-0 <?php echo e(request()->routeIs('admin.finance.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                <span x-show="sidebarOpen" x-cloak class="flex-1 text-left truncate">Accounting</span>
                                <svg x-show="sidebarOpen" x-cloak :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 text-slate-400 flex-shrink-0"><path d="m6 9 6 6 6-6"/></svg>
                            </button>

                            
                            <ul x-show="open && sidebarOpen" x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-1 ml-9 space-y-0.5 border-l-2 border-slate-100 pl-3">

                                <?php
                                    $financeLinks = [
                                        ['route' => 'admin.finance.dashboard', 'label' => 'Overview',             'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                                        ['route' => 'admin.finance.invoices',  'label' => 'Invoices',             'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
                                        ['route' => 'admin.finance.receipts',  'label' => 'Receipts',             'svg' => '<line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/>'],
                                        ['route' => 'admin.finance.vouchers',  'label' => 'Payment Vouchers',     'svg' => '<line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>'],
                                        ['route' => 'admin.finance.accounts',  'label' => 'Cash & Bank Accounts', 'svg' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
                                    ];
                                ?>

                                <?php $__currentLoopData = $financeLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route($fl['route'])); ?>"
                                        class="flex items-center gap-2.5 px-2 py-2 rounded-md text-[0.72rem] font-bold transition-all
                                            <?php echo e(request()->routeIs($fl['route']) ? 'text-[#ff6900] bg-orange-50' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><?php echo $fl['svg']; ?></svg>
                                        <?php echo e($fl['label']); ?>

                                    </a>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </ul>
                        </li>

                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">Content</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.cms.home')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.cms.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="home" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Home CMS</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.posts.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.posts.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Blog Posts</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.pages.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.pages.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="layers" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Static Pages</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.menus.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.menus.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="menu" class="w-5 h-5"></i>
                                <span x-show="sidebarOpen" x-cloak>Site Navigation</span>
                            </a>
                        </li>
                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" x-cloak class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">System</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.seo.dashboard')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.seo.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.seo.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <span x-show="sidebarOpen" x-cloak>SEO Intelligence</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.settings.hub')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.settings.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.settings.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span x-show="sidebarOpen" x-cloak>Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>

                
                <div class="pt-6 border-t border-slate-50">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="sidebar-item w-full flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-red-500 hover:bg-red-50 transition-all italic">
                                <span x-show="sidebarOpen" x-cloak>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Toggle Controller -->
            <div class="p-4 border-t border-[#f1f5f9] flex justify-center flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm active:scale-90">
                    
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
                        <?php echo e(__('messages.location')); ?> <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i> 
                        <span class="text-slate-900">
                            <?php if(request()->segment(2) == 'leads'): ?> Leads
                            <?php elseif(request()->segment(2) == 'inspections'): ?>
                                <?php if(request()->segment(3) == 'calendar'): ?> Inspections Calendar
                                <?php elseif(request()->segment(3) == 'tasks'): ?> Field Tasks
                                <?php else: ?> Appraisal Reports
                                <?php endif; ?>
                            <?php else: ?> <?php echo $__env->yieldContent('page_title', 'Dashboard'); ?>
                            <?php endif; ?>
                        </span>
                     </div>
                </div>

                <div class="flex items-center gap-6">

                    
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-bell" onclick="toggleNotifPanel()"
                                class="relative w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                            <i data-lucide="bell" class="w-4 h-4"></i>
                            <span id="notif-badge"
                                  class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] rounded-full bg-[#ff6900] text-white text-[0.5rem] font-black flex items-center justify-center px-1 hidden animate-bounce">0</span>
                        </button>

                        
                        <div id="notif-panel"
                             class="hidden absolute top-[calc(100%+12px)] right-0 w-96 bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden z-50">
                            
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

                            
                            <div id="notif-list" class="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
                                <div class="py-12 text-center text-[0.65rem] font-black uppercase tracking-widest text-slate-300">
                                    Loading...
                                </div>
                            </div>

                            
                            <div class="px-6 py-3 border-t border-slate-50 text-center">
                                <span class="text-[0.55rem] font-black uppercase tracking-widest text-slate-300">Auto-refreshes every 15 seconds</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
                        <div class="text-right">
                            <p class="text-[0.75rem] font-bold text-slate-800 leading-none italic"><?php echo e(Auth::user()->name ?? 'Operator'); ?></p>
                            <p class="text-[0.55rem] text-slate-400 uppercase tracking-widest mt-1">Administrator Access</p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-white text-xs shadow-md">
                           <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <?php echo $__env->yieldContent('content'); ?>
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
    </script>

    <!-- ── Premium Elite Toast Engine: The Rocket Pill ── -->
    <script>
        let eliteToastContainer = null;
        
        function ensureToastContainer() {
            if (eliteToastContainer) return eliteToastContainer;
            eliteToastContainer = document.createElement('div');
            eliteToastContainer.id = 'eliteToastContainer';
            eliteToastContainer.style.cssText = 'position:fixed;top:2.5rem;right:2.5rem;z-index:9999999;display:flex;flex-direction:column;gap:1rem;pointer-events:none;';
            document.body.appendChild(eliteToastContainer);
            return eliteToastContainer;
        }

        const toastConfigs = {
            success: {
                label: 'Sync Successful',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                bg: 'rgba(2, 6, 23, 0.96)',
                border: 'rgba(255, 255, 255, 0.15)',
                subColor: 'rgba(255,255,255,0.4)'
            },
            error: {
                label: 'Sync Failure',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-red-400"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                bg: '#dc2626',
                border: 'rgba(255, 255, 255, 0.2)',
                subColor: 'rgba(255,255,255,0.6)'
            }
        };

        window.showToast = function(msg, type = 'success', duration = 5000) {
            const config = toastConfigs[type] || toastConfigs.success;
            const toast = document.createElement('div');
            toast.style.cssText = `
                pointer-events: auto;
                display: flex;
                align-items: center;
                gap: 1.25rem;
                padding: 1.5rem 2.5rem;
                background: ${config.bg};
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border: 1px solid ${config.border};
                border-radius: 5rem;
                box-shadow: 0 35px 60px -15px rgba(0,0,0,0.3);
                min-width: 380px;
                max-width: 500px;
                opacity: 0;
                transform: translateX(3rem) scale(0.9) blur(10px);
                transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                position: relative;
                overflow: hidden;
                font-family: 'Plus Jakarta Sans', sans-serif;
            `;

            toast.innerHTML = `
                <div style="width: 3.5rem; height: 3.5rem; border-radius: 1.2rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: inset 0 0 12px rgba(0,0,0,0.1);">
                    ${config.icon}
                </div>
                <div style="flex: 1;">
                    <p style="margin: 0; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em; color: ${config.subColor}; margin-bottom: 0.25rem;">${config.label}</p>
                    <p style="margin: 0; font-size: 1.05rem; font-weight: 500; color: white; letter-spacing: -0.02em; line-height: 1.2;">${msg}</p>
                </div>
                <div style="position: absolute; top: 0.75rem; right: 2rem; opacity: 0.1; color: white; pointer-events: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-5c1.62-2.2 5-2.5 5-2.5"/><path d="M12 15v5s3.03-.55 5-2c2.2-1.62 2.5-5 2.5-5"/></svg>
                </div>
            `;

            const target = ensureToastContainer();
            target.appendChild(toast);
            
            // Animate In
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0) scale(1) blur(0)';
            });

            // Auto Close
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(3rem) scale(0.9) blur(10px)';
                setTimeout(() => toast.remove(), 500);
            }, duration);
        };

        // Global Notify API
        window.notify = {
            success: (m) => showToast(m, 'success'),
            error: (m) => showToast(m, 'error'),
            warning: (m) => showToast(m, 'error'),
            info: (m) => showToast(m, 'success')
        };

        // Alpine Event Link
        window.addEventListener('show-toast', e => {
            showToast(e.detail.message || e.detail.msg, e.detail.type || 'success');
        });

        // Session Flash Logic
        document.addEventListener('DOMContentLoaded', () => {
            <?php if(session('success')): ?> showToast("<?php echo e(addslashes(session('success'))); ?>", 'success'); <?php endif; ?>
            <?php if(session('error')): ?> showToast("<?php echo e(addslashes(session('error'))); ?>", 'error'); <?php endif; ?>
        });
    </script>

    
    <script>
    (function() {
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const API  = {
            list:    '<?php echo e(route("admin.notifications.index")); ?>',
            count:   '<?php echo e(route("admin.notifications.count")); ?>',
            readAll: '<?php echo e(route("admin.notifications.read-all")); ?>',
            read: (id) => '<?php echo e(url("admin/notifications")); ?>/' + id + '/read',
        };
        const FETCH_OPTS = { credentials: 'same-origin', headers: { 'Accept': 'application/json' } };

        let panelOpen = false;
        let audioCtx  = null;

        // ── Server-side initial count (no fetch needed on first load) ──
        <?php if(auth()->guard()->check()): ?>
        let lastCount = <?php echo e(auth()->user()->unreadNotifications()->count()); ?>;
        <?php else: ?>
        let lastCount = 0;
        <?php endif; ?>

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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\auction_app\resources\views/admin/layout.blade.php ENDPATH**/ ?>