<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'number' => null,
    'title' => 'Sample Title',
    'subtitle' => null,
    'icon' => null
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
    'number' => null,
    'title' => 'Sample Title',
    'subtitle' => null,
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/40 flex flex-wrap md:flex-nowrap items-center justify-between transition-all hover:bg-white/60 group shadow-sm hover:shadow-md duration-500'])); ?>>
    <div class="flex items-center gap-5">
        <?php if($number): ?>
            <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-[#ff6900] font-black italic text-xl shadow-lg shadow-slate-200 group-hover:scale-110 transition-transform duration-500">
                <?php echo e($number); ?>

            </div>
        <?php elseif($icon): ?>
             <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-[#ff6900] transition-colors duration-500 border border-slate-100">
                <i data-lucide="<?php echo e($icon); ?>" class="w-5 h-5"></i>
            </div>
        <?php endif; ?>
        
        <div class="min-w-0">
            <h5 class="font-black text-slate-800 uppercase text-[0.85rem] truncate"><?php echo e($title); ?></h5>
            <?php if($subtitle): ?>
                <p class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-[0.1em] mt-1 italic"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Actions Area -->
    <div class="flex items-center gap-3 mt-4 md:mt-0 ml-auto md:ml-0">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-data-item.blade.php ENDPATH**/ ?>