<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => null,
    'variant' => 'slate',
    'title' => '',
    'click' => '',
    'href' => null
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
    'variant' => 'slate',
    'title' => '',
    'click' => '',
    'href' => null
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
        'slate' => [
            'btn' => 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-orange-500/40 hover:text-[#ff6900]',
            'glow' => 'group-hover:shadow-orange-500/10'
        ],
        'red' => [
            'btn' => 'bg-white border-slate-200 text-red-500 hover:bg-red-50 hover:border-red-500/40 hover:text-red-600',
            'glow' => 'group-hover:shadow-red-500/10'
        ],
        'emerald' => [
            'btn' => 'bg-white border-slate-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-500/40 hover:text-emerald-700',
            'glow' => 'group-hover:shadow-emerald-500/10'
        ],
        'orange' => [
            'btn' => 'bg-white border-slate-200 text-[#FF6900] hover:bg-orange-50 hover:border-orange-500/40 hover:text-[#FF6900]',
            'glow' => 'group-hover:shadow-orange-500/10'
        ]
    ];
    $v = $variants[$variant] ?? $variants['slate'];
    $tag = $href ? 'a' : 'button';
?>

<<?php echo e($tag); ?> 
    <?php if($href): ?> href="<?php echo e($href); ?>" <?php endif; ?>
    <?php if($click): ?> @click="<?php echo e($click); ?>" <?php endif; ?> 
    title="<?php echo e($title); ?>"
    class="group w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-xl active:scale-90 border <?php echo e($v['btn']); ?> <?php echo e($v['glow']); ?>">
    
    <?php if($icon == 'edit-3' || $icon == 'user-cog' || $icon == 'edit'): ?>
        <i data-lucide="edit-3" class="w-5 h-5 transition-transform duration-500 group-hover:rotate-12 group-hover:scale-110"></i>
    <?php elseif($icon == 'trash-2' || $icon == 'trash'): ?>
        <i data-lucide="trash-2" class="w-5 h-5 transition-transform duration-500 group-hover:-translate-y-0.5 group-hover:scale-110"></i>
    <?php elseif($icon == 'eye'): ?>
        <i data-lucide="eye" class="w-5 h-5 transition-transform duration-500 group-hover:scale-125"></i>
    <?php elseif($icon): ?>
        <i data-lucide="<?php echo e($icon); ?>" class="w-5 h-5 transition-transform duration-500 group-hover:scale-110"></i>
    <?php endif; ?>
    
    <?php echo e($slot); ?>

</<?php echo e($tag); ?>>
<?php /**PATH F:\auction_app\resources\views/components/admin-action.blade.php ENDPATH**/ ?>