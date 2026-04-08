<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => 'layout-dashboard',
    'title' => 'Admin',
    'highlight' => 'Page',
    'subtitle' => 'System Management',
    'dot' => 'emerald',
    'reverse' => false,
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
    'icon' => 'layout-dashboard',
    'title' => 'Admin',
    'highlight' => 'Page',
    'subtitle' => 'System Management',
    'dot' => 'emerald',
    'reverse' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'space-y-10 animate-in fade-in duration-1000'])); ?>>
    <!-- Standard Header -->
    <?php if (isset($component)) { $__componentOriginal489711a049975b0fbcd3875ea3652a04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal489711a049975b0fbcd3875ea3652a04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-header','data' => ['icon' => $icon,'title' => $title,'highlight' => $highlight,'subtitle' => $subtitle,'dot' => $dot,'reverse' => $reverse]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title),'highlight' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($highlight),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subtitle),'dot' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($dot),'reverse' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reverse)]); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <?php echo e($actions ?? ''); ?>

         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal489711a049975b0fbcd3875ea3652a04)): ?>
<?php $attributes = $__attributesOriginal489711a049975b0fbcd3875ea3652a04; ?>
<?php unset($__attributesOriginal489711a049975b0fbcd3875ea3652a04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal489711a049975b0fbcd3875ea3652a04)): ?>
<?php $component = $__componentOriginal489711a049975b0fbcd3875ea3652a04; ?>
<?php unset($__componentOriginal489711a049975b0fbcd3875ea3652a04); ?>
<?php endif; ?>

    <!-- Page Content -->
    <main>
        <?php echo $slot; ?>

    </main>
</div>
<?php /**PATH F:\auction_app\resources\views/components/admin-page-standard.blade.php ENDPATH**/ ?>