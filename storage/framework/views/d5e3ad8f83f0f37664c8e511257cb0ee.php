<?php $__env->startSection('title', 'Page Builder'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $publishedCount = $pages->where('is_published', true)->count();
    $draftCount     = $pages->where('is_published', false)->count();
?>

    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'file-text','title' => 'Content','highlight' => 'Pages','subtitle' => 'Dynamic Content Management','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'file-text','title' => 'Content','highlight' => 'Pages','subtitle' => 'Dynamic Content Management','dot' => 'orange']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('admin.pages.create')); ?>" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-orange-500/10">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-orange-400 group-hover:text-white"></i>
                <span>New Page</span>
            </a>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('stats', null, []); ?> 
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if (isset($component)) { $__componentOriginal6ccb7413961bd805d5db6baba7b26a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6ccb7413961bd805d5db6baba7b26a7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'All Pages','value' => $pages->count(),'icon' => 'file-text','color' => 'slate']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'All Pages','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pages->count()),'icon' => 'file-text','color' => 'slate']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Published','value' => $publishedCount,'icon' => 'check-circle','color' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Published','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($publishedCount),'icon' => 'check-circle','color' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-stat-card','data' => ['label' => 'Drafts','value' => $draftCount,'icon' => 'clock','color' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Drafts','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($draftCount),'icon' => 'clock','color' => 'orange']); ?>
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
         <?php $__env->endSlot(); ?>

    <?php if(session('success')): ?>
        <div class="bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.75rem] font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Page Title</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">URL Slug</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-center">In Menu</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-center">Status</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Created</th>
                    <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-right">Operations</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $menuItem = \App\Models\MenuItem::where('page_id', $pg->id)->with('menu')->first();
                ?>
                <tr class="group hover:bg-slate-50/50 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-orange-500">
                    <td class="py-3 px-8">
                        <div class="text-[0.9rem] font-normal text-slate-700 group-hover:text-orange-600 transition-colors leading-tight"><?php echo e($pg->title); ?></div>
                        <?php if($pg->meta_description): ?>
                            <div class="text-[0.6rem] text-slate-400 mt-0.5 truncate max-w-xs"><?php echo e(Str::limit($pg->meta_description, 60)); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-6">
                        <code class="bg-slate-50 border border-slate-200 text-slate-500 px-2 py-0.5 rounded text-[0.65rem] font-mono">/<?php echo e($pg->slug); ?></code>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php if($menuItem): ?>
                            <span class="inline-flex items-center gap-1 text-[0.6rem] font-medium text-[#ff6900] bg-orange-50 border border-orange-100 px-2.5 py-1 rounded-full">
                                <i data-lucide="link-2" class="w-2.5 h-2.5"></i>
                                <?php echo e($menuItem->menu->name ?? 'Menu'); ?>

                            </span>
                        <?php else: ?>
                            <span class="text-[0.65rem] text-slate-300 font-medium">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <?php if($pg->is_published): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[0.6rem] font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[0.6rem] font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-[0.7rem] font-normal text-slate-500"><?php echo e($pg->created_at->format('M d, Y')); ?></span>
                    </td>
                    <td class="py-3 px-8 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('page.show', $pg->slug)); ?>" target="_blank"
                               class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                            <a href="<?php echo e(route('admin.pages.edit', $pg)); ?>"
                               class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-slate-800 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="<?php echo e(route('admin.pages.destroy', $pg)); ?>" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this page?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="w-9 h-9 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-md border border-red-50 active:scale-95">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-20 text-center bg-slate-50">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                                <i data-lucide="file-x-2" class="w-8 h-8 text-slate-200"></i>
                            </div>
                            <h3 class="text-xs font-medium text-slate-400 uppercase tracking-widest">No pages created yet</h3>
                            <a href="<?php echo e(route('admin.pages.create')); ?>"
                               class="text-[0.7rem] font-medium text-[#ff6900] hover:underline uppercase tracking-widest">
                               + Create First Page
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\auction_app\resources\views/admin/pages/index.blade.php ENDPATH**/ ?>