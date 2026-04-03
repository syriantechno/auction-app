<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $adminSiteName = \App\Models\SystemSetting::get('site_name', 'Laravel');
        $adminSiteLogo = \App\Models\SystemSetting::get('site_logo');
        $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
    ?>
    <title><?php echo e($adminSiteName); ?> Admin | <?php echo $__env->yieldContent('title'); ?></title>

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
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <?php if($googleMapsKey): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsKey); ?>&libraries=places"></script>
    <?php endif; ?>

    <!-- Alerts & Notifications: SweetAlert2 & Toastify -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
</head>

<body class="antialiased text-[#111827] bg-[#e7e7e7]">

    <div class="flex h-screen overflow-hidden" x-data="{ 
        sidebarOpen: true, 
        openCRM: <?php echo e(request()->routeIs('admin.leads.*') || request()->routeIs('admin.inspections.*') ? 'true' : 'false'); ?> 
    }">

        <!-- Sidebar: Full Restoration -->
        <aside :class="sidebarOpen ? 'w-[260px]' : 'w-[80px]'"
            class="bg-white border-r border-[#f1f5f9] flex flex-col transition-all duration-300 ease-in-out relative z-40 overflow-hidden shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
            
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
                    <div x-show="sidebarOpen" x-transition.opacity.duration.300 class="-ml-1">
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
                        <span x-show="sidebarOpen" class="truncate"><?php echo e(__('admin.analytics')); ?></span>
                    </a>
                </div>

                
                <div class="space-y-1">
                    <button @click="openCRM = !openCRM" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-slate-500 hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span x-show="sidebarOpen"><?php echo e(__('admin.leads_management')); ?></span>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openCRM }"></i>
                    </button>
                    
                    <ul x-show="openCRM" x-collapse class="pl-12 space-y-1 mt-1 border-l-2 border-slate-50 ml-6">
                        <li>
                            <a href="<?php echo e(route('admin.leads.index')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.leads.*') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>"><?php echo e(__('admin.leads_management')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.calendar')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.calendar') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>"><?php echo e(__('admin.inspections_calendar')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.tasks')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e(request()->routeIs('admin.inspections.tasks') ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>"><?php echo e(__('admin.field_tasks')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.inspections.index')); ?>" class="block py-2 text-[0.75rem] font-medium <?php echo e((request()->routeIs('admin.inspections.*') && !request()->routeIs('admin.inspections.calendar') && !request()->routeIs('admin.inspections.tasks')) ? 'text-[#ff6900]' : 'text-slate-500 hover:text-slate-800'); ?>"><?php echo e(__('admin.valuation_reports')); ?></a>
                        </li>
                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic"><?php echo e(__('admin.fleet_management')); ?></div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.cars.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.cars.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.cars.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2 11 2 11.1 2 11.2V16c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                                <span x-show="sidebarOpen" class="truncate"><?php echo e(__('admin.vehicles_matrix')); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.auctions.index')); ?>"
                                class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-slate-900 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 <?php echo e(request()->routeIs('admin.auctions.*') ? 'text-[#ff6900]' : 'text-slate-400'); ?>"><path d="m6 15-4-4 6.7-6.7a2.1 2.1 0 1 1 3 3L5 14"/><path d="m15 13 4 4"/><path d="m21 11-8 8"/><path d="m21 15-8 8"/><path d="m10 11 8-8"/></svg>
                                <span x-show="sidebarOpen" class="truncate"><?php echo e(__('admin.auction_cycles')); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic"><?php echo e(__('admin.editorial')); ?></div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.cms.home')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.cms.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="home" class="w-5 h-5"></i> <span x-show="sidebarOpen">Home CMS</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.posts.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.posts.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="file-text" class="w-5 h-5"></i> <span x-show="sidebarOpen">Blog Posts</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.pages.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.pages.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="layers" class="w-5 h-5"></i> <span x-show="sidebarOpen">Static Pages</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.menus.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.menus.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="menu" class="w-5 h-5"></i> <span x-show="sidebarOpen">Site Navigation</span>
                            </a>
                        </li>
                    </ul>
                </div>

                
                <div class="space-y-2 pt-2">
                    <div x-show="sidebarOpen" class="text-[0.6rem] text-slate-400 font-bold mb-3 uppercase tracking-[0.2em] pl-3 opacity-70 italic">System & Finance</div>
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo e(route('admin.seo.dashboard')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.seo.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="bar-chart-3" class="w-5 h-5"></i> <span x-show="sidebarOpen">SEO Intelligence</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.invoices.*') ? 'text-slate-800 bg-slate-50 border border-slate-100' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="credit-card" class="w-5 h-5"></i> <span x-show="sidebarOpen">Financial Hub</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.settings.logo')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.settings.logo') ? 'text-slate-800 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="settings" class="w-5 h-5"></i> <span x-show="sidebarOpen">System Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('admin.settings.google-maps')); ?>" class="sidebar-item flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-medium <?php echo e(request()->routeIs('admin.settings.google-maps') ? 'text-slate-800 bg-slate-50 border border-slate-100 shadow-sm' : 'text-slate-500 hover:bg-slate-50'); ?>">
                                <i data-lucide="map-pin" class="w-5 h-5"></i> <span x-show="sidebarOpen">Maps Config</span>
                            </a>
                        </li>
                    </ul>
                </div>

                
                <div class="pt-6 border-t border-slate-50">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="sidebar-item w-full flex items-center gap-4 px-3.5 py-2.5 rounded-lg text-[0.8rem] font-bold text-red-500 hover:bg-red-50 transition-all italic">
                            <i data-lucide="log-out" class="w-5 h-5"></i> <span x-show="sidebarOpen">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Toggle Controller -->
            <div class="p-4 border-t border-[#f1f5f9] flex justify-center flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm active:scale-90">
                    <i :data-lucide="sidebarOpen ? 'chevron-left' : 'chevron-right'" class="w-4 h-4"></i>
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
                            <?php if(request()->segment(2) == 'leads'): ?>
                                <?php echo e(__('admin.leads_management')); ?>

                            <?php elseif(request()->segment(2) == 'inspections'): ?>
                                <?php if(request()->segment(3) == 'calendar'): ?>
                                    <?php echo e(__('admin.inspections_calendar')); ?>

                                <?php elseif(request()->segment(3) == 'tasks'): ?>
                                    <?php echo e(__('admin.field_tasks')); ?>

                                <?php else: ?>
                                    <?php echo e(__('admin.valuation_reports')); ?>

                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo $__env->yieldContent('page_title', __('admin.admin_dashboard')); ?>
                            <?php endif; ?>
                        </span>
                     </div>
                </div>

                <div class="flex items-center gap-6">
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

        // Global Notifications
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Toastify({ text: "<?php echo e(session('success')); ?>", duration: 4000, gravity: "top", position: "right", style: { background: "#1e293b", color: "#fff", borderRadius: "1rem", fontSize: "0.75rem", fontWeight: "400" } }).showToast();
            <?php endif; ?>
            <?php if(session('error')): ?>
                Toastify({ text: "<?php echo e(session('error')); ?>", duration: 5000, gravity: "top", position: "right", style: { background: "#ef4444", borderRadius: "1rem", fontSize: "0.75rem", fontWeight: "400" } }).showToast();
            <?php endif; ?>
        });

        window.notify = {
            success: (msg) => Toastify({ text: msg, style: { background: "#1e293b", color: "#fff", borderRadius: "1rem" } }).showToast(),
            error: (msg) => Toastify({ text: msg, style: { background: "#ef4444", borderRadius: "1rem" } }).showToast()
        };
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</body>
</html>
<?php /**PATH D:\auction_app\resources\views/admin/layout.blade.php ENDPATH**/ ?>