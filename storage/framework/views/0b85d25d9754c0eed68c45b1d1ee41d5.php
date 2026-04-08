<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => null,
    'icon'  => null,
    'variant' => 'glass'
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
    'title' => null,
    'icon'  => null,
    'variant' => 'glass'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-100 shadow-sm p-8 transition-all group'])); ?>>
    <?php if($title || $icon): ?>
        <div class="flex items-center gap-3 mb-8 border-b border-slate-100/50 pb-5">
            <?php if($icon): ?>
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-[#ff6900] shadow-lg shadow-slate-200">
                    <i data-lucide="<?php echo e($icon); ?>" class="w-4 h-4"></i>
                </div>
            <?php endif; ?>
            <?php if($title): ?>
                <h4 class="text-[0.7rem] font-black uppercase text-slate-800 tracking-[0.3em]"><?php echo e($title); ?></h4>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="relative z-10">
        <?php echo e($slot); ?>

    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-card.blade.php ENDPATH**/ ?>