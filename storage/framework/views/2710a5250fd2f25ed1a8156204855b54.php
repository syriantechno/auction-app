

<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon'      => 'layout-dashboard',
    'title'     => 'Page',
    'highlight' => '',
    'subtitle'  => '',
    'dot'       => 'emerald',
    'reverse'   => false,
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
    'icon'      => 'layout-dashboard',
    'title'     => 'Page',
    'highlight' => '',
    'subtitle'  => '',
    'dot'       => 'emerald',
    'reverse'   => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$dotColors = [
    'emerald' => 'bg-emerald-500',
    'amber'   => 'bg-amber-400',
    'blue'    => 'bg-blue-500',
    'violet'  => 'bg-violet-500',
    'rose'    => 'bg-rose-500',
    'orange'  => 'bg-[#ff6900]',
    'cyan'    => 'bg-cyan-500',
    'indigo'  => 'bg-indigo-500',
];
$dotClass = $dotColors[$dot] ?? 'bg-emerald-500';
?>

<div class="w-full flex <?php echo e($reverse ? 'flex-row-reverse' : 'flex-row'); ?> items-center justify-between gap-4 pb-8 border-b border-slate-100/50">

    
    <div class="flex items-center gap-6">

        
        <div class="relative">
            <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl shadow-[#031629]/20 transform rotate-3">
                <i data-lucide="<?php echo e($icon); ?>" class="w-7 h-7 text-[#ff6900]"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg <?php echo e($dotClass); ?> border-[3px] border-[#f8fafc] animate-pulse"></div>
        </div>

        
        <div>
            <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                <?php echo e($title); ?>

                <?php if($highlight): ?>
                    <span class="text-[#ff6900]"><?php echo e($highlight); ?></span>
                <?php endif; ?>
            </h1>
            <?php if($subtitle): ?>
            <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-3">
                <?php echo e($subtitle); ?>

            </p>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if(isset($actions)): ?>
    <div class="flex items-center gap-3 flex-shrink-0">
        <?php echo e($actions); ?>

    </div>
    <?php endif; ?>

</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-header.blade.php ENDPATH**/ ?>