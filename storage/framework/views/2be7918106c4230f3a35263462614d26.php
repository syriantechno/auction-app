<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['alpine' => false]));

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

foreach (array_filter((['alpine' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'flex flex-wrap items-center gap-4 bg-white/40 p-5 rounded-2xl border border-slate-100 backdrop-blur-sm sticky top-4 z-20 shadow-sm mb-10 transition-all hover:bg-white/60 hover:shadow-md duration-500'])); ?>>
    <!-- Filter Icon Indicator -->
    <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center mr-2 shadow-lg shadow-slate-200">
        <i data-lucide="filter" class="w-4 h-4 text-[#ff6900]"></i>
    </div>

    <?php echo e($slot); ?>

    
    <!-- Optional Status Indicator -->
    <div class="ml-auto hidden md:flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
        <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest leading-none">Live Access</span>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-filter-bar.blade.php ENDPATH**/ ?>