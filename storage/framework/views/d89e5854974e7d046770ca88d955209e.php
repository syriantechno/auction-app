<?php $__env->startSection('title', 'Leaves'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'log-out','title' => 'Leave','highlight' => 'Management','subtitle' => 'Manage employee leave requests and approvals','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'log-out','title' => 'Leave','highlight' => 'Management','subtitle' => 'Manage employee leave requests and approvals','dot' => 'orange']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('admin.hr.leaves.create')); ?>" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                Request Leave
            </a>
         <?php $__env->endSlot(); ?>

    <!-- Leaves Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            <?php echo e(strtoupper(substr($leave->employee->first_name ?? 'E', 0, 1))); ?>

                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($leave->employee->full_name ?? 'Unknown'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e($leave->leave_type_label); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e($leave->start_date->format('M d')); ?> - <?php echo e($leave->end_date->format('M d, Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e($leave->days_count); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900" title="<?php echo e($leave->reason_details); ?>">
                                    <?php echo e(Str::limit($leave->reason_details, 30)); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($leave->status_badge['class']); ?>">
                                    <?php echo e($leave->status_badge['label']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('admin.hr.leaves.show', $leave)); ?>" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                <?php if($leave->status === 'pending'): ?>
                                    <form action="<?php echo e(route('admin.hr.leaves.approve', $leave)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                    </form>
                                    <form action="<?php echo e(route('admin.hr.leaves.reject', $leave)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => 'No Leave Requests','subtitle' => 'The leave registry is currently empty. Staff records will appear here once submitted.','icon' => 'palm-tree']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No Leave Requests','subtitle' => 'The leave registry is currently empty. Staff records will appear here once submitted.','icon' => 'palm-tree']); ?>
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
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($leaves->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100">
                <?php echo e($leaves->links()); ?>

            </div>
        <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/leaves/index.blade.php ENDPATH**/ ?>