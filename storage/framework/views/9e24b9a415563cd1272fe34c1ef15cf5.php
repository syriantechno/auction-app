<?php $__env->startSection('title', 'Page Builder'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $publishedCount = $pages->where('is_published', true)->count();
    $draftCount     = $pages->where('is_published', false)->count();
?>

<div class="px-1 space-y-5 animate-in fade-in duration-500">

    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-medium text-slate-800 tracking-tighter italic">Page Builder</h1>
            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
            <p class="text-[0.65rem] text-slate-500 font-medium uppercase tracking-[0.2em] hidden md:block">Dynamic Content Management</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 bg-white px-5 py-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="text-[0.6rem] font-medium uppercase text-slate-400 tracking-widest">Total Pages:</span>
                <span class="text-sm font-medium text-slate-800 tabular-nums"><?php echo e($pages->count()); ?></span>
            </div>
            <a href="<?php echo e(route('admin.pages.create')); ?>"
               style="background: var(--primary-orange);"
               class="px-6 h-[44px] text-white rounded-lg font-medium shadow-lg shadow-orange-500/10 hover:scale-[1.02] active:scale-95 transition-all text-[0.65rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-white/80"></i> New Page
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-md bg-slate-50 border border-slate-100 flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5 text-slate-400"></i>
            </div>
            <div>
                <div class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">All Pages</div>
                <div class="text-xl font-medium text-slate-800 tabular-nums leading-tight"><?php echo e($pages->count()); ?></div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            </div>
            <div>
                <div class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Published</div>
                <div class="text-xl font-medium text-slate-800 tabular-nums leading-tight"><?php echo e($publishedCount); ?></div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-md bg-amber-50 border border-amber-100 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5 text-amber-400"></i>
            </div>
            <div>
                <div class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Drafts</div>
                <div class="text-xl font-medium text-slate-800 tabular-nums leading-tight"><?php echo e($draftCount); ?></div>
            </div>
        </div>
    </div>

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
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/pages/index.blade.php ENDPATH**/ ?>