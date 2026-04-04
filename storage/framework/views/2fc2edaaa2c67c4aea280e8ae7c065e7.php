<?php $__env->startSection('title'); ?><?php echo e($siteName ?? 'Motor Bazar'); ?> — Blog@endsection
<?php $__env->startSection('meta_description', 'Latest news, car tips, and auction insights from Motor Bazar.'); ?>

<?php $__env->startSection('content'); ?>


<section class="relative pt-40 pb-20 overflow-hidden hero-gradient section-border-b">
    <?php
        $indexHero = \App\Models\SystemSetting::get('blog_index_hero_image');
        $opacity   = \App\Models\SystemSetting::get('blog_post_hero_opacity', 60);
    ?>
    <?php if($indexHero): ?>
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e(asset('storage/' . $indexHero)); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-[#e7e7e7]" style="opacity: <?php echo e($opacity / 100); ?>"></div>
    </div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <span class="text-[0.6rem] font-black text-[#ff6900] uppercase tracking-[0.3em] mb-4 block [text-shadow:0_1px_2px_rgba(0,0,0,0.1)]">Motor Bazar</span>
            <h1 class="text-5xl lg:text-7xl font-black text-[#031629] uppercase italic tracking-tighter leading-none mb-6 [text-shadow:0_4px_12px_rgba(0,0,0,0.1)]">
                The <span class="text-[#ff6900]">Blog</span>
            </h1>
            <p class="text-slate-500 text-sm font-medium max-w-xl mx-auto leading-relaxed">
                Insights, tips, and news about cars, auctions, and the automotive world.
            </p>

            
            <?php if($categories->count()): ?>
            <div class="flex items-center justify-center flex-wrap gap-2 mt-10">
                <a href="<?php echo e(route('blog.index')); ?>"
                   class="px-6 py-2.5 rounded-full text-[0.65rem] font-black uppercase tracking-widest transition-all shadow-sm
                          <?php echo e(!request('cat') ? 'bg-[#031629] text-white' : 'bg-white text-slate-500 hover:border-[#ff6900] hover:text-[#ff6900] border border-slate-100'); ?>">
                    All Articles
                </a>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('blog.index', ['cat' => $cat->slug])); ?>"
                   class="px-6 py-2.5 rounded-full text-[0.65rem] font-black uppercase tracking-widest transition-all shadow-sm
                          <?php echo e(request('cat') == $cat->slug ? 'bg-[#ff6900] text-white' : 'bg-white text-slate-500 hover:border-[#ff6900] hover:text-[#ff6900] border border-slate-100'); ?>">
                    <?php echo e($cat->name); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    
    <?php if($posts->count()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

            
            <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="block aspect-[16/9] overflow-hidden bg-[#f0f2f5]">
                <?php if($post->featured_image): ?>
                <img src="<?php echo e($post->featured_image); ?>" alt="<?php echo e($post->title); ?>"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                </div>
                <?php endif; ?>
            </a>

            
            <div class="p-6">
                
                <?php if($post->category): ?>
                <span class="text-[0.52rem] font-black text-[#ff6900] uppercase tracking-widest bg-[#ff6900]/10 px-2.5 py-1 rounded-full">
                    <?php echo e($post->category->name); ?>

                </span>
                <?php endif; ?>

                
                <h2 class="mt-3 text-[0.95rem] font-black text-[#1d293d] leading-tight group-hover:text-[#ff6900] transition-colors line-clamp-2">
                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                </h2>

                
                <?php if($post->meta_description): ?>
                <p class="mt-2 text-[0.72rem] text-slate-500 leading-relaxed line-clamp-2">
                    <?php echo e($post->meta_description); ?>

                </p>
                <?php endif; ?>

                
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[0.58rem] font-bold text-slate-400 tabular-nums">
                        <?php echo e(($post->published_at ?? $post->created_at)?->format('d M Y')); ?>

                    </span>
                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>"
                       class="text-[0.58rem] font-black text-[#ff6900] uppercase tracking-widest hover:underline flex items-center gap-1">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>
        </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($posts->hasPages()): ?>
    <div class="mt-12 flex justify-center">
        <?php echo e($posts->links()); ?>

    </div>
    <?php endif; ?>

    <?php else: ?>
    <div class="text-center py-24">
        <div class="w-16 h-16 rounded-2xl bg-[#f0f2f5] border border-slate-200 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No articles yet</p>
        <p class="text-slate-300 text-sm mt-1">Check back soon for new content.</p>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
<style>
    .hero-gradient {
        background: radial-gradient(circle at 10% 20%, rgba(255, 105, 0, 0.05) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(3, 22, 41, 0.03) 0%, transparent 40%),
                    linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    }
    .section-border-b {
        border-bottom: 1px solid #f1f5f9;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/blog/index.blade.php ENDPATH**/ ?>