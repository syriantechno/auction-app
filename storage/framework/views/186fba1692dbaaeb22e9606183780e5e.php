<?php $__env->startSection('title', '404 - Page Not Found'); ?>
<?php $__env->startSection('meta_description', 'The page you are looking for does not exist on Motor Bazar.'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center pt-24 pb-20 px-6">
    <div class="max-w-4xl w-full text-center">
        
        <div class="relative inline-block mb-12">
            <h1 class="text-[12rem] md:text-[18rem] font-black text-[#031629]/5 leading-none select-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="space-y-4">
                    <div class="w-24 h-24 bg-[#ff6900]/10 rounded-3xl flex items-center justify-center mx-auto mb-6 animate-bounce">
                        <i data-lucide="search-x" class="w-12 h-12 text-[#ff6900]"></i>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-black text-[#031629] uppercase italic tracking-tighter">
                        Lost in the <span class="text-[#ff6900]">Circuit</span>
                    </h2>
                </div>
            </div>
        </div>

        <p class="text-slate-500 font-bold text-lg max-w-xl mx-auto mb-12 leading-relaxed">
            The page you're looking for has been moved, deleted, or never existed in our catalog.
            Let's get you back on track to finding your next vehicle.
        </p>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <a href="/" class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i data-lucide="home" class="w-5 h-5"></i>
                </div>
                <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-1">Return Home</div>
                <div class="text-sm font-black text-slate-800">Main Dashboard</div>
            </a>

            <a href="<?php echo e(route('auctions.index')); ?>" class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-[#ff6900]/10 text-[#ff6900] flex items-center justify-center mb-4 group-hover:bg-[#ff6900] group-hover:text-white transition-all">
                    <i data-lucide="gavel" class="w-5 h-5"></i>
                </div>
                <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-1">Browse Catalog</div>
                <div class="text-sm font-black text-slate-800">Live Auctions</div>
            </a>

            <a href="<?php echo e(route('blog.index')); ?>" class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-1">Market Insights</div>
                <div class="text-sm font-black text-slate-800">Our Blog</div>
            </a>
        </div>

        <div class="mt-16">
            <a href="javascript:history.back()" class="text-[0.7rem] font-black text-slate-400 uppercase tracking-widest hover:text-[#ff6900] transition-colors flex items-center justify-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Go Back to Safety
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/errors/404.blade.php ENDPATH**/ ?>