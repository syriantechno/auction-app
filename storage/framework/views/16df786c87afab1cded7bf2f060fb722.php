<?php $__env->startSection('title', 'Attendance Manager'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="attendanceManager()" x-init="initComponent()">
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'clipboard-check','title' => 'Attendance','highlight' => 'Manager','subtitle' => 'Monitor daily attendance logs and working hours','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clipboard-check','title' => 'Attendance','highlight' => 'Manager','subtitle' => 'Monitor daily attendance logs and working hours','dot' => 'orange']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'secondary','icon' => 'users','href' => ''.e(route('admin.hr.attendance.bulk.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','icon' => 'users','href' => ''.e(route('admin.hr.attendance.bulk.create')).'']); ?>Bulk Mark <?php echo $__env->renderComponent(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'plus','href' => ''.e(route('admin.hr.attendance.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'plus','href' => ''.e(route('admin.hr.attendance.create')).'']); ?>Mark Attendance <?php echo $__env->renderComponent(); ?>
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Total','value' => ''.e($stats['total']).'','icon' => 'users','color' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total','value' => ''.e($stats['total']).'','icon' => 'users','color' => 'slate']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Present','value' => ''.e($stats['present']).'','icon' => 'user-check','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Present','value' => ''.e($stats['present']).'','icon' => 'user-check','color' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Absent','value' => ''.e($stats['absent']).'','icon' => 'user-x','color' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Absent','value' => ''.e($stats['absent']).'','icon' => 'user-x','color' => 'rose']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Late','value' => ''.e($stats['late']).'','icon' => 'clock','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Late','value' => ''.e($stats['late']).'','icon' => 'clock','color' => 'orange']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'On Leave','value' => ''.e($stats['on_leave']).'','icon' => 'calendar-x','color' => 'blue']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'On Leave','value' => ''.e($stats['on_leave']).'','icon' => 'calendar-x','color' => 'blue']); ?>
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

        <!-- Filters -->
        <?php if (isset($component)) { $__componentOriginal6b29dae6f7a16af6949451a73d5dcc44 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-filter-bar','data' => ['class' => 'mt-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-6']); ?>
            <div class="w-48">
                <?php if (isset($component)) { $__componentOriginal5ec76c329367b94a81bc06aea48f6863 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ec76c329367b94a81bc06aea48f6863 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-date','data' => ['dateName' => 'filter_date','label' => 'Select Date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateName' => 'filter_date','label' => 'Select Date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $attributes = $__attributesOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $component = $__componentOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__componentOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
            </div>
            <div class="w-48">
                <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block mb-2">Employee</label>
                <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['icon' => 'user','name' => 'employee_id','xModel' => 'employeeFilter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'user','name' => 'employee_id','x-model' => 'employeeFilter']); ?>
                    <option value="">All Employees</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <div class="w-48">
                <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block mb-2">Status</label>
                <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['icon' => 'tag','name' => 'status','xModel' => 'statusFilter']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'tag','name' => 'status','x-model' => 'statusFilter']); ?>
                    <option value="">All Status</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="vacation">Vacation</option>
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
            <div class="w-48 pt-[22px]">
                <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'secondary','icon' => 'rotate-ccw','@click' => 'resetFilters()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','icon' => 'rotate-ccw','@click' => 'resetFilters()']); ?>Reset <?php echo $__env->renderComponent(); ?>
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

        <!-- Attendance Data View -->
        <?php if (isset($component)) { $__componentOriginalf5a744a70ee982199708c37a824ad023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5a744a70ee982199708c37a824ad023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-view','data' => ['count' => ''.e($attendances->count()).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['count' => ''.e($attendances->count()).'']); ?>
            <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginald6ed9e3c29e55aa4262c98eb2df87e42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald6ed9e3c29e55aa4262c98eb2df87e42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-item','data' => ['number' => ''.e($loop->iteration).'','title' => ''.e($attendance->employee->full_name ?? 'Unknown').'','subtitle' => ''.e($attendance->attendance_date->format('M d, Y')).' | '.e($attendance->check_in ?? '&ndash;&ndash;:&ndash;&ndash;').' - '.e($attendance->check_out ?? '&ndash;&ndash;:&ndash;&ndash;').'','highlight' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['number' => ''.e($loop->iteration).'','title' => ''.e($attendance->employee->full_name ?? 'Unknown').'','subtitle' => ''.e($attendance->attendance_date->format('M d, Y')).' | '.e($attendance->check_in ?? '&ndash;&ndash;:&ndash;&ndash;').' - '.e($attendance->check_out ?? '&ndash;&ndash;:&ndash;&ndash;').'','highlight' => true]); ?>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 text-[0.65rem] font-black uppercase tracking-wider rounded-full <?php echo e($attendance->status_badge['class']); ?>">
                            <?php echo e($attendance->status_badge['label']); ?>

                        </span>
                        <span class="text-[0.75rem] font-bold text-slate-500"><?php echo e($attendance->working_hours ?? '0'); ?>h</span>
                        <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'edit-3','title' => 'Edit','variant' => 'slate','href' => ''.e(route('admin.hr.attendance.edit', $attendance)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'edit-3','title' => 'Edit','variant' => 'slate','href' => ''.e(route('admin.hr.attendance.edit', $attendance)).'']); ?>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => 'No Attendance Records','subtitle' => 'No attendance logs found for the selected filters.','icon' => 'clipboard-x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No Attendance Records','subtitle' => 'No attendance logs found for the selected filters.','icon' => 'clipboard-x']); ?>
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
            
            <?php if($attendances->hasPages()): ?>
                <div class="px-6 py-4 border-t border-slate-100">
                    <?php echo e($attendances->links()); ?>

                </div>
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
</div>

<script>
function attendanceManager() {
    return {
        employeeFilter: "<?php echo e(request('employee_id')); ?>",
        statusFilter: "<?php echo e(request('status')); ?>",
        
        initComponent() {
            this.$nextTick(() => { 
                if (typeof lucide !== 'undefined') lucide.createIcons(); 
            });
        },
        
        resetFilters() {
            this.employeeFilter = '';
            this.statusFilter = '';
            window.location.href = '<?php echo e(route('admin.hr.attendance.index')); ?>';
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/attendance/index.blade.php ENDPATH**/ ?>