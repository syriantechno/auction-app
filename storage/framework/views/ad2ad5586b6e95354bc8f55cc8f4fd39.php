<?php $__env->startSection('title', 'Component Showcase'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ opened: false }">
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'component','title' => 'Elite','highlight' => 'Showcase','subtitle' => 'Master component library and design system verification','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'component','title' => 'Elite','highlight' => 'Showcase','subtitle' => 'Master component library and design system verification','dot' => 'orange']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'plus','@click' => 'opened = true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'plus','@click' => 'opened = true']); ?>Open Test Modal <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'external-link','variant' => 'outline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'external-link','variant' => 'outline']); ?>Preview Live <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>

        <!-- 1. Stats Grid Showcase -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Total Revenue','value' => '$128.4k','icon' => 'dollar-sign','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total Revenue','value' => '$128.4k','icon' => 'dollar-sign','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Active Users','value' => '2,840','icon' => 'users','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Active Users','value' => '2,840','icon' => 'users','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Pending Tasks','value' => '14','icon' => 'clock','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Pending Tasks','value' => '14','icon' => 'clock','color' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Critical Errors','value' => '0','icon' => 'alert-circle','color' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Critical Errors','value' => '0','icon' => 'alert-circle','color' => 'rose']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
        </div>

        <!-- Pages Processed Stats -->
        <div class="bg-gradient-to-r from-emerald-50 to-orange-50 rounded-xl border border-emerald-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#ff6900]"></i>
                Pages Successfully Processed
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'HR Dashboard','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'HR Dashboard','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Departments','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Departments','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Positions','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Positions','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Employees','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employees','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Shifts','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Shifts','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Attendance','value' => '✓','icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Attendance','value' => '✓','icon' => 'check-circle','color' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $attributes = $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c)): ?>
<?php $component = $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c; ?>
<?php unset($__componentOriginal6ccb7413961bd805d5db6baba7b26a7c); ?>
<?php endif; ?>
            </div>
            <div class="mt-4 p-3 bg-emerald-100 rounded-lg border border-emerald-200">
                <p class="text-sm font-medium text-emerald-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    <strong>6 pages</strong> successfully unified with the new design system
                </p>
            </div>
        </div>

        <!-- 2. Filter Bar Showcase -->
        <?php if (isset($component)) { $__componentOriginal6b29dae6f7a16af6949451a73d5dcc44 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-filter-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex-1 max-w-sm">
                <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['icon' => 'search','placeholder' => 'Search components...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'search','placeholder' => 'Search components...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
            </div>
            <div class="w-48">
                <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['icon' => 'tag','name' => 'category','placeholder' => 'All Categories']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'tag','name' => 'category','placeholder' => 'All Categories']); ?>
                    <option>All Categories</option>
                    <option>Interface</option>
                    <option>Navigation</option>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $attributes = $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $component = $__componentOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
            </div>
            <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'secondary','icon' => 'download']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','icon' => 'download']); ?>Export Report <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44)): ?>
<?php $attributes = $__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44; ?>
<?php unset($__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b29dae6f7a16af6949451a73d5dcc44)): ?>
<?php $component = $__componentOriginal6b29dae6f7a16af6949451a73d5dcc44; ?>
<?php unset($__componentOriginal6b29dae6f7a16af6949451a73d5dcc44); ?>
<?php endif; ?>

        <!-- 3. Form & Elements Section -->
        <?php if (isset($component)) { $__componentOriginal4658ca741dca2689097dd49737f7416c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4658ca741dca2689097dd49737f7416c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-card','data' => ['title' => 'System Interface Core','icon' => 'layers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'System Interface Core','icon' => 'layers']); ?>
            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Standard Form Elements</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Full Name','placeholder' => 'John Doe','icon' => 'user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Full Name','placeholder' => 'John Doe','icon' => 'user']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Department','icon' => 'layers','name' => 'dept']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Department','icon' => 'layers','name' => 'dept']); ?>
                        <option>Engineering</option>
                        <option>HR & Culture</option>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $attributes = $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $component = $__componentOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
                    <div class="md:col-span-2">
                        <?php if (isset($component)) { $__componentOriginalf4891fa44f09df093b640787c7c16efe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf4891fa44f09df093b640787c7c16efe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-picker','data' => ['dateName' => 'test_date','timeName' => 'test_time','dateId' => 'showcaseDate','timeId' => 'showcaseTime']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateName' => 'test_date','timeName' => 'test_time','dateId' => 'showcaseDate','timeId' => 'showcaseTime']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf4891fa44f09df093b640787c7c16efe)): ?>
