<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => '',
    'placeholder' => '',
    'type' => 'text',
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
    'label' => '',
    'placeholder' => '',
    'type' => 'text',
    'icon' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="space-y-2 group transition-all duration-300">
    <?php if($label): ?>
        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 group-focus-within:text-[#ff6900] transition-colors translate-y-1 block">
            <?php echo e($label); ?>

        </label>
    <?php endif; ?>

    <div class="relative">
        <?php if($icon): ?>
            <div class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-[#ff6900] transition-colors pointer-events-none">
                <i data-lucide="<?php echo e($icon); ?>" class="w-full h-full"></i>
            </div>
        <?php endif; ?>

        <input type="<?php echo e($type); ?>" 
               placeholder="<?php echo e($placeholder); ?>"
               <?php echo e($attributes->merge(['class' => 'w-full h-[52px] bg-slate-50 border-2 border-slate-100/50 rounded-md ' . ($icon ? 'pl-12' : 'px-6') . ' pr-6 text-[0.85rem] font-black text-slate-800 outline-none focus:border-orange-500/30 focus:bg-white focus:ring-[6px] focus:ring-orange-500/5 transition-all shadow-sm placeholder:text-slate-300 placeholder:font-bold'])); ?>>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-input.blade.php ENDPATH**/ ?>