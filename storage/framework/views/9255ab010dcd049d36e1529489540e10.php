<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'No Records Found',
    'subtitle' => 'The system current list is empty. Try adding a new entry to get started.',
    'icon' => 'box'
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
    'title' => 'No Records Found',
    'subtitle' => 'The system current list is empty. Try adding a new entry to get started.',
    'icon' => 'box'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="col-span-full py-32 text-center bg-transparent flex flex-col items-center justify-center space-y-8 animate-in fade-in duration-1000">
    <!-- Clean Jumping Icon (No Card) -->
    <div class="animate-bounce">
        <i data-lucide="<?php echo e($icon); ?>" class="w-16 h-16 text-slate-300 stroke-[1.5]"></i>
    </div>

    <div class="max-w-xs space-y-3">
        <h3 class="text-[0.75rem] font-black text-slate-400 uppercase tracking-[0.4em] italic"><?php echo e($title); ?></h3>
        <p class="text-[0.65rem] text-slate-300 font-bold uppercase tracking-widest leading-relaxed"><?php echo e($subtitle); ?></p>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-empty-state.blade.php ENDPATH**/ ?>