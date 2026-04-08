<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        // ── Cached admin settings (5 min) — prevents 6+ DB hits per admin page load ──
        $adminLayoutCache = \Illuminate\Support\Facades\Cache::remember('layout.admin.globals', now()->addMinutes(5), function () {
            return [
                'siteName' => \App\Models\SystemSetting::get('site_name', 'Motor Bazar'),
                'siteLogo' => \App\Models\SystemSetting::get('site_logo'),
                'siteFavicon' => \App\Models\SystemSetting::get('site_favicon'),
                'currency' => \App\Models\SystemSetting::get('site_currency', 'AED'),
                'currencyPos' => \App\Models\SystemSetting::get('currency_position', 'before'),
                'dateFormat' => \App\Models\SystemSetting::get('date_format', 'd/m/Y'),
                'mapsKey' => \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY', '')),
                'mapProvider' => \App\Models\SystemSetting::get('google_maps_provider', 'google'),
                'currSym' => \App\Helpers\CurrencyHelper::symbol(),
            ];
        });

        if (!isset($adminSiteName))
            $adminSiteName = $adminLayoutCache['siteName'];
        if (!isset($adminSiteLogo))
            $adminSiteLogo = $adminLayoutCache['siteLogo'];
        if (!isset($adminSiteFavicon))
            $adminSiteFavicon = $adminLayoutCache['siteFavicon'];
        if (!isset($appCurrencySymbol))
            $appCurrencySymbol = $adminLayoutCache['currSym'];
        if (!isset($appCurrencyCode))
            $appCurrencyCode = $adminLayoutCache['currency'];
        if (!isset($appCurrencyPos))
            $appCurrencyPos = $adminLayoutCache['currencyPos'];
        if (!isset($appDateFormat))
            $appDateFormat = $adminLayoutCache['dateFormat'];
        $googleMapsKey = $adminLayoutCache['mapsKey'];
    ?>
    <title><?php echo e($adminSiteName); ?> Admin | <?php echo $__env->yieldContent('title'); ?></title>
    <?php if($adminSiteFavicon): ?>
        <link rel="icon" type="image/x-icon" href="<?php echo e(asset('storage/' . $adminSiteFavicon)); ?>">
    <?php endif; ?>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css', 'resources/js/admin.js']); ?>

    <script>
        window.googleMapsKey = "<?php echo $googleMapsKey; ?>";
        window.mapProvider = "<?php echo \App\Models\SystemSetting::get('google_maps_provider', 'google'); ?>";
        window.appCurrency = {
            code: "<?php echo $appCurrencyCode; ?>",
            symbol: "<?php echo $appCurrencySymbol; ?>",
            position: "<?php echo $appCurrencyPos; ?>",
        };
        window.appDateFormat = "<?php echo $appDateFormat; ?>";
    </script>

    
    <script>
        (function () {
            // 1. Set sidebar collapsed class immediately
            if (localStorage.getItem('sidebarOpen') === 'false') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
            // 2. Hide body until Alpine is ready (prevents all x-show FOUC)
            document.documentElement.classList.add('alpine-loading');
        })();
    </script>

    <?php echo $__env->yieldPushContent('head'); ?>
</head>

