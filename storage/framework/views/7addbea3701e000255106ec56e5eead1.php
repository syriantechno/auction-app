<?php $__env->startSection('title', 'Editorial Hub'); ?>

<?php $__env->startSection('content'); ?>
<div class="pb-20 space-y-5">

    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-rose-500 border-[3px] border-[#f8fafc] animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    Editorial <span class="text-[#ff6900]">Hub</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    Blog posts & content management
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.categories.index')); ?>"
               class="px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-[#1d293d] flex items-center gap-2 text-[0.6rem] font-black uppercase tracking-widest transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Categories
            </a>
            <a href="<?php echo e(route('admin.posts.create')); ?>"
               class="px-5 py-2.5 bg-[#1d293d] text-white rounded-lg font-black text-[0.62rem] uppercase tracking-widest flex items-center gap-2 hover:bg-[#ff6900] transition-all shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Article
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-3 gap-4">
        <?php $__currentLoopData = [
            ['All Articles', $totalCount, 'bg-[#1d293d]', '#ff6900'],
            ['Published', $liveCount, 'bg-emerald-500', '#fff'],
            ['Drafts', $draftCount, 'bg-amber-400', '#fff'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $bg, $tc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 rounded-lg <?php echo e($bg); ?> flex items-center justify-center">
                <span class="text-lg font-black tabular-nums" style="color: <?php echo e($tc); ?>"><?php echo e($count); ?></span>
            </div>
            <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest"><?php echo e($label); ?></div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if(session('success')): ?>
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
            <span class="text-[0.6rem] font-black text-[#1d293d] uppercase tracking-widest">All Articles</span>
            <span class="text-[0.52rem] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($posts->total()); ?> total</span>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-[#f0f2f5]">
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-5 font-black tracking-widest w-16">Cover</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Title</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Category</th>
                    <th class="text-center text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Status</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Date</th>
                    <th class="text-right text-[0.58rem] text-slate-400 uppercase py-3 px-5 font-black tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="group hover:bg-slate-50/50 transition-all border-l-4 border-l-transparent hover:border-l-[#ff6900]">

                    
                    <td class="py-4 px-5">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-[#f0f2f5] border border-slate-200 flex items-center justify-center flex-shrink-0">
                            <?php if($post->featured_image): ?>
                                <img src="<?php echo e($post->featured_image); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <?php endif; ?>
                        </div>
                    </td>

                    
                    <td class="py-4 px-4">
                        <div class="font-black text-[0.82rem] text-[#1d293d] group-hover:text-[#ff6900] transition-colors line-clamp-1"><?php echo e($post->title); ?></div>
                        <div class="text-[0.58rem] text-slate-400 font-mono mt-0.5">/blog/<?php echo e($post->slug); ?></div>
                    </td>

                    
                    <td class="py-4 px-4">
                        <?php if($post->category): ?>
                        <span class="px-2.5 py-1 bg-[#ff6900]/10 text-[#ff6900] rounded-md text-[0.52rem] font-black uppercase tracking-widest">
                            <?php echo e($post->category->name); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-slate-300 text-[0.6rem] font-black">—</span>
                        <?php endif; ?>
                    </td>

                    
                    <td class="py-4 px-4 text-center">
                        <?php if($post->is_published): ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[0.52rem] font-black uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-400 text-[0.52rem] font-black uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                        </span>
                        <?php endif; ?>
                    </td>

                    
                    <td class="py-4 px-4">
                        <span class="text-[0.68rem] font-bold text-slate-500 tabular-nums">
                            <?php echo e(($post->published_at ?? $post->updated_at)?->format('d M Y')); ?>

                        </span>
                    </td>

                    
                    <td class="py-4 px-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <?php if($post->is_published): ?>
                            <a href="<?php echo e(route('blog.show', $post->slug)); ?>" target="_blank"
                               class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('admin.posts.edit', $post)); ?>"
                               class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="<?php echo e(route('admin.posts.destroy', $post)); ?>" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this article?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="w-8 h-8 rounded-lg bg-red-50 border border-red-100 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-24 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-[#f0f2f5] border border-slate-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </div>
                            <p class="text-[0.62rem] font-black text-slate-300 uppercase tracking-widest">No articles yet</p>
                            <a href="<?php echo e(route('admin.posts.create')); ?>" class="text-[0.6rem] text-[#ff6900] font-black uppercase tracking-widest hover:underline">
                                + Write First Article
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if($posts->hasPages()): ?>
        <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex justify-center">
            <?php echo e($posts->links()); ?>

        </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/posts/index.blade.php ENDPATH**/ ?>