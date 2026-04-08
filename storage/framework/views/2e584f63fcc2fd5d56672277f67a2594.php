<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => null,
    'variant' => 'primary'
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
    'icon' => null,
    'variant' => 'primary'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$variants = [
    'primary'   => 'bg-slate-900 hover:bg-[#ff6900] text-white shadow-slate-200/50',
    'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 shadow-transparent',
    'orange'    => 'bg-[#ff6900] hover:bg-slate-900 text-white shadow-orange-200/50',
    'red'       => 'bg-red-50 hover:bg-red-500 text-red-600 hover:text-white shadow-transparent',
    'outline'   => 'bg-transparent border-2 border-slate-100 hover:border-[#ff6900]/30 text-slate-600 shadow-transparent'
];
$vClass = $variants[$variant] ?? $variants['primary'];
?>

<button <?php echo e($attributes->merge(['class' => "group $vClass px-6 py-3.5 rounded-md font-black text-[0.65rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center justify-center gap-2.5 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"])); ?>>
    <?php if($icon): ?>
        <i data-lucide="<?php echo e($icon); ?>" class="w-4 h-4 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12"></i>
    <?php endif; ?>
    
    <span><?php echo e($slot); ?></span>
</button>
<?php /**PATH D:\auction_app\resources\views/components/admin-button.blade.php ENDPATH**/ ?>