<?php $attributes = $__attributesOriginalf4891fa44f09df093b640787c7c16efe; ?>
<?php unset($__attributesOriginalf4891fa44f09df093b640787c7c16efe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf4891fa44f09df093b640787c7c16efe)): ?>
<?php $component = $__componentOriginalf4891fa44f09df093b640787c7c16efe; ?>
<?php unset($__componentOriginalf4891fa44f09df093b640787c7c16efe); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Table Action Components</h4>
                <div class="flex items-center gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'eye','title' => 'View Details','variant' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'eye','title' => 'View Details','variant' => 'slate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'edit','title' => 'Edit Record','variant' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'edit','title' => 'Edit Record','variant' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'trash','title' => 'Delete Permanent','variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'trash','title' => 'Delete Permanent','variant' => 'red']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'plus','title' => 'Add New','variant' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'plus','title' => 'Add New','variant' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Button Variants</h4>
                <div class="flex flex-wrap gap-4">
                    <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary']); ?>Primary Action <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'orange','icon' => 'zap']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'orange','icon' => 'zap']); ?>Orange High-Click <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'secondary','icon' => 'settings']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','icon' => 'settings']); ?>Secondary Tools <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'red','icon' => 'trash-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'red','icon' => 'trash-2']); ?>Danger Zone <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'outline','icon' => 'share-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','icon' => 'share-2']); ?>Outline Ghost <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Status & Action Markers</h4>
                <div class="flex gap-4">
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'edit-3','title' => 'Edit','variant' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'edit-3','title' => 'Edit','variant' => 'slate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'check','title' => 'Approve','variant' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check','title' => 'Approve','variant' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'x','title' => 'Reject','variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'x','title' => 'Reject','variant' => 'red']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'download','title' => 'Save','variant' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'download','title' => 'Save','variant' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                </div>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4658ca741dca2689097dd49737f7416c)): ?>
<?php $attributes = $__attributesOriginal4658ca741dca2689097dd49737f7416c; ?>
<?php unset($__attributesOriginal4658ca741dca2689097dd49737f7416c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4658ca741dca2689097dd49737f7416c)): ?>
<?php $component = $__componentOriginal4658ca741dca2689097dd49737f7416c; ?>
<?php unset($__componentOriginal4658ca741dca2689097dd49737f7416c); ?>
<?php endif; ?>

        <!-- 4. Data View & Empty State Showcase -->
        <div class="space-y-6">
             <h4 class="text-[0.7rem] font-black uppercase text-[#ff6900] tracking-[0.4em] text-center mb-10">— Intelligent Data View Simulation —</h4>
             
             <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Case A: With Data -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: With Active Data</p>
                    <?php if (isset($component)) { $__componentOriginalf5a744a70ee982199708c37a824ad023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5a744a70ee982199708c37a824ad023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-view','data' => ['count' => 1]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => 1]); ?>
                        <?php if (isset($component)) { $__componentOriginald6ed9e3c29e55aa4262c98eb2df87e42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald6ed9e3c29e55aa4262c98eb2df87e42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-item','data' => ['number' => '01','title' => 'Sample Active Record','subtitle' => 'Active System Instance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => '01','title' => 'Sample Active Record','subtitle' => 'Active System Instance']); ?>
                            <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'edit-3','title' => 'Edit','variant' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'edit-3','title' => 'Edit','variant' => 'slate']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'eye','title' => 'View','variant' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'eye','title' => 'View','variant' => 'orange']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald6ed9e3c29e55aa4262c98eb2df87e42)): ?>
<?php $attributes = $__attributesOriginald6ed9e3c29e55aa4262c98eb2df87e42; ?>
<?php unset($__attributesOriginald6ed9e3c29e55aa4262c98eb2df87e42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald6ed9e3c29e55aa4262c98eb2df87e42)): ?>
<?php $component = $__componentOriginald6ed9e3c29e55aa4262c98eb2df87e42; ?>
<?php unset($__componentOriginald6ed9e3c29e55aa4262c98eb2df87e42); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $attributes = $__attributesOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__attributesOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $component = $__componentOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__componentOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
                </div>

                <?php
                    $mockTask = (object)[
                        'id' => 77,
                        'car_id' => 123,
                        'car_details' => [
                            'make' => 'Mercedes-Benz',
                            'model' => 'G-Class AMG',
                            'year' => '2024',
                            'location' => 'Downtown Dubai, UAE',
                            'inspection_date' => date('Y-m-d'),
                            'inspection_time' => '10:30 AM',
                            'phone' => '+971 50 123 4567',
                            'vin' => 'G63-AMG-TEST-2024'
                        ]
                    ];
                ?>
                <!-- 5. Mission Card Showcase -->
                <div class="col-span-12 grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Original Operational Card</p>
                        <?php if (isset($component)) { $__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-mission-card','data' => ['task' => $mockTask,'isToday' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-mission-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['task' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mockTask),'isToday' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a)): ?>
<?php $attributes = $__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a; ?>
<?php unset($__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a)): ?>
<?php $component = $__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a; ?>
<?php unset($__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a); ?>
<?php endif; ?>
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Developed Inspect Elite Card</p>
                        <?php if (isset($component)) { $__componentOriginalc303075913fdfc9083b4fd555f5f0a95 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc303075913fdfc9083b4fd555f5f0a95 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-mission-card','data' => ['task' => $mockTask,'isToday' => true,'status' => 'Active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-mission-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['task' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mockTask),'isToday' => true,'status' => 'Active']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc303075913fdfc9083b4fd555f5f0a95)): ?>
