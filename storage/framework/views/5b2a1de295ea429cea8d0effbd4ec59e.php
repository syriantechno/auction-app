<?php $__env->startSection('title', $dealer->name . ' — Dealer Profile · Motor Bazar'); ?>

<?php $__env->startSection('custom_css'); ?>
<style>
    .profile-hero { background: #031629; position: relative; overflow: hidden; }
    .hero-grid { position: absolute; inset: 0; background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 40px 40px; }
    .tab-btn { background: white; border: 1px solid #e2e8f0; color: #64748b; transition: all 0.3s; }
    .tab-btn.active { background: #031629; border-color: #031629; color: white; }
    .deal-card { background: white; border-radius: 2.5rem; overflow: hidden; display: flex; flex-direction: column; border: 1px solid #eef0f5; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .deal-card:hover { transform: translateY(-8px); box-shadow: 0 30px 60px -12px rgba(0,0,0,0.12); border-color: #ff690020; }
    .car-panel { height: 180px; position: relative; overflow: hidden; background: #f8fafc; }
    .car-bg { width: 100%; height: 100%; object-fit: cover; transition: transform 1.5s ease; }
    .deal-card:hover .car-bg { transform: scale(1.1); }
    .brand-logo-wrap { position: absolute; top: 1.5rem; left: 1.5rem; z-index: 20; }
    .brand-logo-inner { width: 3.5rem; h: 3.5rem; background: white; border-radius: 1.25rem; display: flex; items-center; justify-center; p: 0.75rem; shadow: lg; }
    .status-badge-abs { position: absolute; top: 1.5rem; right: 1.5rem; z-index: 20; px: 3; py: 1.5; rounded: full; text: [0.55rem]; font: black; uppercase; tracking: widest; }
    .car-ref { position: absolute; bottom: 1.5rem; left: 1.5rem; z-index: 20; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.55s ease both; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="profile-hero pt-32 pb-16 px-6">
    <div class="hero-grid"></div>
    <div class="max-w-6xl mx-auto relative z-10">

        <a href="<?php echo e(url()->previous()); ?>" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs font-black uppercase tracking-widest mb-10 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back
        </a>

        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-10">

            
            <div class="w-24 h-24 rounded-2xl border-2 border-[#ff6900]/40 shadow-2xl shadow-black/40 bg-[#ff6900]/10 flex items-center justify-center flex-shrink-0">
                <span class="text-3xl font-black text-[#ff6900] uppercase italic"><?php echo e(strtoupper(substr($dealer->name,0,2))); ?></span>
            </div>

            
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter leading-none"><?php echo e($dealer->name); ?></h1>
                    <?php if($won->count() > 0): ?>
                    <span class="px-3 py-1.5 bg-[#ff6900]/15 border border-[#ff6900]/30 text-[#ff6900] rounded-full text-[0.55rem] font-black uppercase tracking-widest">
                        ✦ Verified Buyer
                    </span>
                    <?php endif; ?>
                </div>
                <p class="text-white/40 text-sm font-bold mb-1">Member since <?php echo e($dealer->created_at->format('M Y')); ?></p>
                <?php if(auth()->check() && auth()->id() === $dealer->id): ?>
                <p class="text-white/20 text-xs font-medium"><?php echo e($dealer->email); ?></p>
                <?php endif; ?>

                
                <div class="flex flex-wrap items-center gap-6 mt-6">
                    <?php $__currentLoopData = [
                        ['v' => number_format($totalBids),       'l' => 'Total Bids',    'c' => 'text-white'],
                        ['v' => $won->count(),                   'l' => 'Auctions Won',  'c' => 'text-[#ff6900]'],
                        ['v' => $participating->count(),          'l' => 'Active Bids',   'c' => 'text-emerald-400'],
                        ['v' => $winRate . '%',                   'l' => 'Win Rate',      'c' => 'text-sky-400'],
                        ['v' => '$' . number_format($highestBid), 'l' => 'Top Bid',       'c' => 'text-white'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($i > 0): ?><div class="w-px h-8 bg-white/10"></div><?php endif; ?>
                    <div class="text-center">
                        <div class="text-2xl font-black <?php echo e($s['c']); ?> tabular-nums"><?php echo e($s['v']); ?></div>
                        <div class="text-[0.55rem] font-black text-white/35 uppercase tracking-widest mt-0.5"><?php echo e($s['l']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="grid grid-cols-2 gap-3 lg:w-60 flex-shrink-0">
                <div class="col-span-2 bg-white/5 border border-white/8 rounded-2xl p-5 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Total Participated</div>
                    <div class="text-3xl font-black text-white tabular-nums"><?php echo e($participating->count() + $won->count()); ?></div>
                </div>
                <div class="bg-white/5 border border-white/8 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Avg. Bid</div>
                    <div class="text-lg font-black text-white">$<?php echo e(number_format($avgBid)); ?></div>
                </div>
                <div class="bg-white/5 border border-white/8 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Spent</div>
                    <div class="text-lg font-black text-[#ff6900]">$<?php echo e(number_format($totalSpent)); ?></div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="bg-[#f0f2f7] min-h-screen py-12 px-6" x-data="{ tab: 'participating' }">
    <div class="max-w-6xl mx-auto">

        
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <button @click="tab = 'participating'"
                :class="{ 'active': tab === 'participating' }"
                class="tab-btn px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5">
                <i data-lucide="activity" class="w-4 h-4"></i>
                Active Auctions
                <span class="ml-1 bg-[#ff6900]/20 text-[#ff6900] rounded-full px-2 py-0.5 text-[0.55rem]"><?php echo e($participating->count()); ?></span>
            </button>
            <button @click="tab = 'won'"
                :class="{ 'active': tab === 'won' }"
                class="tab-btn px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5">
                <i data-lucide="trophy" class="w-4 h-4"></i>
                Won Auctions
                <span class="ml-1 bg-emerald-100 text-emerald-700 rounded-full px-2 py-0.5 text-[0.55rem]"><?php echo e($won->count()); ?></span>
            </button>
        </div>

        
        <div x-show="tab === 'participating'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $participating; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg","images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake,'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if (file_exists(public_path($p))) { $finalLogo = $p; break; } }
                    $carImg = $car?->image_url ?? asset('images/cars/car-silver.png');
                ?>
                <div class="deal-card fade-up <?php echo e($auction->is_leading ? '' : ''); ?>">

                    
                    <div class="car-panel">
                        <img src="<?php echo e($carImg); ?>" alt="<?php echo e($car?->make); ?>" class="car-bg absolute inset-0">
                        <div class="brand-logo-wrap">
                            <div class="brand-logo-inner">
                                <?php if($finalLogo): ?>
                                    <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <i data-lucide="car-front" class="w-10 h-10 text-[#ff6900] opacity-80"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($auction->is_leading): ?>
                        <span class="status-badge-abs bg-emerald-500 text-white shadow-lg shadow-emerald-500/40">⚡ Leading</span>
                        <?php else: ?>
                        <span class="status-badge-abs bg-amber-400 text-white">⚠ Outbid</span>
                        <?php endif; ?>
                        
                        <div class="car-ref">
                            <div class="text-[0.45rem] text-white/40 font-black uppercase tracking-widest mb-0.5">Auction Ref</div>
                            <div class="text-xs font-black text-white font-mono"><?php echo e($auction->reference_code); ?></div>
                        </div>
                    </div>

                    
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        <?php echo e($car?->make); ?> <span class="text-[#ff6900]"><?php echo e($car?->model); ?></span>
                                    </h3>
                                    <p class="text-[0.62rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide"><?php echo e($car?->year); ?> · <?php echo e(ucfirst($auction->status)); ?></p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.45rem] text-slate-400 font-black uppercase tracking-widest">My Bid</div>
                                    <div class="text-xl font-black text-[#031629] tabular-nums">$<?php echo e(number_format($auction->user_bid)); ?></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Top Bid</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="trending-up" class="w-3 h-3 <?php echo e($auction->is_leading ? 'text-emerald-500' : 'text-red-500'); ?>"></i>
                                        <span class="text-[0.8rem] font-black <?php echo e($auction->is_leading ? 'text-emerald-600' : 'text-red-500'); ?>">$<?php echo e(number_format($auction->top_bid)); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Ends</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3 h-3 text-[#ff6900]"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629] uppercase italic tracking-tighter">
                                            <?php echo e($auction->end_time ? \Carbon\Carbon::parse($auction->end_time)->format('d M @ g:ia') : 'TBD'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="<?php echo e(route('auctions.show', $auction)); ?>"
                               class="flex-1 h-12 bg-[#1d293d] hover:bg-[#ff6900] rounded-xl flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest transition-all hover:scale-[1.02] shadow-lg shadow-slate-900/10">
                                <i data-lucide="zap" class="w-4 h-4 text-orange-400"></i>
                                <?php echo e($auction->status === 'active' ? 'Bid Now' : 'View Auction'); ?>

                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-2 py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="activity" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Active Bids</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">No ongoing auction activity</p>
                    </div>
                    <a href="<?php echo e(route('auctions.index')); ?>" class="px-8 py-3 bg-[#1d293d] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-full hover:bg-[#ff6900] transition-all">
                        Browse Auctions
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div x-show="tab === 'won'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $won; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg","images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake,'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if (file_exists(public_path($p))) { $finalLogo = $p; break; } }
                    $carImg = $car?->image_url ?? asset('images/cars/car-silver.png');
                    $finalPrice = $auction->negotiation?->highest_bid ?? 0;
                    $inv = $auction->invoices?->first();
                ?>
                <div class="deal-card won-card fade-up">

                    
                    <div class="car-panel" style="background:#031629;">
                        <img src="<?php echo e($carImg); ?>" alt="<?php echo e($car?->make); ?>" class="car-bg absolute inset-0" style="opacity:0.55;">
                        <div class="brand-logo-wrap">
                            <div class="brand-logo-inner" style="background:rgba(255,255,255,0.88);">
                                <?php if($finalLogo): ?>
                                    <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <i data-lucide="car-front" class="w-10 h-10 text-emerald-500 opacity-80"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <span class="status-badge-abs bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 flex items-center gap-1">
                            🏆 Won
                        </span>
                        <div class="car-ref">
                            <div class="text-[0.45rem] text-white/40 font-black uppercase tracking-widest mb-0.5">Ref</div>
                            <div class="text-xs font-black text-white font-mono"><?php echo e($auction->reference_code); ?></div>
                        </div>
                    </div>

                    
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        <?php echo e($car?->make); ?> <span class="text-emerald-600"><?php echo e($car?->model); ?></span>
                                    </h3>
                                    <p class="text-[0.62rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide"><?php echo e($car?->year); ?> Production</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.45rem] text-slate-400 font-black uppercase tracking-widest">Final Price</div>
                                    <div class="text-xl font-black text-emerald-600 tabular-nums">$<?php echo e(number_format($finalPrice)); ?></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Won On</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar-check" class="w-3 h-3 text-emerald-500"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629]"><?php echo e($auction->negotiation?->updated_at?->format('d M Y') ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Invoice</div>
                                    <?php
                                        $sc = ['paid'=>'bg-emerald-100 text-emerald-700','partial'=>'bg-blue-100 text-blue-700','pending'=>'bg-amber-100 text-amber-700'];
                                        $ic = $sc[$inv?->status] ?? 'bg-slate-100 text-slate-500';
                                    ?>
                                    <span class="px-2 py-0.5 rounded-full text-[0.5rem] font-black uppercase tracking-widest <?php echo e($ic); ?>"><?php echo e(ucfirst($inv?->status ?? 'No Invoice')); ?></span>
                                </div>
                            </div>
                        </div>

                        <a href="<?php echo e(route('auctions.show', $auction)); ?>"
                           class="h-12 bg-[#031629] hover:bg-emerald-700 rounded-xl flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest transition-all hover:scale-[1.02] shadow-lg shadow-slate-900/10">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i> View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-2 py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="trophy" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Wins Yet</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">Start bidding to win your first auction</p>
                    </div>
                    <a href="<?php echo e(route('auctions.index')); ?>" class="px-8 py-3 bg-[#031629] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-full hover:bg-[#ff6900] transition-all">
                        Browse Auctions
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\auction_app\resources\views/dealer/profile.blade.php ENDPATH**/ ?>