<body class="antialiased font-light text-[#111827] bg-[#e7e7e7]">

    <div class="flex h-screen overflow-hidden"
        style="display: flex; flex-direction: row; height: 100vh; overflow: hidden;" x-data="{
        sidebarOpen: !document.documentElement.classList.contains('sidebar-collapsed'),
        openCRM: <?php echo e(request()->routeIs('admin.leads.*') || request()->routeIs('admin.inspections.*') ? 'true' : 'false'); ?>

    }" x-init="
        // Remove loading class — page becomes visible after Alpine processes all x-show
        $nextTick(() => {
            document.documentElement.classList.remove('alpine-loading');
            // Enable sidebar transition AFTER initial render
            setTimeout(() => document.getElementById('admin-sidebar')?.classList.add('sidebar-ready'), 0);
        });
        
        // Safety: Remove loading class even if Alpine hangs
        setTimeout(() => document.documentElement.classList.remove('alpine-loading'), 2000);
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
            <div
                class="h-[120px] flex items-center px-4 border-b border-[#f1f5f9] overflow-hidden whitespace-nowrap flex-shrink-0">
                <div class="flex items-center gap-1 min-w-max">
                    <?php if($adminSiteLogo): ?>
                        <img src="<?php echo e(asset('st/' . $adminSiteLogo)); ?>" class="w-20 h-20 object-contain">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-slate-800 rounded-lg flex items-center justify-center shadow-lg">
                            <span
                                class="text-white font-medium text-2xl tracking-tighter italic"><?php echo e(strtoupper(substr($adminSiteName, 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="-ml-1">
                        <h1 class="font-bold text-[1.1rem] tracking-tight leading-none italic uppercase"
                            style="color: #1d293d !important;">
                            <?php echo e($primaryAdminWord); ?><span
                                style="color: #ff6900 !important;"><?php echo e($secondaryAdminWord); ?></span>
                        </h1>
                        <span
                            class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-[0.1em] mt-2 block italic opacity-60">Elite
                            Admin Suite</span>
                    </div>
                </div>
            </div>

            <!-- Navigation: Absolute Restore -->
            <nav class="flex-1 overflow-y-auto p-4 sidebar-scroll space-y-6 mt-2 pb-10">

                
                <div class="space-y-1">
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.dashboard') ? 'text-slate-900 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.dashboard') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                            <rect width="7" height="9" x="3" y="3" rx="1" />
                            <rect width="7" height="5" x="14" y="3" rx="1" />
                            <rect width="7" height="9" x="14" y="12" rx="1" />
                            <rect width="7" height="5" x="3" y="16" rx="1" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak class="truncate">Dashboard</span>
                    </a>
                </div>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view leads')): ?>
                    <div class="space-y-1">
                        <button @click="openCRM = !openCRM"
                            class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-slate-500 hover:bg-slate-50 transition-all">
                            <div class="flex items-center gap-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="text-slate-400 flex-shrink-0">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                <span x-show="sidebarOpen" x-cloak>CRM and Operations</span>
                            </div>
                            <svg x-show="sidebarOpen" x-cloak :class="openCRM ? 'rotate-180' : ''"
                                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                class="transition-transform duration-200 text-slate-400 flex-shrink-0">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <ul x-show="openCRM" x-cloak x-collapse
                            class="pl-12 space-y-1 mt-1 border-l-2 border-slate-50 ml-6">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view leads')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.leads.index')); ?>"
                                        class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.leads.*') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Leads</a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view inspections')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.inspections.calendar')); ?>"
                                        class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.calendar') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Inspections
                                        Calendar</a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('admin.inspections.tasks')); ?>"
                                        class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.tasks') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Field
                                        Tasks</a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('admin.inspections.index')); ?>"
                                        class="block py-2 text-[0.75rem] font-medium <?php echo e((request()->routeIs('admin.inspections.*') && !request()->routeIs('admin.inspections.calendar') && !request()->routeIs('admin.inspections.tasks')) ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>">Appraisal
                                        Reports</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view hr')): ?>
                    <div class="space-y-2 pt-2">
                        <div x-show="sidebarOpen" x-cloak
                            class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">
                            HR Management</div>
                        <ul class="space-y-1">
                            
                            <li x-data="{ open: <?php echo e(request()->routeIs('admin.hr.*') ? 'true' : 'false'); ?> }">
                                
                                <button @click="open = !open"
                                    class="w-full sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold transition-all
                                        <?php echo e(request()->routeIs('admin.hr.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="flex-shrink-0 <?php echo e(request()->routeIs('admin.hr.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak class="flex-1 text-left truncate">HR
                                        Management</span>
                                    <svg x-show="sidebarOpen" x-cloak :class="open ? 'rotate-180' : ''"
                                        xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="transition-transform duration-200 text-slate-400 flex-shrink-0">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </button>

                                
                                <ul x-show="open && sidebarOpen" x-cloak
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="mt-1 ml-9 space-y-0.5 border-l-2 border-slate-100 pl-3">

                                    <?php
                                        $hrLinks = [
                                            ['route' => 'admin.hr.dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'perm' => 'view hr'],
                                            ['route' => 'admin.hr.departments.index', 'label' => 'Departments', 'icon' => 'building-2', 'perm' => null],
                                            ['route' => 'admin.hr.positions.index', 'label' => 'Positions', 'icon' => 'briefcase', 'perm' => null],
                                            ['route' => 'admin.hr.employees.index', 'label' => 'Employee Manager', 'icon' => 'users', 'perm' => null],
                                            ['route' => 'admin.hr.shifts.index', 'label' => 'Shifts', 'icon' => 'clock', 'perm' => null],
                                            ['route' => 'admin.hr.attendance.index', 'label' => 'Attendance', 'icon' => 'calendar-check', 'perm' => 'view attendance'],
                                            ['route' => 'admin.hr.leaves.index', 'label' => 'Leaves', 'icon' => 'calendar-x', 'perm' => 'view leaves'],
                                            ['route' => 'admin.hr.payrolls.index', 'label' => 'Payrolls', 'icon' => 'banknote', 'perm' => 'view payroll'],
                                            ['route' => 'admin.hr.salary-structures.index', 'label' => 'Salary Structures', 'icon' => 'banknote', 'perm' => null],
                                            ['route' => 'admin.hr.advances.index', 'label' => 'Advances', 'icon' => 'hand-coins', 'perm' => null],
                                            ['route' => 'admin.hr.penalties.index', 'label' => 'Penalties', 'icon' => 'shield-alert', 'perm' => null],
                                            ['route' => 'admin.hr.documents.index', 'label' => 'Documents', 'icon' => 'file-stack', 'perm' => null],
                                            ['route' => 'admin.hr.evaluations.index', 'label' => 'Evaluations', 'icon' => 'clipboard-check', 'perm' => null],
                                            ['route' => 'admin.hr.rewards.index', 'label' => 'Rewards', 'icon' => 'award', 'perm' => null],
                                            ['route' => 'admin.hr.recruitments.index', 'label' => 'Recruitment', 'icon' => 'user-plus', 'perm' => null],
                                            ['route' => 'admin.hr.test-components', 'label' => 'Test Page', 'icon' => 'layers', 'perm' => null],
                                        ];
                                    ?>

                                    <?php $__currentLoopData = $hrLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(empty($link['perm']) || auth()->user()->can($link['perm'])): ?>
                                            <li>
                                                <a href="<?php echo e(route($link['route'])); ?>"
                                                    class="flex items-center gap-2.5 px-2 py-2 rounded-md text-[0.72rem] font-bold transition-all
                                                            <?php echo e(request()->routeIs($link['route']) ? 'text-[#ff6900] bg-orange-50' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-50'); ?>">
                                                    <i data-lucide="<?php echo e($link['icon']); ?>" class="w-4 h-4 flex-shrink-0"></i>
                                                    <?php echo e($link['label']); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </ul>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view cars')): ?>
                    <div class="space-y-2 pt-2">
                        <div x-show="sidebarOpen" x-cloak
                            class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">
                            Fleet Management</div>
                        <ul class="space-y-1">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view cars')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.cars.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.cars.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.cars.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path
                                                d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11 2 11.1 2 11.2V16c0 .6.4 1 1 1h2" />
                                            <circle cx="7" cy="17" r="2" />
                                            <path d="M9 17h6" />
                                            <circle cx="17" cy="17" r="2" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak class="truncate">Vehicles</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view auctions')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.auctions.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path d="m6 15-4-4 6.7-6.7a2.1 2.1 0 1 1 3 3L5 14" />
                                            <path d="m15 13 4 4" />
                                            <path d="m21 11-8 8" />
                                            <path d="m21 15-8 8" />
                                            <path d="m10 11 8-8" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak class="truncate">Auctions</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view stock')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.stock.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.stock.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.stock.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path
                                                d="M22 8.35V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.35A2 2 0 0 1 3.26 6.5l8-3.2a2 2 0 0 1 1.48 0l8 3.2A2 2 0 0 1 22 8.35Z" />
                                            <path d="M6 18h12" />
                                            <path d="M6 14h12" />
                                            <rect x="8" y="10" width="8" height="12" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak class="truncate">Stock</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view dealers')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.dealers.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.dealers.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.dealers.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak class="truncate">Dealers</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view finance')): ?>
                                <li x-data="{ open: <?php echo e(request()->routeIs('admin.finance.*') ? 'true' : 'false'); ?> }">
                                    
                                    <button @click="open = !open"
                                        class="w-full sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold transition-all
                                            <?php echo e(request()->routeIs('admin.finance.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.finance.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <rect width="20" height="14" x="2" y="5" rx="2" />
                                            <line x1="2" x2="22" y1="10" y2="10" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak class="flex-1 text-left truncate">Accounting</span>
                                        <svg x-show="sidebarOpen" x-cloak :class="open ? 'rotate-180' : ''"
                                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="transition-transform duration-200 text-slate-400 flex-shrink-0">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>

                                    
                                    <ul x-show="open && sidebarOpen" x-cloak
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 -translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="mt-1 ml-9 space-y-0.5 border-l-2 border-slate-100 pl-3">

                                        <?php
                                            $financeLinks = [
                                                ['route' => 'admin.finance.dashboard', 'label' => 'Overview', 'svg' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                                                ['route' => 'admin.finance.invoices', 'label' => 'Invoices', 'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
                                                ['route' => 'admin.finance.receipts', 'label' => 'Receipts', 'svg' => '<line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/>'],
                                                ['route' => 'admin.finance.vouchers', 'label' => 'Payment Vouchers', 'svg' => '<line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>'],
                                                ['route' => 'admin.finance.accounts', 'label' => 'Cash and Bank Accounts', 'svg' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>'],
                                            ];
                                        ?>

                                        <?php $__currentLoopData = $financeLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <a href="<?php echo e(route($fl['route'])); ?>"
                                                    class="flex items-center gap-2.5 px-2 py-2 rounded-md text-[0.72rem] font-bold transition-all
                                                        <?php echo e(request()->routeIs($fl['route']) ? 'text-[#ff6900] bg-orange-50' : 'text-slate-400 hover:text-slate-700 hover:bg-slate-50'); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="flex-shrink-0"><?php echo $fl['svg']; ?></svg>
                                                    <?php echo e($fl['label']); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </ul>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view cms')): ?>
                    <div class="space-y-2 pt-2">
                        <div x-show="sidebarOpen" x-cloak
                            class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">
                            Content</div>
                        <ul class="space-y-1">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view cms')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.cms.home')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.cms.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <i data-lucide="home" class="w-5 h-5"></i>
                                        <span x-show="sidebarOpen" x-cloak>Home CMS</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view posts')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.posts.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.posts.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <i data-lucide="file-text" class="w-5 h-5"></i>
                                        <span x-show="sidebarOpen" x-cloak>Blog Posts</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view pages')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.pages.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.pages.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <i data-lucide="layers" class="w-5 h-5"></i>
                                        <span x-show="sidebarOpen" x-cloak>Static Pages</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view menus')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.menus.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.menus.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <i data-lucide="menu" class="w-5 h-5"></i>
                                        <span x-show="sidebarOpen" x-cloak>Site Navigation</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view settings')): ?>
                    <div class="space-y-2 pt-2">
                        <div x-show="sidebarOpen" x-cloak
                            class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">
                            System</div>
                        <ul class="space-y-1">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view seo')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.seo.dashboard')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.seo.dashboard') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.seo.dashboard') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <circle cx="11" cy="11" r="8" />
                                            <path d="m21 21-4.3-4.3" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak>SEO Intelligence</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('admin.seo.guide')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.seo.guide') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                                        <span x-show="sidebarOpen" x-cloak>How it Works (SEO)</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view settings')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.settings.hub')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.settings.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.settings.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path
                                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak>Settings</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view roles')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.roles.index')); ?>"
                                        class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.roles.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="flex-shrink-0 <?php echo e(request()->routeIs('admin.roles.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                        </svg>
                                        <span x-show="sidebarOpen" x-cloak>Roles and Users</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="<?php echo e(route('admin.routes.inventory')); ?>"
                                    class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.routes.inventory') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="flex-shrink-0 <?php echo e(request()->routeIs('admin.routes.inventory') ? 'text-[#ff6900]' : 'text-slate-400'); ?>">
                                        <path
                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                        <path d="M4 9h16" />
                                        <path d="M9 16l3-3 3 3" />
                                    </svg>
                                    <span x-show="sidebarOpen" x-cloak>Routes Inventory</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <div class="pt-6 border-t border-slate-50">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="sidebar-item w-full flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-red-500 hover:bg-red-50 transition-all italic">
                            <span x-show="sidebarOpen" x-cloak>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Toggle Controller -->
            <div class="p-4 border-t border-[#f1f5f9] flex justify-center flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm active:scale-90">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path x-show="!sidebarOpen" x-cloak d="m9 18 6-6-6-6" />
                        <path x-show="sidebarOpen" x-cloak d="m15 18-6-6 6-6" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#e7e7e7] overflow-hidden">
            <header
                class="h-[74px] bg-white border-b border-slate-100 flex items-center justify-between px-8 relative z-30 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <div
                        class="text-slate-500 font-bold text-[0.7rem] uppercase tracking-widest italic flex items-center gap-2">
                        <?php echo e(__('messages.location')); ?> <i data-lucide="chevron-right"
                            class="w-3.5 h-3.5 text-slate-300"></i>
                        <span class="text-slate-900">
                            <?php if(request()->segment(2) == 'leads'): ?> Leads
                            <?php elseif(request()->segment(2) == 'inspections'): ?>
                                <?php if(request()->segment(3) == 'calendar'): ?> Inspections Calendar
                                <?php elseif(request()->segment(3) == 'tasks'): ?> Field Tasks
                                <?php else: ?> Appraisal Reports
                                <?php endif; ?>
                            <?php elseif(request()->segment(2) == 'hr'): ?>
                                <?php
                                    $hrTitles = [
                                        'dashboard' => 'HR Dashboard',
                                        'employees' => 'Employee Manager',
                                        'departments' => 'Departments',
                                        'positions' => 'Positions',
                                        'shifts' => 'Shifts',
                                        'attendance' => 'Attendance',
                                        'leaves' => 'Leaves',
                                        'payrolls' => 'Payrolls',
                                        'salary-structures' => 'Salary Structures',
                                        'advances' => 'Advances',
                                        'penalties' => 'Penalties',
                                        'documents' => 'Documents',
                                        'evaluations' => 'Evaluations',
                                        'rewards' => 'Rewards',
                                        'recruitments' => 'Recruitment',
                                        'test-components' => 'Test Page',
                                        'calendar' => 'HR Calendar',
                                        'reports' => 'HR Reports',
                                    ];
                                    $thirdSegment = request()->segment(3);
                                ?>
                                <?php echo e($hrTitles[$thirdSegment] ?? 'HR Management'); ?>

                            <?php else: ?> <?php echo $__env->yieldContent('page_title', 'Dashboard'); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    
                    <div class="relative" id="notif-wrapper">
                        <button id="notif-bell" onclick="toggleNotifPanel()"
                            class="relative w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span id="notif-badge"
                                class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] rounded-full bg-[#ff6900] text-white text-[0.5rem] font-black flex items-center justify-center px-1 hidden animate-bounce">0</span>
                        </button>

                        
                        <div id="notif-panel"
                            class="hidden absolute top-[calc(100%+12px)] right-0 w-96 bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 overflow-hidden z-50">
                            
                            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-50">
                                <div>
                                    <div class="text-[0.7rem] font-black uppercase tracking-widest text-[#031629]">
                                        Notification Center</div>
                                    <div class="text-[0.55rem] text-slate-400 font-bold mt-0.5" id="notif-count-label">
                                        Loading...</div>
                                </div>
                                <button onclick="markAllRead()"
                                    class="px-3 py-1.5 bg-slate-50 rounded-lg text-[0.6rem] font-black uppercase tracking-widest text-slate-500 hover:bg-[#1d293d] hover:text-white transition-all">
                                    Mark all read
                                </button>
                            </div>

                            
                            <div id="notif-list" class="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
                                <div
                                    class="py-12 text-center text-[0.65rem] font-black uppercase tracking-widest text-slate-300">
                                    Loading...
                                </div>
                            </div>

                            
                            <div class="px-6 py-3 border-t border-slate-50 text-center">
                                <span
                                    class="text-[0.55rem] font-black uppercase tracking-widest text-slate-300">Real-time
                                    notifications</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative" id="user-menu-wrapper">
                        <!-- User Menu Button -->
                        <button onclick="toggleUserPanel()"
                            class="flex items-center gap-3 pl-6 border-l border-slate-100 hover:opacity-80 transition-all">
                            <div class="text-right">
                                <p class="text-[0.75rem] font-bold text-slate-800 leading-none italic">
                                    <?php echo e(Auth::user()->name ?? 'Operator'); ?></p>
                                <p class="text-[0.55rem] text-slate-400 uppercase tracking-widest mt-1">Administrator
                                    Access</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-white text-xs shadow-md">
                                <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                            </div>
                        </button>

                        
                        <div id="user-panel"
                            class="hidden absolute top-[calc(100%+12px)] right-0 w-60 bg-white rounded-[1.4rem] shadow-2xl border border-slate-100 overflow-hidden z-50 p-2">

                            
                            <div class="p-4 border-b border-slate-100/50 mb-1.5 bg-slate-50/50 rounded-2xl">
                                <div class="text-[0.85rem] font-black text-slate-950 truncate leading-tight">
                                    <?php echo e(Auth::user()->name ?? 'Operator'); ?></div>
                                <div class="text-[0.55rem] text-slate-400 font-bold uppercase mt-1 tracking-[0.1em]">
                                    Administrator Access</div>
                            </div>

                            
                            <div class="space-y-0.5">
                                <a href="<?php echo e(route('dealer.profile', Auth::id())); ?>"
                                    class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-orange-50 hover:text-[#ff6900] transition-all group/item">
                                    <div
                                        class="w-7 h-7 rounded-lg bg-orange-100/50 flex items-center justify-center text-orange-600 group-hover/item:scale-110 transition-transform">
                                        <i data-lucide="user-circle" class="w-4"></i>
                                    </div>
                                    My Profile
                                </a>

                                <?php if(Auth::user()->is_admin || Auth::user()->hasRole(['admin', 'super-admin'])): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                                        class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-slate-50 transition-all group/item">
                                        <div
                                            class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover/item:scale-110 transition-transform">
                                            <i data-lucide="cpu" class="w-3"></i>
                                        </div>
                                        Core Systems
                                    </a>
                                <?php endif; ?>

                                <div class="pt-2 mt-2 border-t border-slate-100/50">
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-[0.75rem] font-bold text-red-500 hover:bg-red-50 transition-all group/item">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-red-100/50 flex items-center justify-center text-red-600 group-hover/item:scale-110 transition-transform">
                                                <i data-lucide="log-out" class="w-4"></i>
                                            </div>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <!-- Page Context & Initialization -->
    <?php if(auth()->guard()->check()): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof window.initNotificationCenter === 'function') {
                    window.initNotificationCenter({
                        listUrl: '<?php echo route("admin.notifications.index"); ?>',
                        countUrl: '<?php echo route("admin.notifications.count"); ?>',
                        readAllUrl: '<?php echo route("admin.notifications.read-all"); ?>',
                        readUrlTemplate: '<?php echo url("admin/notifications"); ?>/:id/read',
                        csrf: document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                        initialCount: <?php echo auth()->user()->unreadNotifications()->count(); ?>,
                        userId: <?php echo auth()->id(); ?>,
                        userRoles: <?php echo json_encode(auth()->user()->roles->pluck('name')->toArray(), 15, 512) ?>
                    });
                }

                // Session Flash Feedback
                <?php if(session('success')): ?> window.showToast("<?php echo addslashes(session('success')); ?>", 'success'); <?php endif; ?>
                <?php if(session('error')): ?> window.showToast("<?php echo addslashes(session('error')); ?>", 'error'); <?php endif; ?>
            });
        </script>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <style>
        :root {
            --font-outfit: "Outfit", sans-serif;
        }
        body {
            font-family: var(--font-outfit);
            font-weight: 300; /* Lighter default for airy feel */
        }
        
        /* Premium weight mapping to avoid "thickness" */
        .font-black, .font-extrabold {
            font-weight: 700 !important; /* Bold instead of Black */
        }
        .font-bold {
            font-weight: 600 !important; /* Semibold instead of Bold */
        }
        .font-semibold {
            font-weight: 500 !important; /* Medium instead of Semibold */
        }
        .font-medium {
            font-weight: 400 !important; /* Regular instead of Medium */
        }
    </style>
</body>

</html><?php /**PATH F:\auction_app\resources\views/admin/layout.blade.php ENDPATH**/ ?>