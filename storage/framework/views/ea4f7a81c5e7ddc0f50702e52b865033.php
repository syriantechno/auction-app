<?php $__env->startSection('title', 'Payrolls'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'banknote','title' => 'Payroll','highlight' => 'Management','subtitle' => 'Generate and manage employee salaries','dot' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'banknote','title' => 'Payroll','highlight' => 'Management','subtitle' => 'Generate and manage employee salaries','dot' => 'emerald']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <div class="flex items-center gap-4">
                <form action="<?php echo e(route('admin.hr.payrolls.generate-bulk')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="h-14 px-6 border-2 border-slate-100 rounded-2xl text-[0.7rem] font-black uppercase text-slate-600 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm italic flex items-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        Generate Bulk
                    </button>
                </form>
                <a href="<?php echo e(route('admin.hr.payrolls.create')); ?>" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                    <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                    Generate Payroll
                </a>
            </div>
         <?php $__env->endSlot(); ?>

    <!-- Month/Year Filter -->
    <div class="bg-white rounded-xl border border-slate-100 p-4">
        <form method="GET" class="flex items-center gap-4">
            <select name="month" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($month == $i ? 'selected' : ''); ?>>
                        <?php echo e(date('F', mktime(0, 0, 0, $i, 1))); ?>

                    </option>
                <?php endfor; ?>
            </select>
            
            <select name="year" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                <?php for($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($year == $i ? 'selected' : ''); ?>>
                        <?php echo e($i); ?>

                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-slate-900"><?php echo e($summary['total_payrolls']); ?></p>
                <p class="text-sm text-slate-500">Total Payrolls</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600"><?php echo e(number_format($summary['total_net_salary'], 2)); ?></p>
                <p class="text-sm text-slate-500">Total Net Salary</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600"><?php echo e($summary['approved_count']); ?></p>
                <p class="text-sm text-slate-500">Approved</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-600"><?php echo e($summary['paid_count']); ?></p>
                <p class="text-sm text-slate-500">Paid</p>
            </div>
        </div>
    </div>

    <!-- Payrolls Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Basic Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Net Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            <?php echo e(strtoupper(substr($payroll->employee->first_name ?? 'E', 0, 1))); ?>

                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($payroll->employee->full_name ?? 'Unknown'); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e($payroll->month_name); ?> <?php echo e($payroll->year); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e(number_format($payroll->basic_salary, 2)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900"><?php echo e(number_format($payroll->deductions, 2)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900"><?php echo e(number_format($payroll->net_salary, 2)); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($payroll->status_badge['class']); ?>">
                                    <?php echo e($payroll->status_badge['label']); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('admin.hr.payrolls.show', $payroll)); ?>" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                <?php if($payroll->status === 'draft' || $payroll->status === 'calculated'): ?>
                                    <a href="<?php echo e(route('admin.hr.payrolls.edit', $payroll)); ?>" class="text-slate-600 hover:text-slate-900 mr-2">Edit</a>
                                <?php endif; ?>
                                <?php if($payroll->status === 'calculated'): ?>
                                    <form action="<?php echo e(route('admin.hr.payrolls.approve', $payroll)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                    </form>
                                <?php endif; ?>
                                <?php if($payroll->status === 'approved'): ?>
                                    <form action="<?php echo e(route('admin.hr.payrolls.pay', $payroll)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Pay</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7">
                                <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => 'No Payroll Records','subtitle' => 'The salary registry is currently empty. Staff records will appear here once generated.','icon' => 'banknote']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'No Payroll Records','subtitle' => 'The salary registry is currently empty. Staff records will appear here once generated.','icon' => 'banknote']); ?>
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
        <?php if($payrolls->hasPages()): ?>
            <div class="px-6 py-4 border-t border-slate-100">
                <?php echo e($payrolls->links()); ?>

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

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/payrolls/index.blade.php ENDPATH**/ ?>