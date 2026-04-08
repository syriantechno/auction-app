<?php $__env->startSection('title', 'Valuation Reports'); ?>
<?php $__env->startSection('page_title', 'Valuation Reports'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'clipboard-check','title' => 'Inspection','highlight' => 'Reports','subtitle' => 'All vehicle inspection records','dot' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'clipboard-check','title' => 'Inspection','highlight' => 'Reports','subtitle' => 'All vehicle inspection records','dot' => 'emerald']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('admin.inspections.create')); ?>" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-emerald-500/10">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-emerald-400 group-hover:text-white"></i>
                <span>New Inspection</span>
            </a>
         <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-md mb-6 font-bold border border-emerald-100 flex items-center gap-2 text-xs">
            <i data-lucide="check-circle" class="w-4"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-sm border border-[#f1f5f9] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#f1f5f9] bg-[#f8fafc]">
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Vehicle</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest text-center">Score</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Inspector</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Date</th>
                    <th class="text-right text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $car = $report->car;
                    $rawMake = strtolower($car->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if(file_exists(public_path($p))) { $finalLogo = $p; break; } }
                ?>
                <tr id="report-row-<?php echo e($report->id); ?>" class="hover:bg-[#fbfcfe] transition-all">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center p-2.5 shrink-0 shadow-sm group transition-all hover:bg-white">
                                <?php if($finalLogo): ?>
                                    <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain filter drop-shadow-sm opacity-60 group-hover:opacity-100 transition-opacity">
                                <?php else: ?>
                                    <i data-lucide="car-front" class="w-6 h-6 text-slate-200"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="font-black text-[0.85rem] text-[#111827] uppercase italic leading-none tracking-tight"><?php echo e(optional($report->car)->make); ?> <?php echo e(optional($report->car)->model); ?></div>
                                <div class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest mt-2 flex items-center gap-2">
                                    <span class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 text-slate-400 italic">Production Year: <?php echo e(optional($report->car)->year); ?></span>
                                    <span class="text-emerald-500 italic">ID: #RP-<?php echo e($report->id); ?></span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <?php
                            $scoreColor = $report->overall_score >= 80 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : ($report->overall_score >= 50 ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-red-500 bg-red-50 border-red-100');
                        ?>
                        <div class="inline-flex flex-col items-center justify-center p-2 rounded-xl border <?php echo e($scoreColor); ?> shadow-sm">
                            <span class="text-xl font-black leading-none tabular-nums"><?php echo e($report->overall_score); ?></span>
                            <span class="text-[0.4rem] font-black uppercase tracking-widest opacity-80 mt-1 leading-none">SCORE</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-400 font-black text-[0.55rem] shadow-sm uppercase tracking-widest">EXP</div>
                            <div class="font-black text-[0.75rem] text-[#111827] italic tracking-tight"><?php echo e(optional($report->expert)->name ?? 'Auditor X'); ?></div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-[0.75rem] text-slate-400 font-bold tabular-nums italic">
                        <?php echo e($report->created_at->format('d-m-Y')); ?>

                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.inspections.show', $report)); ?>" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-[#111827] hover:text-white transition-all shadow-sm">
                                <i data-lucide="book-open" class="w-3.5"></i>
                            </a>
                             <form id="delete-report-<?php echo e($report->id); ?>" action="<?php echo e(route('admin.inspections.destroy', $report)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="button" 
                                    onclick="archiveReport(<?php echo e($report->id); ?>, '<?php echo e(route('admin.inspections.destroy', $report)); ?>')"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="trash-2" class="w-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="py-20 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.7rem]">Technical ledger is empty. No audits finalized.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($reports->hasPages()): ?>
        <div class="mt-6 px-1">
            <?php echo e($reports->links()); ?>

        </div>
    <?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function archiveReport(reportId, url) {
            Swal.fire({
                title: 'Archive Audit?',
                text: 'This record will be moved to the archive vault.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4605',
                cancelButtonColor: '#1e293b',
                confirmButtonText: 'Yes, Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: new URLSearchParams({
                            '_method': 'DELETE'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const row = document.getElementById('report-row-' + reportId);
                            row.classList.add('opacity-0', '-translate-x-4', 'transition-all', 'duration-500');
                            setTimeout(() => row.remove(), 500);
                            Toastify({ text: data.message, style: { background: "#1e293b", color: "#fff", borderRadius: "1rem" } }).showToast();
                        } else {
                            Swal.fire('Error!', data.message || 'Processing failed.', 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error!', 'System communication failure.', 'error');
                    });
                }
            });
        }
    </script>
    <?php $__env->stopPush(); ?>

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

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/inspections/index.blade.php ENDPATH**/ ?>