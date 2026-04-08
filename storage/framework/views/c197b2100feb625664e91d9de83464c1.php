<?php $__env->startSection('title', 'HR Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'users','title' => 'HR','highlight' => 'Dashboard','subtitle' => 'Manage your human resources efficiently','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'HR','highlight' => 'Dashboard','subtitle' => 'Manage your human resources efficiently','dot' => 'orange']); ?>
    
     <?php $__env->slot('actions', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'plus','href' => ''.e(route('admin.hr.employees.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'plus','href' => ''.e(route('admin.hr.employees.create')).'']); ?>Add Employee <?php echo $__env->renderComponent(); ?>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Total','value' => ''.e($stats['total_employees']).'','icon' => 'users','color' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Total','value' => ''.e($stats['total_employees']).'','icon' => 'users','color' => 'slate']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Present','value' => ''.e($stats['present_today']).'','icon' => 'user-check','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Present','value' => ''.e($stats['present_today']).'','icon' => 'user-check','color' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Absent','value' => ''.e($stats['absent_today'] ?? 0).'','icon' => 'user-x','color' => 'rose']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Absent','value' => ''.e($stats['absent_today'] ?? 0).'','icon' => 'user-x','color' => 'rose']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Late','value' => ''.e($stats['late_today'] ?? 0).'','icon' => 'clock','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Late','value' => ''.e($stats['late_today'] ?? 0).'','icon' => 'clock','color' => 'orange']); ?>
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

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Recent Employees -->
        <div class="bg-white rounded-xl border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900">Recent Employees</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $recentEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-slate-600">
                                        <?php echo e(strtoupper(substr($employee->first_name ?? 'E', 0, 1))); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900"><?php echo e($employee->full_name ?? 'Unknown'); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo e($employee->department->name ?? 'No Department'); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500"><?php echo e($employee->created_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-slate-500 text-center py-4">No employees yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900">Today's Attendance</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $todayAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center <?php echo e($attendance->status == 'present' ? 'bg-green-100' : 'bg-red-100'); ?>">
                                    <span class="text-sm font-medium <?php echo e($attendance->status == 'present' ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e(strtoupper(substr($attendance->employee->first_name ?? 'A', 0, 1))); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900"><?php echo e($attendance->employee->full_name ?? 'Unknown'); ?></p>
                                    <p class="text-sm text-slate-500"><?php echo e($attendance->status); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <?php if($attendance->check_in): ?>
                                    <p class="text-sm text-slate-500"><?php echo e($attendance->check_in); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-slate-500 text-center py-4">No attendance records for today</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-slate-100 p-6 mt-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('admin.hr.attendance.create')); ?>" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Mark Attendance</span>
            </a>
            <a href="<?php echo e(route('admin.hr.leaves.create')); ?>" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calendar-minus" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Request Leave</span>
            </a>
            <a href="<?php echo e(route('admin.hr.payrolls.create')); ?>" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calculator" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Generate Payroll</span>
            </a>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/dashboard.blade.php ENDPATH**/ ?>