<div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em] w-16 text-center">Node</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em]">Customer Identity</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em]">Asset Specs</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em]">Technical Stats</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em]">Appointment Hub</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em] text-center">Status</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em] text-center">Source</th>
                <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-semibold uppercase tracking-[0.2em] text-right">Ops Control</th>
            </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $details = $lead->car_details ?? [];
                $name = $details['name'] ?? ($lead->user ? $lead->user->name : 'Operator');
                $email = $details['email'] ?? ($lead->user ? $lead->user->email : 'N/A');
                $mileage = $details['mileage'] ?? 'N/A';
                if(is_numeric($mileage)) $mileage = number_format($mileage) . ' KM';
                
                $condition = $details['condition'] ?? 'N/A';
                
                // Numeric Date Formatting (Fix #8)
                $rawDate = $details['inspection_date'] ?? null;
                $appDate = $rawDate ? \Carbon\Carbon::parse($rawDate)->format('d-m-Y') : 'TBD';
                
                $appTime = $details['inspection_time'] ?? '';
                $isHome = ($details['inspection_type'] ?? 'branch') === 'home';
                $address = $details['home_address'] ?? 'Hub Branch';

                // Brand Logo Intelligence (Unified Matrix Strategy)
                $rawMake = trim($details['make'] ?? 'generic');
                $cleanMake = strtolower($rawMake);
                $makeSlug = \Illuminate\Support\Str::slug($cleanMake);
                
                // 1. Try Local Assets First
                $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                if (str_contains($cleanMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                
                $finalLogo = null;
                foreach ($searchPaths as $p) {
                    if (file_exists(public_path($p))) { $finalLogo = asset($p); break; }
                }

                // 2. Fallback to Cloud CDN (Enterprise Grade)
                if (!$finalLogo && $rawMake !== 'generic') {
                    $cdnSlug = str_replace(' ', '-', $makeSlug);
                    $finalLogo = "https://cdn.jsdelivr.net/gh/fawazahmed0/car-logos@master/logos/{$cdnSlug}.svg";
                }
            ?>
            <tr class="group hover:bg-slate-50/50 transition-all duration-300 border-l-4 border-l-transparent hover:border-l-[#FF6900]">
                <td class="py-5 px-8 text-center text-slate-300 text-[0.65rem] font-mono group-hover:text-slate-900 transition-colors">#<?php echo e($lead->id); ?></td>
                <td class="py-5 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-md bg-[#1d293d] flex items-center justify-center text-white shadow-lg">
                            <span class="text-[0.7rem] font-semibold"><?php echo e(mb_substr($name, 0, 1)); ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[0.85rem] font-semibold text-slate-900 tracking-tight"><?php echo e($name); ?></span>
                            <span class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-wider"><?php echo e($details['phone'] ?? 'NO PHONE'); ?></span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex items-center gap-3">
                        
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-1.5 shrink-0">
                            <?php if($finalLogo): ?>
                                <img src="<?php echo e($finalLogo); ?>" class="w-full h-full object-contain opacity-60">
                            <?php else: ?>
                                <i data-lucide="car-front" class="w-5 h-5 text-slate-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[0.85rem] font-semibold text-slate-900 tracking-tighter uppercase"><?php echo e($details['make'] ?? 'Unknown'); ?></span>
                            <span class="text-[0.65rem] text-slate-500 font-medium"><?php echo e($details['year'] ?? ''); ?> <?php echo e($details['model'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <i data-lucide="gauge" class="w-3 h-3 text-[#FF6900]"></i>
                            <span class="text-[0.75rem] font-mono text-slate-700 tracking-tighter"><?php echo e($mileage); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full <?php echo e($condition == 'excellent' ? 'bg-emerald-500' : 'bg-orange-400'); ?>"></div>
                            <span class="text-[0.6rem] font-semibold uppercase tracking-widest text-slate-400"><?php echo e($condition); ?></span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span class="text-[0.8rem] font-semibold text-slate-900 leading-none"><?php echo e($appDate); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if($isHome): ?>
                                <span class="bg-orange-50 text-[#FF6900] px-2 py-0.5 rounded-md text-[0.55rem] font-semibold uppercase tracking-widest border border-orange-100 flex items-center gap-1">
                                    <i data-lucide="home" class="w-2.5 h-2.5"></i> Home
                                </span>
                            <?php else: ?>
                                <span class="bg-slate-50 text-slate-500 px-2 py-0.5 rounded-md text-[0.55rem] font-semibold uppercase tracking-widest border border-slate-200 flex items-center gap-1">
                                    <i data-lucide="building-2" class="w-2.5 h-2.5"></i> Hub
                                </span>
                            <?php endif; ?>
                            <span class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest"><?php echo e($appTime ?: 'ASAP'); ?></span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6 text-center">
                    <?php
                        $statusColors = [
                            'new' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'pending' => 'bg-orange-50 text-[#ff6900] border-orange-100',
                            'in_review' => 'bg-blue-50 text-blue-500 border-blue-200',
                            'approved' => 'bg-emerald-900 text-white border-emerald-900',
                            'rejected' => 'bg-red-50 text-red-500 border-red-200',
                            'inspection_scheduled' => 'bg-indigo-50 text-indigo-500 border-indigo-200',
                        ];
                        $col = $statusColors[$lead->status] ?? 'bg-slate-50 text-slate-500 border-slate-100';
                    ?>
                    <span class="px-4 py-1.5 rounded-md text-[0.65rem] font-semibold uppercase tracking-widest border shadow-sm <?php echo e($col); ?>">
                        <?php echo e(str_replace('_', ' ', $lead->status)); ?>

                    </span>
                </td>
                <td class="py-5 px-6 text-center">
                    <?php
                        $sourceColor = \App\Models\Lead::getSourceColor($lead->source ?? '');
                        $sourceLabel = \App\Models\Lead::getSourceLabel($lead->source);
                    ?>
                    <span class="px-3 py-1.5 rounded-md text-[0.6rem] font-semibold uppercase tracking-widest text-white shadow-sm" 
                          style="background-color: <?php echo e($sourceColor); ?>;">
                        <?php echo e($sourceLabel); ?>

                    </span>
                </td>
                <td class="py-5 px-8 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <?php if($lead->status === 'new' || $lead->status === 'pending' || $lead->status === 'Active'): ?>
                            <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'calendar-check','click' => 'confirmLead('.e($lead->id).')','title' => 'Confirm & Schedule','variant' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'calendar-check','click' => 'confirmLead('.e($lead->id).')','title' => 'Confirm & Schedule','variant' => 'orange']); ?>
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
                        <?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'eye','click' => 'viewLead('.e($lead->id).')','title' => 'Open Node','variant' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'eye','click' => 'viewLead('.e($lead->id).')','title' => 'Open Node','variant' => 'slate']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'message-circle','href' => 'https://wa.me/'.e(preg_replace('/[^0-9]/', '', $details['phone'] ?? '')).'','title' => 'WhatsApp','variant' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'message-circle','href' => 'https://wa.me/'.e(preg_replace('/[^0-9]/', '', $details['phone'] ?? '')).'','title' => 'WhatsApp','variant' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'trash','click' => 'deleteLead('.e($lead->id).')','title' => 'Delete Record','variant' => 'red']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'trash','click' => 'deleteLead('.e($lead->id).')','title' => 'Delete Record','variant' => 'red']); ?>
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
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="py-32 text-center bg-slate-50">
                    <div class="flex flex-col items-center gap-6">
                        <div class="relative">
                            <div class="w-20 h-20 bg-white rounded-lg flex items-center justify-center border border-slate-200 shadow-xl">
                                <i data-lucide="layers" class="w-10 text-slate-200"></i>
                            </div>
                            <div class="absolute -right-2 -bottom-2 w-8 h-8 bg-[#ff6900] rounded-full flex items-center justify-center text-white text-[0.6rem] font-semibold border-4 border-slate-50">0</div>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-[0.4em]">No Results</h3>
                            <p class="text-[0.6rem] text-slate-300 font-medium">Waiting for new incoming lead nodes...</p>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="paginationContainer">
        <?php if($leads->hasPages()): ?>
        <div class="bg-slate-50 px-10 py-10 border-t border-slate-200 flex items-center justify-center">
            <?php echo e($leads->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH F:\auction_app\resources\views/admin/leads/_table.blade.php ENDPATH**/ ?>