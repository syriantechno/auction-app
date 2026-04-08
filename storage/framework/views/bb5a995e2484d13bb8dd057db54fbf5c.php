<?php $__env->startSection('title', 'Execute Technical Audit'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-1 space-y-10 pb-20" x-data="{ 
    checklist: [],
    addSection(type) {
        const name = prompt('Field Name (e.g. Chassis, Engine Bay, Interior):');
        if(!name) return;
        this.checklist.push({ id: Date.now(), type, name, value: '' });
    },
    removeSection(id) {
        this.checklist = this.checklist.filter(c => c.id !== id);
    }
}">
    <form id="audit-form" action="<?php echo e(route('admin.inspections.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if($lead): ?>
            <input type="hidden" name="lead_id" value="<?php echo e($lead->id); ?>">
        <?php endif; ?>
        
        <input type="hidden" name="detailed_checklists" :value="JSON.stringify(checklist)">

        <!-- Optimized Premium Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-10 border-b border-slate-100">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="w-14 h-14 rounded-2xl bg-[#1d293d] flex items-center justify-center shadow-2xl transform rotate-3">
                        <i data-lucide="shield-check" class="w-7 h-7 text-[#ff6900]"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">Execute <span class="text-[#ff6900]">Audit</span></h1>
                    <div class="flex items-center gap-3 mt-4">
                         <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 underline decoration-[#ff6900]/30 underline-offset-4">High-Fidelity Certification Protocol</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('admin.inspections.index')); ?>" class="px-6 py-4 bg-white text-slate-400 hover:text-slate-900 border border-slate-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all">
                    Cancel Session
                </a>
                <button type="submit" class="px-10 py-5 bg-[#1d293d] text-white rounded-2xl font-black shadow-2xl hover:bg-black transition-all flex items-center gap-4 text-[0.7rem] uppercase tracking-[0.2em] hover:scale-[1.02] active:scale-95">
                    <i data-lucide="verified" class="w-5 h-5 text-[#ff6900]"></i> Publish Report
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-10">
            
            <div class="md:col-span-2 space-y-10">
                
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-slate-50 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-50 rounded-full opacity-50"></div>
                    
                    <div class="flex items-center gap-4 mb-10 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#ff6900] flex items-center justify-center border border-orange-100">
                            <i data-lucide="car" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Asset Calibration Identity</h2>
                    </div>
                    
                    <?php if($selectedCar): ?>
                        <input type="hidden" name="car_id" value="<?php echo e($selectedCar->id); ?>">
                        <div class="bg-slate-50/50 border border-slate-100 p-8 rounded-[2rem] flex items-center gap-10 group transition-all">
                            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center border border-slate-100 shadow-sm overflow-hidden flex-shrink-0 p-4">
                                <?php
                                    $rawMake = strtolower($selectedCar->make ?? 'generic');
                                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                                    $finalLogo = null;
                                    foreach ($searchPaths as $p) { if(file_exists(public_path($p))) { $finalLogo = $p; break; } }
                                ?>
                                <?php if($finalLogo): ?>
                                     <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain filter drop-shadow-sm group-hover:scale-110 transition-transform">
                                <?php else: ?>
                                     <i data-lucide="car-front" class="w-12 h-12 text-slate-200"></i>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 bg-black text-white text-[0.6rem] font-black rounded-lg uppercase tracking-widest italic shadow-lg shadow-black/10"><?php echo e($selectedCar->year); ?></span>
                                    <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-[0.2em] italic">Unit Verified</span>
                                </div>
                                <h3 class="text-3xl font-black text-[#031629] leading-tight truncate italic">
                                    <?php echo e($selectedCar->make); ?> <span class="text-[#ff6900]"><?php echo e($selectedCar->model); ?></span>
                                </h3>
                                <div class="flex items-center gap-5 mt-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="fingerprint" class="w-4 h-4 text-slate-300"></i>
                                        <span class="text-xs font-bold text-slate-400 font-mono tracking-tighter"><?php echo e($selectedCar->vin ?? 'SERIAL-PENDING'); ?></span>
                                    </div>
                                    <div class="h-4 w-px bg-slate-200"></div>
                                    <div class="text-[0.65rem] font-black text-emerald-500 uppercase tracking-widest italic">Locked for Audit</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <select name="car_id" class="w-full h-16 bg-slate-50 border border-slate-100 px-6 rounded-2xl font-black text-[#031629] text-sm outline-none focus:bg-white focus:border-[#ff6900] transition-all">
                            <option value="">Select Asset...</option>
                            <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($car->id); ?>"><?php echo e($car->year); ?> <?php echo e($car->make); ?> <?php echo e($car->model); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php endif; ?>
                </div>

                
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-slate-50">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                            <i data-lucide="gauge" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Technical Scorecard Matrix</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-10">
                        <?php $__currentLoopData = ['engine_score' => 'Powerplant & Mechanics', 'paint_score' => 'Body & Paint Finish', 'transmission_score' => 'Drivetrain Dynamics', 'interior_score' => 'Cabin Integrity', 'tires_score' => 'Tire Profile Status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-4">
                            <div class="flex justify-between items-end px-1">
                                <label class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic"><?php echo e($label); ?></label>
                                <span class="text-lg font-black text-[#031629] italic tabular-nums leading-none"><span id="<?php echo e($key); ?>_val" class="text-[#ff6900]">100</span><span class="text-xs text-slate-300 ml-1">/ 100</span></span>
                            </div>
                            <input type="range" name="<?php echo e($key); ?>" min="0" max="100" value="100" oninput="document.getElementById('<?php echo e($key); ?>_val').textContent = this.value" class="w-full h-3 bg-slate-100 rounded-full appearance-none cursor-pointer accent-[#ff6900]">
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <?php
                    $customFields = json_decode(\App\Models\SystemSetting::get('inspection_fields', '[]'), true) ?: [];
                ?>

                <?php if(count($customFields) > 0): ?>
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-slate-50">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Extended Audit Fields</h2>
                            <a href="<?php echo e(route('admin.settings.inspection-fields')); ?>" class="text-[0.6rem] text-[#ff6900] font-bold uppercase tracking-widest hover:underline">Configure Fields →</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-3">
                            <label class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic ml-1 flex items-center gap-2">
                                <?php echo e($cf['label']); ?>

                                <?php if($cf['required'] ?? false): ?>
                                    <span class="text-[#ff6900] text-xs">*</span>
                                <?php endif; ?>
                                <span class="ml-auto px-2 py-0.5 rounded-md text-[0.55rem] font-black uppercase
                                    <?php if($cf['type'] === 'text'): ?> bg-blue-50 text-blue-400
                                    <?php elseif($cf['type'] === 'textarea'): ?> bg-violet-50 text-violet-400
                                    <?php elseif($cf['type'] === 'image'): ?> bg-orange-50 text-orange-400
                                    <?php else: ?> bg-emerald-50 text-emerald-400 <?php endif; ?>
                                "><?php echo e($cf['type']); ?></span>
                            </label>

                            <?php if($cf['type'] === 'text'): ?>
                                <input type="text" name="custom_field[<?php echo e($cf['id']); ?>]"
                                       placeholder="Enter <?php echo e($cf['label']); ?>..."
                                       <?php echo e(($cf['required'] ?? false) ? 'required' : ''); ?>

                                       class="w-full h-14 bg-slate-50/50 border-2 border-slate-100 px-6 rounded-2xl font-bold text-sm text-[#031629] outline-none focus:bg-white focus:border-blue-500 transition-all placeholder:text-slate-300">

                            <?php elseif($cf['type'] === 'textarea'): ?>
                                <textarea name="custom_field[<?php echo e($cf['id']); ?>]" rows="4"
                                          placeholder="Enter <?php echo e($cf['label']); ?>..."
                                          <?php echo e(($cf['required'] ?? false) ? 'required' : ''); ?>

                                          class="w-full bg-slate-50/50 border-2 border-slate-100 px-6 py-5 rounded-[1.5rem] font-bold text-sm text-[#031629] outline-none focus:bg-white focus:border-blue-500 transition-all placeholder:text-slate-300"></textarea>

                            <?php elseif($cf['type'] === 'image'): ?>
                                <div class="relative">
                                    <input type="file" name="custom_field_img[<?php echo e($cf['id']); ?>]"
                                           accept="image/*"
                                           <?php echo e(($cf['required'] ?? false) ? 'required' : ''); ?>

                                           class="w-full file:bg-[#1d293d] file:text-white file:border-none file:px-6 file:py-3 file:rounded-xl file:text-[0.6rem] file:font-black file:uppercase file:tracking-widest file:mr-6 file:cursor-pointer p-5 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer focus:outline-none focus:border-blue-300">
                                    <p class="text-[0.6rem] text-slate-300 font-bold uppercase italic mt-2 ml-1">High resolution recommended · JPG / PNG / WebP</p>
                                </div>

                            <?php elseif($cf['type'] === 'checkbox'): ?>
                                <label class="flex items-center gap-4 p-5 bg-slate-50/50 border-2 border-slate-100 rounded-2xl cursor-pointer hover:bg-white hover:border-emerald-200 transition-all group">
                                    <input type="checkbox" name="custom_field[<?php echo e($cf['id']); ?>]" value="1"
                                           class="w-5 h-5 rounded accent-emerald-500">
                                    <span class="text-sm font-black text-slate-500 group-hover:text-slate-900 transition-colors italic"><?php echo e($cf['label']); ?></span>
                                </label>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-[2.5rem] p-10 flex items-center gap-6">
                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-200 shrink-0">
                        <i data-lucide="settings-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-300 uppercase italic tracking-tight">No extended fields configured</p>
                        <a href="<?php echo e(route('admin.settings.inspection-fields')); ?>" class="text-[0.65rem] text-[#ff6900] font-black uppercase tracking-widest hover:underline">Configure Audit Fields →</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="space-y-10">
                <?php if($lead): ?>
                <div class="bg-orange-50/50 rounded-[2rem] p-10 border border-orange-100 shadow-lg relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-orange-100 rounded-full opacity-30"></div>
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-[#ff6900] flex items-center justify-center">
                            <i data-lucide="info" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-sm font-black text-orange-900 uppercase tracking-widest italic">Lead Context</h2>
                    </div>
                    <div class="space-y-3 relative z-10">
                        <p class="text-sm font-bold text-orange-900 italic underline decoration-orange-300 underline-offset-4"><?php echo e($lead->car_details['name'] ?? 'Authorized Contact'); ?></p>
                        <p class="text-[0.65rem] text-orange-600 font-black uppercase tracking-widest">Lead Reference: #L-<?php echo e($lead->id); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-[#1d293d] rounded-[2.5rem] p-10 shadow-2xl border border-black relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="flex items-center gap-4 mb-10 relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 text-white flex items-center justify-center shadow-inner">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-sm font-black text-white uppercase tracking-widest italic">Executive Verdict</h2>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <textarea name="expert_summary" rows="10" class="w-full bg-black/30 border-none px-6 py-6 rounded-[2rem] font-bold text-[#fcf9d0] text-sm outline-none placeholder:text-slate-600 shadow-inner focus:ring-2 focus:ring-[#ff6900]/20 transition-all font-mono" placeholder="Provide a high-fidelity summary..."></textarea>
                        
                        <div class="bg-black/20 p-6 rounded-2xl border border-white/5 flex flex-col gap-2">
                            <span class="text-[0.6rem] text-slate-500 font-black uppercase tracking-widest">Authorized Auditor</span>
                            <div class="text-[0.8rem] font-black text-white italic tracking-tight"><?php echo e(Auth::user()->name); ?> <span class="text-[#ff6900] ml-1">/ TECH-OPS</span></div>
                        </div>
                    </div>

                    <div class="mt-10 relative z-10" x-data="{ submitting: false }">
                        <button type="submit" 
                                @click="submitting = true"
                                :disabled="submitting"
                                :class="submitting ? 'opacity-50 cursor-not-allowed scale-95' : 'hover:scale-[1.02] shadow-orange-500/30'"
                                class="w-full h-20 bg-[#ff6900] text-white rounded-[1.5rem] font-black shadow-2xl transition-all duration-300 flex items-center justify-center gap-4 text-[0.75rem] uppercase tracking-[0.3em]">
                            <i data-lucide="shield-check" class="w-6 h-6" x-show="!submitting"></i>
                            <span x-show="!submitting">Finalize Mission</span>
                            <span x-show="submitting" class="flex items-center gap-3">
                                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Archiving...
                            </span>
                        </button>
                        <p class="text-center text-[0.55rem] text-slate-500 font-bold uppercase tracking-widest mt-6 opacity-60 italic">Publishing report triggers liquidation clearance</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/inspections/create.blade.php ENDPATH**/ ?>