<?php $attributes = $__attributesOriginalc303075913fdfc9083b4fd555f5f0a95; ?>
<?php unset($__attributesOriginalc303075913fdfc9083b4fd555f5f0a95); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc303075913fdfc9083b4fd555f5f0a95)): ?>
<?php $component = $__componentOriginalc303075913fdfc9083b4fd555f5f0a95; ?>
<?php unset($__componentOriginalc303075913fdfc9083b4fd555f5f0a95); ?>
<?php endif; ?>
                    </div>
                </div>

                <!-- Case B: Empty State -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: Null / Empty</p>
                    <?php if (isset($component)) { $__componentOriginalf5a744a70ee982199708c37a824ad023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5a744a70ee982199708c37a824ad023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-view','data' => ['count' => 0,'emptyTitle' => 'No Records Found','emptySubtitle' => 'The components gallery has no dynamic items to list at this moment.','emptyIcon' => 'box-select']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => 0,'emptyTitle' => 'No Records Found','emptySubtitle' => 'The components gallery has no dynamic items to list at this moment.','emptyIcon' => 'box-select']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $attributes = $__attributesOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__attributesOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $component = $__componentOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__componentOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
                </div>
             </div>
        </div>

     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $attributes = $__attributesOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $component = $__componentOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__componentOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>

    <!-- Modal Showcase -->
    <?php if (isset($component)) { $__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-modal','data' => ['xShow' => 'opened','title' => 'Elite Modal Interface','icon' => 'layout','size' => 'max-w-2xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'opened','title' => 'Elite Modal Interface','icon' => 'layout','size' => 'max-w-2xl']); ?>
        <div class="col-span-12 grid grid-cols-2 gap-6">
            <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Test Label One','placeholder' => 'Entering data...','icon' => 'hash']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Test Label One','placeholder' => 'Entering data...','icon' => 'hash']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Test Label Two','placeholder' => 'Optional value...','icon' => 'edit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Test Label Two','placeholder' => 'Optional value...','icon' => 'edit']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
        </div>
        <div class="col-span-12 mt-4">
            <?php if (isset($component)) { $__componentOriginalae12780a0a64e9f87e72a8fbc7a8343f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalae12780a0a64e9f87e72a8fbc7a8343f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-select','data' => ['label' => 'Priority Ranking','icon' => 'flag']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Priority Ranking','icon' => 'flag']); ?>
                <option>Low Importance</option>
                <option>Medium Tier</option>
                <option>Critical / Strategic</option>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalae12780a0a64e9f87e72a8fbc7a8343f)): ?>
<?php $attributes = $__attributesOriginalae12780a0a64e9f87e72a8fbc7a8343f; ?>
<?php unset($__attributesOriginalae12780a0a64e9f87e72a8fbc7a8343f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalae12780a0a64e9f87e72a8fbc7a8343f)): ?>
<?php $component = $__componentOriginalae12780a0a64e9f87e72a8fbc7a8343f; ?>
<?php unset($__componentOriginalae12780a0a64e9f87e72a8fbc7a8343f); ?>
<?php endif; ?>
        </div>
        <div class="col-span-12 mt-4">
             <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Additional Notes','placeholder' => 'Write something professional...']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Additional Notes','placeholder' => 'Write something professional...']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
        </div>
        
         <?php $__env->slot('footer', null, []); ?> 
             <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'secondary','@click' => 'opened = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','@click' => 'opened = false']); ?>Cancel Mission <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
             <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'check','@click' => 'opened = false']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check','@click' => 'opened = false']); ?>Execute Changes <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27)): ?>
<?php $attributes = $__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27; ?>
<?php unset($__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27)): ?>
<?php $component = $__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27; ?>
<?php unset($__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27); ?>
<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/test_components.blade.php ENDPATH**/ ?>