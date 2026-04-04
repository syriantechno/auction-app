<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $siteName = \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
        $siteLogo = \App\Models\SystemSetting::get('site_logo');
        $googleMapsKey = \App\Models\SystemSetting::get('google_maps_api_key', env('GOOGLE_MAPS_API_KEY'));
        $page = \App\Models\Page::where('slug', 'home')->first();
        $navbarContent = data_get($page?->content, 'navbar', []);
        $navbarPhone = data_get($navbarContent, 'phone', '+1 (234) 567 890');
        $navbarHours = data_get($navbarContent, 'hours', 'Mon - Fri: 9:00 - 18:00');
        $isSticky = (bool) data_get($navbarContent, 'sticky', true);
        $isGlass  = (bool) data_get($navbarContent, 'glass', true);
        $navbarBgColor = data_get($navbarContent, 'bg_color', '#ffffff');
        $navbarTextColor = data_get($navbarContent, 'text_color', '#0d121f');

        // Social links — computed ONCE, used in both Navbar and Footer
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
    ?>

    <title><?php echo $__env->yieldContent('title', $siteName . ' - Premium Car Auctions'); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <?php if($googleMapsKey): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsKey); ?>&libraries=places"></script>
    <?php endif; ?>

    <script>
        window.googleMapsKey = "<?php echo e($googleMapsKey); ?>";
        window.mapProvider = "<?php echo e(\App\Models\SystemSetting::get('google_maps_provider', 'google')); ?>";
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
            font-size: 0.74rem;
            font-weight: 800;
            color: <?php echo e($navbarTextColor); ?>;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px 10px;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            width: 0;
            height: 2px;
            background: #ff4605;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
            border-radius: 99px;
            box-shadow: 0 4px 12px rgba(255, 70, 5, 0.3);
        }

        .nav-link:hover::after {
            width: 14px;
        }

        .nav-link-active::after {
            width: 14px !important;
        }

        /* Extreme Thumping Pulse Animation */
        @keyframes pulse-orange {
            0% { box-shadow: 0 0 0 0 rgba(255, 70, 5, 0.6); }
            70% { box-shadow: 0 0 0 18px rgba(255, 70, 5, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 70, 5, 0); }
        }
        .animate-pulse-orange {
            animation: pulse-orange 2s infinite !important;
        }

        .nav-link:hover {
            color: #ff4605;
        }
        
        .sticky-nav {
            background: <?php echo e($isGlass ? 'rgba(255, 255, 255, 0.7)' : $navbarBgColor); ?>;
            backdrop-filter: <?php echo e($isGlass ? 'blur(12px)' : 'none'); ?>;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .static-nav {
            background: <?php echo e($navbarBgColor); ?>;
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
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="font-sans">

    
    <nav class="<?php echo e($isSticky ? 'sticky-nav' : 'static-nav'); ?> fixed w-full z-50 px-2 lg:px-4 top-0 transition-all duration-300">
        <div class="w-full flex justify-start items-center h-24 gap-8">
            
            <a href="/" class="flex items-center gap-2 group">
                
                <div class="h-20 flex items-center">
                    <?php if($siteLogo): ?>
                        <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" class="h-16 w-auto object-contain">
                    <?php else: ?>
                        <div class="w-20 h-20 rounded-lg bg-white flex items-center justify-center text-[#031629] shadow-xl shadow-slate-200/70 border border-slate-200">
                            <i data-lucide="car-front" class="w-10 h-10 text-[#031629]"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </a>

            
            
            <div class="hidden lg:flex items-center gap-0.5">
                <?php if(isset($headerMenu) && $headerMenu->items): ?>
                    <?php $__currentLoopData = $headerMenu->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item->children->count() > 0): ?>
                            <div class="relative group flex items-center h-full">
                                <button class="nav-link flex items-center gap-1.5 h-full <?php echo e(request()->url() == $item->url ? 'nav-link-active' : ''); ?>">
                                    <?php echo e($item->label); ?>

                                    <i data-lucide="chevron-down" class="w-2.5 opacity-40 group-hover:rotate-180 transition-transform"></i>
                                </button>
                                
                                <div class="absolute top-[80%] left-0 w-56 bg-white rounded-lg shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60]">
                                    <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e($child->url); ?>" class="block px-4 py-3 rounded-md text-[0.7rem] font-black uppercase tracking-wide text-deep-900 hover:bg-orange-50 hover:text-[#ff6900] transition-all">
                                            <?php echo e($child->label); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e($item->url); ?>" class="nav-link <?php echo e(request()->url() == $item->url ? 'text-bazar-500 nav-link-active' : ''); ?>">
                                <?php echo e($item->label); ?>

                            </a>
                        <?php endif; ?>

                        
                        <?php if (! ($loop->last)): ?>
                            <div class="h-4 w-px bg-slate-300/80"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>

            
            <?php if(!empty($navSocials)): ?>
            <div class="hidden lg:flex items-center gap-1.5 ml-2">
                <?php
                $navSocialMeta = [
                    'facebook'  => ['color'=>'#1877f2','title'=>'Facebook', 'path'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                    'instagram' => ['color'=>'#e1306c','title'=>'Instagram','path'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                    'tiktok'    => ['color'=>'#010101','title'=>'TikTok',   'path'=>'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.16 8.16 0 004.77 1.52V6.75a4.85 4.85 0 01-1-.06z'],
                    'youtube'   => ['color'=>'#ff0000','title'=>'YouTube',  'path'=>'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
                    'x'         => ['color'=>'#000000','title'=>'X',        'path'=>'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z'],
                    'linkedin'  => ['color'=>'#0a66c2','title'=>'LinkedIn', 'path'=>'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
                    'whatsapp'  => ['color'=>'#25d366','title'=>'WhatsApp','path'=>'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
                ];
                ?>
                <?php $__currentLoopData = $navSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nsk => $nurl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $nm = $navSocialMeta[$nsk] ?? null; ?>
                <?php if($nm): ?>
                <a href="<?php echo e($nurl); ?>" target="_blank" title="<?php echo e($nm['title']); ?>"
                   class="w-7 h-7 rounded-full flex items-center justify-center bg-gray-100 hover:bg-[#ff4605] hover:text-white transition-all"
                   style="color: <?php echo e($nm['color']); ?>">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="<?php echo e($nm['path']); ?>"/></svg>
                </a>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            
            <div class="flex items-center gap-3 ml-auto">
                <div class="hidden md:flex">
                    <!-- Interactive Sliding Contact Card -->
                    <a href="tel:<?php echo e($navbarPhone); ?>" class="flex flex-row-reverse items-center bg-white/95 rounded-full shadow-[0_15px_40px_-12px_rgba(0,0,0,0.12)] border border-white/40 p-1 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] w-[52px] hover:w-[210px] cursor-pointer overflow-hidden group/contact relative z-40">
                        <!-- Pulse Orange Icon Circle (anchored right) -->
                        <div class="w-[42px] h-[42px] rounded-full bg-[#ff4605] border border-orange-400/20 flex items-center justify-center shrink-0 z-20 transition-transform duration-500 group-hover/contact:scale-95 group-hover/contact:-rotate-12 animate-pulse-orange">
                            <i data-lucide="phone-incoming" class="w-4 h-4 text-white"></i>
                        </div>
                        
                        <!-- Info Area (reveals to the left) -->
                        <div class="opacity-0 translate-x-10 group-hover/contact:opacity-100 group-hover/contact:translate-x-0 transition-all duration-500 delay-75 flex-1 px-4 text-right overflow-hidden whitespace-nowrap pointer-events-none">
                            <p class="text-[0.8rem] font-black text-slate-950 tracking-tight leading-none mb-1"><?php echo e($navbarPhone); ?></p>
                            <p class="text-[0.45rem] font-black text-[#ff4605] uppercase tracking-[0.12em] leading-none text-nowrap opacity-90"><?php echo e($navbarHours); ?></p>
                        </div>
                    </a>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <div class="relative group py-2">
                        <!-- Precise Sliding Avatar Card -->
                        <div class="flex flex-row-reverse items-center bg-white/95 backdrop-blur-3xl rounded-full shadow-[0_15px_40px_-12px_rgba(0,0,0,0.12)] border border-white/40 p-1 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] w-[52px] hover:w-[200px] cursor-pointer overflow-hidden group/card relative z-50">
                            <!-- Image (anchored right) -->
                            <div class="w-[42px] h-[42px] rounded-full overflow-hidden border-2 border-white shadow-lg shrink-0 z-20 transition-transform duration-500 group-hover/card:scale-95 group-hover/card:rotate-3">
                                <img src="https://i.pravatar.cc/100?u=<?php echo e(auth()->id()); ?>" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Name Area (reveals to the left) -->
                            <div class="opacity-0 translate-x-10 group-hover/card:opacity-100 group-hover/card:translate-x-0 transition-all duration-500 delay-75 flex-1 px-4 text-right overflow-hidden whitespace-nowrap pointer-events-none">
                                <p style="color: #ff4605" class="text-[0.45rem] font-bold uppercase tracking-[0.2em] mb-0.5 leading-none opacity-80 text-nowrap">Welcome</p>
                                <p class="text-[0.82rem] font-black text-slate-900 tracking-tighter leading-none text-nowrap"><?php echo e(explode(' ', auth()->user()->name)[0]); ?></p>
                            </div>
                        </div>

                        
                        <div class="absolute right-0 top-full mt-2 w-60 bg-white rounded-[1.4rem] shadow-[0_40px_90px_-20px_rgba(0,0,0,0.35)] border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60] origin-top-right transform scale-95 group-hover:scale-100">
                            <div class="p-4 border-b border-slate-100/50 mb-1.5 bg-slate-50/50 rounded-2xl">
                                <div class="text-[0.85rem] font-black text-slate-950 truncate leading-tight"><?php echo e(auth()->user()->name); ?></div>
                                <div class="text-[0.55rem] text-slate-400 font-bold uppercase mt-1 tracking-[0.1em]">Identity Verified</div>
                            </div>
                            
                            <div class="space-y-0.5">
                                <a href="<?php echo e(route('dealer.profile', auth()->id())); ?>" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-orange-50 hover:text-[#ff4605] transition-all group/item">
                                    <div class="w-7 h-7 rounded-lg bg-orange-100/50 flex items-center justify-center text-orange-600 group-hover/item:scale-110 transition-transform">
                                        <i data-lucide="shield-user" class="w-3"></i>
                                    </div>
                                    Secure Profile
                                </a>
                                
                                <?php if(auth()->user()->is_admin): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-slate-50 transition-all group/item">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover/item:scale-110 transition-transform">
                                            <i data-lucide="cpu" class="w-3"></i>
                                        </div>
                                        Core Systems
                                    </a>
                                <?php endif; ?>

                                <div class="pt-2 mt-2 border-t border-slate-100/50">
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-[0.75rem] font-bold text-red-500 hover:bg-red-50 transition-all group/item">
                                            <div class="w-8 h-8 rounded-lg bg-red-100/50 flex items-center justify-center text-red-600 group-hover/item:scale-110 transition-transform">
                                                <i data-lucide="power" class="w-3.5"></i>
                                            </div>
                                            Terminate Session
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-[0.85rem] font-bold text-deep-900 border-b-2 border-transparent hover:border-[#ff6900] hover:text-[#ff6900] transition-all pb-1 hidden sm:block">Login</a>
                    
                    <a href="<?php echo e(route('login', ['redirect' => request()->url()])); ?>"
                       class="flex items-center gap-2 px-4 py-2 bg-[#1d293d] hover:bg-[#ff6900] text-white text-[0.72rem] font-black uppercase tracking-widest rounded-lg transition-all shadow-md hover:shadow-orange-500/25 group">
                        <i data-lucide="user-circle" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="btn-bazar flex items-center gap-2 animate-pulse-orange">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Sell My Car</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php
        $footerColor   = data_get($page?->content, 'footer.background_color', '#eef3f9');
        $footerDesc    = data_get($page?->content, 'footer.description', "The world's most trusted platform for premium car auctions. We bring the auction room to your screen with transparency and class.");
        $footerAddress = data_get($page?->content, 'footer.address', '123 Luxury Drive, Dubai, UAE');
        $footerEmail   = data_get($page?->content, 'footer.email', 'contact@motorbazar.com');
        $footerPhone   = data_get($page?->content, 'footer.phone', '+971 4 000 0000');
        $footerCopy    = data_get($page?->content, 'footer.copyright', '&copy; ' . date('Y') . ' MOTOR BAZAR. ALL RIGHTS RESERVED.');
        $footerTerms   = data_get($page?->content, 'footer.terms_url', '#');
        $footerPrivacy = data_get($page?->content, 'footer.privacy_url', '#');
        $footerCookies = data_get($page?->content, 'footer.cookies_url', '#');
        $footerQuickLinks = data_get($page?->content, 'footer.quick_links', [
            ['label' => 'Home',            'url' => '/'],
            ['label' => 'Browse Auctions', 'url' => route('auctions.index')],
            ['label' => 'How it Works',    'url' => route('how-it-works')],
            ['label' => 'Sell Your Car',   'url' => '#'],
        ]);
        $footerPages = data_get($page?->content, 'footer.pages', []);
        // Social arrays already built in the head section above

    ?>



    <footer class="text-slate-900 pt-20 pb-12 overflow-hidden relative transition-colors duration-500" style="background-color: <?php echo e($footerColor); ?>;">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-bazar-500/5 to-transparent pointer-events-none"></div>
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">

                
                <div class="lg:col-span-2 space-y-6">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-xl border border-slate-200">
                            <i data-lucide="car-front" class="w-7 h-7 text-[#031629]"></i>
                        </div>
                    </a>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium max-w-sm"><?php echo e($footerDesc); ?></p>
                    
                    <?php if(!empty($footerSocials)): ?>
                    <?php
                    $footerSocialMeta = [
                        'facebook'  => ['hover'=>'hover:bg-bazar-500', 'title'=>'Facebook', 'path'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                        'instagram' => ['hover'=>'hover:bg-pink-500',  'title'=>'Instagram','path'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
                        'tiktok'    => ['hover'=>'hover:bg-black',     'title'=>'TikTok',  'path'=>'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.16 8.16 0 004.77 1.52V6.75a4.85 4.85 0 01-1-.06z'],
                        'youtube'   => ['hover'=>'hover:bg-red-500',   'title'=>'YouTube', 'path'=>'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
                        'x'         => ['hover'=>'hover:bg-black',     'title'=>'X',       'path'=>'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z'],
                        'linkedin'  => ['hover'=>'hover:bg-blue-600',  'title'=>'LinkedIn','path'=>'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
                        'whatsapp'  => ['hover'=>'hover:bg-green-500', 'title'=>'WhatsApp','path'=>'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
                    ];
                    ?>
                    <div class="flex gap-3 flex-wrap">
                        <?php $__currentLoopData = $footerSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fsk => $fsurl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $fm = $footerSocialMeta[$fsk] ?? null; ?>
                        <?php if($fm): ?>
                        <a href="<?php echo e($fsurl); ?>" target="_blank" title="<?php echo e($fm['title']); ?>"
                           class="w-9 h-9 rounded-full bg-white flex items-center justify-center <?php echo e($fm['hover']); ?> hover:text-white transition-all border border-slate-200 shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="<?php echo e($fm['path']); ?>"/></svg>
                        </a>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Quick Links
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        <?php $__currentLoopData = $footerQuickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(data_get($link,'url','#')); ?>"
                               class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform shrink-0"></i>
                                <?php echo e(data_get($link,'label','')); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                
                <div>
                    <?php if(!empty($footerPages)): ?>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Pages
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        <?php $__currentLoopData = $footerPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(data_get($pg,'url','#')); ?>"
                               class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-bazar-500 group-hover:translate-x-1 transition-transform shrink-0"></i>
                                <?php echo e(data_get($pg,'label','')); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <?php endif; ?>
                </div>

                
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Contact Us
                        <div class="h-0.5 w-6 bg-bazar-500 rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-4">
                        <?php if($footerAddress): ?>
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-bazar-500 mt-0.5 shrink-0"></i>
                            <span class="text-sm font-medium text-slate-600"><?php echo e($footerAddress); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if($footerEmail): ?>
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-bazar-500 shrink-0"></i>
                            <a href="mailto:<?php echo e($footerEmail); ?>" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"><?php echo e($footerEmail); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if($footerPhone): ?>
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-bazar-500 shrink-0"></i>
                            <a href="tel:<?php echo e($footerPhone); ?>" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"><?php echo e($footerPhone); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            
            <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest"><?php echo $footerCopy; ?></p>
                <div class="flex gap-6">
                    <?php if($footerTerms && $footerTerms !== '#'): ?>
                    <a href="<?php echo e($footerTerms); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Terms</a>
                    <?php endif; ?>
                    <?php if($footerPrivacy && $footerPrivacy !== '#'): ?>
                    <a href="<?php echo e($footerPrivacy); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Privacy</a>
                    <?php endif; ?>
                    <?php if($footerCookies && $footerCookies !== '#'): ?>
                    <a href="<?php echo e($footerCookies); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Cookies</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>

        

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\auction_app\resources\views/layouts/app.blade.php ENDPATH**/ ?>