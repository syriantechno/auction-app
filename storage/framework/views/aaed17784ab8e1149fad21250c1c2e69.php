<?php $__env->startSection('title', 'Navigation Architect'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-20 space-y-5">

    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-violet-500 border-[3px] border-[#f8fafc] animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    Navigation <span class="text-[#ff6900]">Architect</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    Manage header & footer navigation menus
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#ff6900] animate-pulse flex-shrink-0"></span>
                <span class="text-[0.58rem] font-black uppercase text-slate-400 tracking-widest">Active · <?php echo e($menus->count()); ?> Menu Zones</span>
            </div>
            <a href="<?php echo e(route('admin.pages.index')); ?>"
               class="px-5 py-2.5 bg-[#1d293d] text-white rounded-lg font-black text-[0.62rem] uppercase tracking-widest flex items-center gap-2 hover:bg-[#ff6900] transition-all shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Page Builder
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <?php $__empty_1 = true; $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <?php
            $locationIcons = [
                'header' => ['icon' => 'layout-template', 'color' => 'bg-blue-50 text-blue-600 border-blue-200', 'dot' => 'bg-blue-500'],
                'footer' => ['icon' => 'panel-bottom',    'color' => 'bg-violet-50 text-violet-600 border-violet-200', 'dot' => 'bg-violet-500'],
            ];
            $loc = $locationIcons[$menu->location] ?? ['icon' => 'menu', 'color' => 'bg-slate-50 text-slate-500 border-slate-200', 'dot' => 'bg-slate-400'];
        ?>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:border-slate-300 transition-all duration-200 group">

            
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    
                    <div class="w-8 h-8 rounded-lg <?php echo e($loc['color']); ?> border flex items-center justify-center flex-shrink-0">
                        <i data-lucide="<?php echo e($loc['icon']); ?>" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[0.7rem] font-black text-[#1d293d] uppercase tracking-wider group-hover:text-[#ff6900] transition-colors"><?php echo e($menu->name); ?></div>
                        <div class="text-[0.52rem] text-slate-400 font-bold uppercase tracking-widest"><?php echo e(strtoupper($menu->location ?? 'Custom')); ?> Zone</div>
                    </div>
                </div>

                
                <span class="px-2.5 py-1 <?php echo e($menu->items_count > 0 ? 'bg-[#ff6900]/10 text-[#ff6900]' : 'bg-slate-100 text-slate-400'); ?> rounded-md text-[0.52rem] font-black uppercase tracking-widest">
                    <?php echo e($menu->items_count); ?> Items
                </span>
            </div>

            
            <div class="p-5 bg-[#f0f2f5]">

                <?php if($menu->items_count > 0): ?>
                
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <?php $__currentLoopData = $menu->items->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center gap-1.5 text-[0.6rem] font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full <?php echo e($item->page_id ? 'bg-[#ff6900]' : 'bg-slate-300'); ?> flex-shrink-0"></span>
                        <?php echo e($item->label); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($menu->items_count > 8): ?>
                    <span class="inline-flex items-center text-[0.58rem] text-slate-400 font-black self-center px-1">
                        +<?php echo e($menu->items_count - 8); ?> more
                    </span>
                    <?php endif; ?>
                </div>

                
                <div class="border-t border-slate-200 mb-4"></div>

                
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-[#1d293d]"><?php echo e($menu->items_count); ?></div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Total</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-[#ff6900]"><?php echo e($menu->items->where('page_id', '!=', null)->count()); ?></div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Pages</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-slate-400"><?php echo e($menu->items->where('page_id', null)->count()); ?></div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Links</div>
                    </div>
                </div>

                <?php else: ?>
                
                <div class="flex flex-col items-center justify-center py-8 text-center mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                    </div>
                    <p class="text-[0.6rem] text-slate-300 font-black uppercase tracking-widest">No items yet</p>
                    <p class="text-[0.55rem] text-slate-400 mt-0.5">Click Edit to add navigation links</p>
                </div>
                <?php endif; ?>

                <a href="<?php echo e(route('admin.menus.show', $menu)); ?>"
                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-[#1d293d] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Menu
                </a>
            </div>
        </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="md:col-span-2 py-24 text-center bg-white rounded-xl border border-dashed border-slate-200">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-[#f0f2f5] flex items-center justify-center border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                </div>
                <div>
                    <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No Menu Zones Detected</p>
                    <p class="text-[0.58rem] text-slate-400 mt-1">Navigation menus will appear here once created</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="flex items-center gap-5 text-[0.58rem] text-slate-400 font-bold px-1">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-[#ff6900] inline-block"></span>
            Linked to a dynamic page
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
            Manual URL
        </div>
        <div class="flex items-center gap-1.5 ml-auto">
            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
            Header Zone
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-violet-500 inline-block"></span>
            Footer Zone
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/menus/index.blade.php ENDPATH**/ ?>