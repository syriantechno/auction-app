

<?php $__env->startSection('title', 'Review Lead Entry'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-1 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 mb-1 tracking-tight">Review Entry: <span class="text-[#FF6900]">#<?php echo e($lead->id); ?></span></h1>
            <p class="text-[0.65rem] text-slate-400 font-black uppercase tracking-[0.3em] leading-none">Lead Inquiry Details</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $lead->car_details['phone'] ?? '')); ?>" target="_blank" class="px-6 py-3 bg-emerald-600 text-white rounded-md font-black uppercase tracking-widest text-[0.65rem] shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i data-lucide="message-circle" class="w-4"></i> WhatsApp Contact
            </a>
            <a href="<?php echo e(route('admin.leads.index')); ?>" class="px-6 py-3 bg-white text-slate-500 rounded-md font-black border border-slate-200 shadow-sm hover:bg-slate-50 flex items-center gap-2 text-[0.65rem] uppercase tracking-widest transition-all">
                <i data-lucide="arrow-left" class="w-3.5"></i> Exit Queue
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-emerald-50 text-emerald-600 px-5 py-4 rounded-lg mb-8 font-black border border-emerald-100 flex items-center gap-3 text-xs shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
            <i data-lucide="check-circle" class="w-5"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-8">
            
            
            <div class="bg-white rounded-lg p-8 shadow-xl border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                    <i data-lucide="shield-check" class="w-40 h-40"></i>
                </div>
                
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 text-[#FF6900] flex items-center justify-center shadow-inner">
                        <i data-lucide="car" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-[0.8rem] font-black text-slate-900 uppercase tracking-widest">Asset Specification Hub</h2>
                        <p class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Verified Technical Capture</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest">Mileage</span>
                        <div class="text-lg font-black text-slate-900 tabular-nums"><?php echo e(is_numeric($lead->car_details['mileage'] ?? null) ? number_format($lead->car_details['mileage']) : ($lead->car_details['mileage'] ?? 'N/A')); ?> <span class="text-[0.6rem] text-slate-400 ml-1">KM</span></div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest">Engine</span>
                        <div class="text-lg font-black text-slate-900"><?php echo e($lead->car_details['engine'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest">Specs</span>
                        <div class="text-lg font-black text-slate-900"><?php echo e($lead->car_details['gcc_specs'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest">Paint</span>
                        <div class="text-lg font-black text-slate-900"><?php echo e($lead->car_details['paint'] ?? 'N/A'); ?></div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-50">
                    <div class="flex items-center justify-between p-5 rounded-lg bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-md bg-white border border-slate-200 flex items-center justify-center p-2">
                                <img src="/images/brands/<?php echo e(strtolower($lead->car_details['make'] ?? 'default')); ?>.svg" class="w-full h-full object-contain mix-blend-multiply" onerror="this.src='https://placehold.co/40x40?text=C'">
                            </div>
                            <div>
                                <span class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest"><?php echo e($lead->car_details['year'] ?? ''); ?> <?php echo e($lead->car_details['make'] ?? 'Unknown'); ?></span>
                                <p class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($lead->car_details['model'] ?? 'N/A'); ?> • <?php echo e($lead->car_details['trim'] ?? 'Standard'); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-4 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-[0.6rem] font-black uppercase tracking-[0.2em] border border-emerald-100 italic">
                                <?php echo e(strtoupper($lead->car_details['condition'] ?? 'Good')); ?> Condition
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg p-8 shadow-xl border border-slate-100 overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-[0.8rem] font-black text-slate-900 uppercase tracking-widest">Dynamic Location Hub</h2>
                            <p class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Physical Node Extraction</p>
                        </div>
                    </div>
                    <?php
                        $isHome = ($lead->car_details['inspection_type'] ?? 'branch') === 'home';
                        $locationQuery = $isHome ? ($lead->car_details['home_address'] ?? 'Dubai') : 'Al Quoz Industrial Area 3, Dubai';
                    ?>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo e(urlencode($locationQuery)); ?>" target="_blank" class="text-[0.6rem] font-black text-blue-500 uppercase tracking-widest border-b border-blue-100 pb-0.5 hover:text-blue-700 transition-all italic">Open Expert Sat-View</a>
                </div>

                <div class="relative h-[480px] rounded-lg border-4 border-slate-50 overflow-hidden group shadow-inner">
                    <iframe 
                        width="100%" 
                        height="100%" 
                        frameborder="0" 
                        style="border:0" 
                        src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=<?php echo e(urlencode($locationQuery)); ?>" 
                        allowfullscreen>
                    </iframe>
                    
                    
                    <div class="absolute bottom-6 left-6 right-6 z-10">
                        <div class="p-6 rounded-lg bg-white/90 backdrop-blur-xl border border-white shadow-2xl flex items-center justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-md bg-[#1d293d] flex items-center justify-center shadow-lg">
                                    <i data-lucide="<?php echo e($isHome ? 'home' : 'building-2'); ?>" class="w-6 h-6 text-white"></i>
                                </div>
                                <div class="max-w-[15rem]">
                                    <h5 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] mb-1"><?php echo e($isHome ? 'Service Node' : 'Hub Node'); ?></h5>
                                    <p class="text-[0.75rem] text-slate-900 font-black tracking-tight truncate"><?php echo e($locationQuery); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[0.55rem] font-black uppercase tracking-widest border border-blue-100">Live Map Link</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-4 space-y-8">
            
            
            <div class="bg-[#1d293d] rounded-lg p-8 shadow-2xl text-white relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#FF6900]/10 rounded-full blur-3xl group-hover:bg-[#FF6900]/20 transition-all duration-700"></div>
                
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-lg bg-[#FF6900] text-white flex items-center justify-center shadow-[0_10px_30px_-5px_rgba(255,105,0,0.5)]">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-[0.8rem] font-black text-white uppercase tracking-widest">Schedule Inspection</h2>
                        <p class="text-[0.6rem] text-white/30 font-bold uppercase tracking-widest mt-0.5">Scheduled Protocol</p>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="p-6 rounded-lg bg-white/5 border border-white/10 text-center">
                        <div class="text-3xl font-black mb-1 tabular-nums"><?php echo e($lead->car_details['inspection_date'] ?? 'TBD'); ?></div>
                        <div class="text-[0.65rem] font-black text-[#FF6900] uppercase tracking-[0.4em]"><?php echo e($lead->car_details['inspection_time'] ?? 'ASAP REQUEST'); ?></div>
                    </div>
                    <div class="flex items-center gap-4 text-[0.65rem] font-black uppercase tracking-[0.2em] px-2 text-white/50">
                        <div class="flex-1 border-t border-white/5"></div>
                        <span>Protocol Status</span>
                        <div class="flex-1 border-t border-white/5"></div>
                    </div>
                    <div class="flex items-center justify-center gap-2 group-hover:scale-105 transition-transform duration-500">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                        <span class="text-[0.7rem] font-black uppercase tracking-widest">Active Verification Required</span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg p-8 shadow-xl border border-slate-100">
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                    <div class="w-12 h-12 rounded-lg bg-[#1d293d] flex items-center justify-center text-white shadow-lg">
                        <span class="text-lg font-black"><?php echo e(mb_substr($lead->car_details['name'] ?? 'U', 0, 1)); ?></span>
                    </div>
                    <div>
                        <h3 class="text-[0.85rem] font-black text-slate-900 leading-none mb-1"><?php echo e($lead->car_details['name'] ?? 'Anonymous'); ?></h3>
                        <p class="text-[0.65rem] text-slate-400 font-bold italic"><?php echo e($lead->car_details['email'] ?? 'No Email Channel'); ?></p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-[0.7rem] font-bold">
                        <span class="text-slate-400 uppercase tracking-widest">Lead Origin</span>
                        <span class="text-slate-900 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">Home Wizard</span>
                    </div>
                    <div class="flex items-center justify-between text-[0.7rem] font-bold">
                        <span class="text-slate-400 uppercase tracking-widest">Node ID</span>
                        <span class="text-slate-900 tabular-nums">#00<?php echo e($lead->id); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg p-8 shadow-xl border border-slate-100">
                <form action="<?php echo e(route('admin.leads.update', $lead)); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="space-y-2">
                        <label class="text-[0.65rem] text-slate-400 font-black uppercase tracking-[0.2em] ml-1">Lifecycle State</label>
                        <select name="status" class="w-full h-12 bg-slate-50 border border-slate-100 px-4 rounded-md font-black text-[0.7rem] text-slate-900 outline-none focus:ring-2 focus:ring-[#FF6900]/10 focus:border-[#FF6900] transition-all">
                            <option value="new" <?php echo e($lead->status == 'new' ? 'selected' : ''); ?>>New Protocol</option>
                            <option value="pending" <?php echo e($lead->status == 'pending' ? 'selected' : ''); ?>>In Queue (Pending)</option>
                            <option value="in_review" <?php echo e($lead->status == 'in_review' ? 'selected' : ''); ?>>Review Phase</option>
                            <option value="inspection_scheduled" <?php echo e($lead->status == 'inspection_scheduled' ? 'selected' : ''); ?>>Inspection Scheduled</option>
                            <option value="approved" <?php echo e($lead->status == 'approved' ? 'selected' : ''); ?>>Verified (Approved)</option>
                            <option value="rejected" <?php echo e($lead->status == 'rejected' ? 'selected' : ''); ?>>Archived (Rejected)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[0.65rem] text-slate-400 font-black uppercase tracking-[0.2em] ml-1">Obs Note</label>
                        <textarea name="notes" rows="4" class="w-full bg-slate-50 border border-slate-100 px-5 py-4 rounded-lg font-black text-[0.7rem] text-slate-900 outline-none focus:ring-2 focus:ring-[#FF6900]/10 focus:border-[#FF6900] transition-all" placeholder="Enter findings..."><?php echo e($lead->notes); ?></textarea>
                    </div>

                    <button type="submit" class="w-full h-14 bg-[#1d293d] text-white rounded-lg font-black text-[0.65rem] uppercase tracking-[0.3em] shadow-xl hover:shadow-2xl active:scale-[0.98] transition-all group">
                        Commit Re-Calibration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\auction_app\resources\views/admin/leads/show.blade.php ENDPATH**/ ?>