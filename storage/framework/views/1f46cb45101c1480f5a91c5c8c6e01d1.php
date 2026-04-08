<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Total Units',
    'value' => '0',
    'icon'  => null,
    'color' => 'slate',
    'alpineValue' => null
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
    'label' => 'Total Units',
    'value' => '0',
    'icon'  => null,
    'color' => 'slate',
    'alpineValue' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$colorMap = [
    'slate'   => ['text' => 'text-[#031629]', 'accent' => 'bg-slate-300',   'glow' => 'bg-slate-100',   'icon' => 'text-slate-400'],
    'orange'  => ['text' => 'text-[#ff6900]', 'accent' => 'bg-[#ff6900]',   'glow' => 'bg-orange-50',   'icon' => 'text-[#ff6900]'],
    'emerald' => ['text' => 'text-emerald-500', 'accent' => 'bg-emerald-500', 'glow' => 'bg-emerald-50', 'icon' => 'text-emerald-500'],
    'rose'    => ['text' => 'text-rose-500',    'accent' => 'bg-rose-500',    'glow' => 'bg-rose-50',    'icon' => 'text-rose-500'],
    'amber'   => ['text' => 'text-amber-500',   'accent' => 'bg-amber-500',   'glow' => 'bg-amber-50',   'icon' => 'text-amber-500'],
];
$c = $colorMap[$color] ?? $colorMap['slate'];
?>

<div <?php echo e($attributes->merge(['class' => 'group relative bg-slate-50 rounded-3xl p-6 border border-slate-100/50 shadow-sm transition-all duration-500 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1 overflow-hidden'])); ?>>
    
    <!-- Accent Bar -->
    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($c['accent']); ?> opacity-20 group-hover:opacity-100 transition-all duration-700"></div>

    <!-- Background Glow Icon (Faded) -->
    <div class="absolute -right-6 -bottom-6 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-700">
        <i data-lucide="<?php echo e($icon ?? 'box'); ?>" class="w-32 h-32 rotate-12"></i>
    </div>

    <!-- Header Section -->
    <div class="flex items-start justify-between mb-6 relative z-10">
        <div>
            <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 italic opacity-80 group-hover:opacity-100 transition-opacity">
                <?php echo e($label); ?>

            </p>
            <div class="w-8 h-[2px] bg-slate-200 group-hover:w-12 group-hover:<?php echo e($c['accent']); ?> transition-all duration-500"></div>
        </div>
        
        <?php if($icon): ?>
            <div class="w-12 h-12 rounded-2xl <?php echo e($c['glow']); ?> flex items-center justify-center shadow-sm border border-white transition-all duration-700 group-hover:rotate-[360deg] group-hover:scale-110">
                <i data-lucide="<?php echo e($icon); ?>" class="w-5 h-5 <?php echo e($c['icon']); ?>"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Value Section -->
    <div class="relative z-10">
        <h3 class="text-4xl font-black <?php echo e($c['text']); ?> italic leading-none tracking-tighter transition-transform duration-500 group-hover:translate-x-1" 
            <?php if($alpineValue): ?> x-text="<?php echo e($alpineValue); ?>" <?php endif; ?>>
            <?php echo e($value); ?>

        </h3>
        
        <div class="mt-4 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full <?php echo e($c['accent']); ?> animate-pulse"></span>
            <span class="text-[0.5rem] font-bold text-slate-400 uppercase tracking-widest italic">Operational Metric Ready</span>
        </div>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-stat-card.blade.php ENDPATH**/ ?>