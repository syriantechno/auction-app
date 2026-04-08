<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'count' => 0,
    'emptyTitle' => 'No Records Found',
    'emptySubtitle' => 'The system is currently clear. Data will appear here once registered.',
    'emptyIcon' => 'database-backup',
    'alpine' => false,
    'alpineCount' => 'items.length'
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
    'count' => 0,
    'emptyTitle' => 'No Records Found',
    'emptySubtitle' => 'The system is currently clear. Data will appear here once registered.',
    'emptyIcon' => 'database-backup',
    'alpine' => false,
    'alpineCount' => 'items.length'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div <?php echo e($attributes->merge(['class' => 'relative min-h-[500px] animate-in fade-in slide-in-from-bottom-5 duration-1000'])); ?>>
    <?php if($alpine): ?>
        <!-- Alpine.js Dynamic Mode -->
        <template x-if="<?php echo e($alpineCount); ?> > 0">
            <div class="space-y-6">
                <?php echo e($slot); ?>

            </div>
        </template>

        <template x-if="<?php echo e($alpineCount); ?> === 0">
            <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => $emptyTitle,'subtitle' => $emptySubtitle,'icon' => $emptyIcon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptyTitle),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptySubtitle),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptyIcon)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $attributes = $__attributesOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__attributesOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $component = $__componentOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__componentOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
        </template>
    <?php else: ?>
        <!-- Standard Blade Mode -->
        <?php if($count > 0): ?>
            <div class="space-y-6">
                <?php echo e($slot); ?>

            </div>
        <?php else: ?>
            <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => $emptyTitle,'subtitle' => $emptySubtitle,'icon' => $emptyIcon]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptyTitle),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptySubtitle),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($emptyIcon)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $attributes = $__attributesOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__attributesOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $component = $__componentOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__componentOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-data-view.blade.php ENDPATH**/ ?>