<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'default', // 'default' or 'modern' (Home 2)
    'siteLogo' => null,
    'siteName' => null,
    'description' => null,
    'address' => null,
    'email' => null,
    'phone' => null,
    'socials' => null,
    'quickLinks' => [],
    'pages' => [],
    'copyright' => null,
    'termsUrl' => '#',
    'privacyUrl' => '#',
    'cookiesUrl' => '#',
    'bgColor' => '#eef3f9'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'variant' => 'default', // 'default' or 'modern' (Home 2)
    'siteLogo' => null,
    'siteName' => null,
    'description' => null,
    'address' => null,
    'email' => null,
    'phone' => null,
    'socials' => null,
    'quickLinks' => [],
    'pages' => [],
    'copyright' => null,
    'termsUrl' => '#',
    'privacyUrl' => '#',
    'cookiesUrl' => '#',
    'bgColor' => '#eef3f9'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $siteLogo    = $siteLogo    ?? \App\Models\SystemSetting::get('site_logo');
    $siteName    = $siteName    ?? \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
    $description = $description ?? \App\Models\SystemSetting::get('footer_description', 'Your premium automotive marketplace.');
    $address     = $address     ?? \App\Models\SystemSetting::get('site_address');
    $email       = $email       ?? \App\Models\SystemSetting::get('site_email');
    $phone       = $phone       ?? \App\Models\SystemSetting::get('site_phone');
    $copyright   = $copyright   ?? \App\Models\SystemSetting::get('footer_copyright', '© '.date('Y').' Motor Bazar. All rights reserved.');
    
    if (is_null($socials)) {
        $socials = [
            'facebook'  => \App\Models\SystemSetting::get('facebook_url'),
            'instagram' => \App\Models\SystemSetting::get('instagram_url'),
            'tiktok'    => \App\Models\SystemSetting::get('tiktok_url'),
            'youtube'   => \App\Models\SystemSetting::get('youtube_url'),
            'x'         => \App\Models\SystemSetting::get('twitter_url'),
        ];
        $socials = array_filter($socials);
    }
?>

<?php if($variant === 'modern'): ?>
    
    <footer class="h2-footer-root" style="background-color: <?php echo e($bgColor); ?> !important;">
        <div class="h2-section-container w-full px-6 lg:px-12">
            <div class="footer-card">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    
                    <div class="space-y-6">
                        <?php if($siteLogo): ?>
                            <img src="<?php echo e(str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)); ?>" alt="<?php echo e($siteName); ?>" class="h-14 w-auto object-contain">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-xl bg-[#031629] flex items-center justify-center text-white shadow-lg">
                                <i data-lucide="car-front" class="w-6 h-6"></i>
                            </div>
                        <?php endif; ?>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed"><?php echo e($description); ?></p>
                    </div>

                    
                    <div>
                        <span class="footer-header-lux">Quick Links</span>
                        <div class="flex flex-col">
                            <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(data_get($link,'url','#')); ?>" class="footer-link-lux"><?php echo e(data_get($link,'label','')); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    
                    <div>
                        <span class="footer-header-lux">Direct Contact</span>
                        <div class="space-y-4">
                            <?php if($phone): ?>
                                <div class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6900] group-hover:bg-[#ff6900] group-hover:text-white transition-all">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <a href="tel:<?php echo e($phone); ?>" class="text-slate-600 font-bold text-sm"><?php echo e($phone); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if($email): ?>
                                <div class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6900] group-hover:bg-[#ff6900] group-hover:text-white transition-all">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </div>
                                    <a href="mailto:<?php echo e($email); ?>" class="text-slate-600 font-bold text-sm"><?php echo e($email); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div>
                        <span class="footer-header-lux">Our Location</span>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-4"><?php echo e($address); ?></p>
                        <div class="flex gap-2">
                            <?php $__currentLoopData = ['facebook', 'instagram', 'tiktok', 'youtube']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($socials[$sk])): ?>
                                    <a href="<?php echo e($socials[$sk]); ?>" target="_blank" class="social-item-lux">
                                        <i data-lucide="<?php echo e($sk === 'x' ? 'twitter' : $sk); ?>" class="w-4.5 h-4.5"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom-lux">
                <p><?php echo $copyright; ?></p>
                <div class="flex gap-6">
                    <a href="<?php echo e($termsUrl); ?>" class="hover:text-[#ff6900] transition-colors">Terms</a>
                    <a href="<?php echo e($privacyUrl); ?>" class="hover:text-[#ff6900] transition-colors">Privacy</a>
                    <a href="<?php echo e($cookiesUrl); ?>" class="hover:text-[#ff6900] transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
<?php else: ?>
    
    <footer class="text-slate-900 pt-20 pb-12 overflow-hidden relative transition-colors duration-500" style="background-color: <?php echo e($bgColor); ?>;">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-bazar-500/5 to-transparent pointer-events-none"></div>
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">

                
                <div class="lg:col-span-2 space-y-6">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-xl border border-slate-200">
                             <?php if($siteLogo): ?>
                                <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" class="h-8 w-auto">
                             <?php else: ?>
                                <i data-lucide="car-front" class="w-7 h-7 text-[#031629]"></i>
                             <?php endif; ?>
                        </div>
                    </a>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium max-w-sm"><?php echo e($description); ?></p>
                    <div class="flex gap-3 flex-wrap">
                        <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sk => $surl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <a href="<?php echo e($surl); ?>" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-[#ff6900] hover:text-white transition-all border border-slate-200 shadow-sm">
                                <i data-lucide="<?php echo e($sk === 'x' ? 'twitter' : $sk); ?>" class="w-4 h-4"></i>
                             </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Quick Links
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(data_get($link,'url','#')); ?>" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-[#ff6900] group-hover:translate-x-1 transition-transform shrink-0"></i>
                                <?php echo e(data_get($link,'label','')); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                
                <div>
                    <?php if(!empty($pages)): ?>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Pages
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(data_get($pg,'url','#')); ?>" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-[#ff6900] group-hover:translate-x-1 transition-transform shrink-0"></i>
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
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-4">
                        <?php if($address): ?>
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6900] mt-0.5 shrink-0"></i>
                            <span class="text-sm font-medium text-slate-600"><?php echo e($address); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if($email): ?>
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-[#ff6900] shrink-0"></i>
                            <a href="mailto:<?php echo e($email); ?>" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"><?php echo e($email); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if($phone): ?>
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-[#ff6900] shrink-0"></i>
                            <a href="tel:<?php echo e($phone); ?>" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors"><?php echo e($phone); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest"><?php echo $copyright; ?></p>
                <div class="flex gap-6">
                    <a href="<?php echo e($termsUrl); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Terms</a>
                    <a href="<?php echo e($privacyUrl); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Privacy</a>
                    <a href="<?php echo e($cookiesUrl); ?>" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>
<?php /**PATH F:\auction_app\resources\views/components/footer.blade.php ENDPATH**/ ?>