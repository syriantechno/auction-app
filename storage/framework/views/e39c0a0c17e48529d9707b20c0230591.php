<?php $__env->startSection('title'); ?><?php echo e($post->title); ?> — <?php echo e($siteName ?? 'Motor Bazar'); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('meta_description', $post->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->content['body'] ?? ''), 160)); ?>

<?php $__env->startSection('content'); ?>


<section class="relative pt-40 pb-20 overflow-hidden hero-gradient section-border-b">
    <?php
        $showHero   = \App\Models\SystemSetting::get('blog_show_hero_image');
        $heroMode   = \App\Models\SystemSetting::get('blog_post_hero_mode', 'auto');
        $opacity    = \App\Models\SystemSetting::get('blog_post_hero_opacity', 60);
        
        $heroUrl = ($heroMode === 'auto' && $post->featured_image) 
                   ? $post->featured_image 
                   : ($showHero ? asset('storage/' . $showHero) : null);
    ?>
    <?php if($heroUrl): ?>
    <div class="absolute inset-0 z-0">
        <img src="<?php echo e($heroUrl); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-[#e7e7e7]" style="opacity: <?php echo e($opacity / 100); ?>"></div>
    </div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8">
            <div class="max-w-3xl">
                <nav class="flex items-center gap-2 text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-6">
                    <a href="<?php echo e(route('home')); ?>" class="hover:text-[#ff6900] transition-colors">Home</a>
                    <span class="text-slate-300">›</span>
                    <a href="<?php echo e(route('blog.index')); ?>" class="hover:text-[#ff6900] transition-colors">Blog</a>
                    <?php if($post->category): ?>
                    <span class="text-slate-300">›</span>
                    <a href="<?php echo e(route('blog.index', ['cat' => $post->category->slug])); ?>" class="hover:text-[#ff6900] transition-colors">
                        <?php echo e($post->category->name); ?>

                    </a>
                    <?php endif; ?>
                    <span class="text-slate-300">›</span>
                    <span class="text-slate-600 truncate max-w-[150px] inline-block"><?php echo e($post->title); ?></span>
                </nav>
                
                <?php if($post->category): ?>
                <span class="inline-block text-[0.55rem] font-black text-[#ff6900] uppercase tracking-[0.2em] mb-4 bg-[#ff6900]/10 px-3 py-1.5 rounded-full">
                    <?php echo e($post->category->name); ?>

                </span>
                <?php endif; ?>
                
                <h1 class="text-4xl lg:text-6xl font-black text-[#031629] uppercase italic tracking-tighter leading-[0.9] mb-6 [text-shadow:0_4px_12px_rgba(0,0,0,0.1)]">
                    <?php echo e($post->title); ?>

                </h1>
                
                <div class="flex items-center gap-6 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#031629] flex items-center justify-center text-white font-black text-[0.6rem]">MB</div>
                        <div>
                            <div class="text-[0.65rem] font-black text-[#031629] uppercase tracking-wide">Motor Bazar Editorial</div>
                            <div class="text-[0.55rem] font-bold text-slate-400 uppercase tracking-widest"><?php echo e(($post->published_at ?? $post->created_at)?->format('d M Y')); ?></div>
                        </div>
                    </div>
                    
                    <div class="h-8 w-[1px] bg-slate-200 hidden sm:block"></div>
                    
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <div class="w-7 h-7 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[0.5rem] font-black text-slate-400">J</div>
                            <div class="w-7 h-7 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[0.5rem] font-black text-slate-400">A</div>
                            <div class="w-7 h-7 rounded-full border-2 border-white bg-[#ff6900] flex items-center justify-center text-[0.5rem] font-black text-white">+5</div>
                        </div>
                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest ml-1">Read by 850+ users</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="lg:grid lg:grid-cols-12 lg:gap-12">
        
        
        <div class="lg:col-span-8">

            
            <?php if($post->featured_image): ?>
            <div class="mb-12 rounded-3xl overflow-hidden aspect-[16/8] bg-slate-100 shadow-2xl shadow-slate-200/50">
                <img src="<?php echo e($post->featured_image); ?>" alt="<?php echo e($post->title); ?>" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>

            
            <article class="prose prose-slate prose-lg max-w-none
                            prose-p:text-slate-600 prose-p:leading-relaxed
                            prose-headings:font-black prose-headings:text-[#031629] prose-headings:uppercase prose-headings:italic
                            prose-a:text-[#ff6900] prose-a:no-underline hover:prose-a:underline
                            prose-img:rounded-3xl prose-img:shadow-2xl prose-img:shadow-slate-200/50">
                <?php echo $post->content['body'] ?? ''; ?>

            </article>

            
            <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

                
                <a href="<?php echo e(route('blog.index')); ?>"
                   class="flex items-center gap-2 text-[0.6rem] font-black text-slate-400 uppercase tracking-widest hover:text-[#ff6900] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    All Articles
                </a>

                
                <?php if($post->category): ?>
                <a href="<?php echo e(route('blog.index', ['cat' => $post->category->slug])); ?>"
                   class="flex items-center gap-2 px-4 py-2 bg-[#ff6900]/10 text-[#ff6900] rounded-full text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#ff6900] hover:text-white transition-all">
                    More in <?php echo e($post->category->name); ?>

                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <?php endif; ?>
            </div>

            
            <?php if($related->count()): ?>
            <div class="mt-16">
                <h2 class="text-2xl font-black text-[#031629] uppercase italic tracking-tighter mb-8">
                    Related <span class="text-[#ff6900]">Articles</span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('blog.show', $rel->slug)); ?>"
                       class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all">
                        <?php if($rel->featured_image): ?>
                        <div class="aspect-[16/9] overflow-hidden bg-[#f0f2f5]">
                            <img src="<?php echo e($rel->featured_image); ?>" alt="<?php echo e($rel->title); ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <?php else: ?>
                        <div class="aspect-[16/9] bg-[#f0f2f5] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <div class="text-[0.7rem] font-black text-[#1d293d] group-hover:text-[#ff6900] transition-colors line-clamp-2">
                                <?php echo e($rel->title); ?>

                            </div>
                            <div class="text-[0.55rem] text-slate-400 mt-1.5">
                                <?php echo e(($rel->published_at ?? $rel->created_at)?->format('d M Y')); ?>

                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <aside class="lg:col-span-4 mt-16 lg:mt-0 space-y-12">
            
            
            <div>
                <h3 class="text-lg font-black text-[#031629] uppercase italic tracking-tighter mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-[#ff6900]/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    </span>
                    Vehicle <span class="text-[#ff6900]">Catalog</span>
                </h3>
                <div class="bg-white rounded-2xl border border-slate-100 p-2 shadow-sm grid grid-cols-2 gap-1">
                    <?php $__currentLoopData = $brands->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('auctions.index', ['make' => $brand->name])); ?>" 
                       class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center p-1.5 group-hover:bg-white shadow-sm border border-transparent group-hover:border-slate-100">
                            <img src="<?php echo e($brand->logo_url); ?>" alt="<?php echo e($brand->name); ?>" class="w-full h-full object-contain">
                        </div>
                        <span class="text-[0.65rem] font-bold text-slate-600 group-hover:text-[#ff6900]"><?php echo e($brand->name); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('auctions.index')); ?>" class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 hover:text-[#ff6900] transition-colors">
                        Explore All Auctions →
                    </a>
                </div>
            </div>

            
            <div>
                <h3 class="text-lg font-black text-[#031629] uppercase italic tracking-tighter mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-[#031629]/5 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#031629" stroke-width="2.5"><path d="M4 11V4a2 2 0 0 1 2-2h10l4 4v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4"/><path d="M14 2v6h6"/><path d="M8 13h6"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L12 15"/></svg>
                    </span>
                    Latest <span class="text-[#ff6900]">Articles</span>
                </h3>
                <div class="space-y-6">
                    <?php $__currentLoopData = $latestPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('blog.show', $lp->slug)); ?>" class="group flex gap-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-[#f0f2f5] flex-shrink-0">
                            <?php if($lp->featured_image): ?>
                            <img src="<?php echo e($lp->featured_image); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M4 11V4a2 2 0 0 1 2-2h10l4 4v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4"/><path d="M14 2v6h6"/></svg>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 py-1">
                            <h4 class="text-[0.7rem] font-black text-[#031629] group-hover:text-[#ff6900] transition-colors leading-snug line-clamp-2 uppercase">
                                <?php echo e($lp->title); ?>

                            </h4>
                            <div class="text-[0.55rem] text-slate-400 font-bold mt-1.5 uppercase tracking-wide">
                                <?php echo e(($lp->published_at ?? $lp->created_at)?->format('d M Y')); ?>

                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="relative rounded-2xl bg-[#031629] p-8 overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-[#ff6900]/10 rounded-full blur-3xl group-hover:bg-[#ff6900]/20 transition-all duration-700"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-white uppercase italic tracking-tighter leading-none mb-2">Sell Your Car <span class="text-[#ff6900]">Fast</span></h3>
                    <p class="text-slate-400 text-[0.65rem] font-bold uppercase tracking-widest mb-6">Get an instant valuation now</p>
                    <a href="<?php echo e(route('home')); ?>?step=1" 
                       class="inline-block px-6 py-3 bg-[#ff6900] text-white rounded-xl text-[0.65rem] font-black uppercase tracking-widest hover:bg-white hover:text-[#031629] transition-all">
                        Get Appraisal
                    </a>
                </div>
                <div class="absolute bottom-0 right-0 opacity-10 -mb-4 -mr-4 pointer-events-none group-hover:scale-110 group-hover:-rotate-12 transition-all duration-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-white"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                </div>
            </div>

        </aside>
    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/blog/show.blade.php ENDPATH**/ ?>