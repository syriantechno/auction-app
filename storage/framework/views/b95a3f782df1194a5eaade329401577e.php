

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

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mt-10 items-stretch">
            
            <div class="md:col-span-2">
                <div class="group relative bg-white rounded-[1.25rem] shadow-xl border border-slate-100 overflow-hidden flex flex-col md:flex-row h-full min-h-[220px]">
                    <?php if($selectedCar): ?>
                        <?php
                            $isMercedes = str_contains(strtolower($selectedCar->make ?? ''), 'mercedes');
                            $displayImage = asset('images/cars/car-silver.png');
                            if (!empty($lead->car_details['image_url']) && !$isMercedes) {
                                $displayImage = $lead->car_details['image_url'];
                            } elseif (!empty($selectedCar->image_url) && !$isMercedes) {
                                $displayImage = $selectedCar->image_url;
                            }
                            
                            $rawMake = strtolower($selectedCar->make ?? 'generic');
                            $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                            $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                            if (str_contains($rawMake, 'mercedes')) $searchPaths[] = 'images/brands/mercedes.svg';
                            $finalLogo = null;
                            foreach ($searchPaths as $p) { if(file_exists(public_path($p))) { $finalLogo = $p; break; } }
                            $vinRef = strtoupper(substr($selectedCar->vin ?? 'SERIAL-PENDING', -8));
                        ?>

                        
                        <div class="relative w-full md:w-[35%] bg-[#1d293d] overflow-hidden flex items-center justify-center border-b md:border-b-0 md:border-r border-slate-50">
                            <?php
                                $isMercedes = str_contains(strtolower($selectedCar->make ?? ''), 'mercedes');
                                // Determine display image: prioritize lead JSON, then model, but force silver fallback for Mercedes if desired or missing
                                $displayImage = asset('images/cars/car-silver.png'); // Default premium silver Mercedes
                                
                                if (!empty($lead->car_details['image_url']) && !$isMercedes) {
                                    $displayImage = $lead->car_details['image_url'];
                                } elseif (!empty($selectedCar->image_url) && !$isMercedes) {
                                    $displayImage = $selectedCar->image_url;
                                }
                            ?>
                            <img src="<?php echo e($displayImage); ?>" 
                                 class="absolute inset-0 w-full h-full object-cover object-center opacity-90 group-hover:scale-110 transition-transform duration-[3s]" alt="">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-[#031629] via-transparent to-transparent opacity-80"></div>

                            
                            <div class="relative z-20 w-24 h-24 bg-white/95 backdrop-blur-md rounded-full flex items-center justify-center p-5 shadow-2xl border-2 border-white group-hover:scale-105 transition-transform">
                                <?php if($finalLogo): ?>
                                    <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <i data-lucide="car-front" class="w-12 h-12 text-slate-300"></i>
                                <?php endif; ?>
                            </div>

                            
                            <div class="absolute left-4 bottom-4 right-4 z-30">
                                <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-xl px-5 py-3 shadow-2xl">
                                    <p class="text-[0.45rem] font-bold text-slate-400 uppercase tracking-[0.3em] mb-1.5">Asset Reference</p>
                                    <p class="text-[1.1rem] font-bold text-white italic tracking-tighter leading-none">#<?php echo e($vinRef); ?></p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="flex-1 p-6 lg:p-8 flex flex-col justify-center bg-white relative">
                            <div class="mb-5">
                                <h3 class="text-3xl font-bold italic tracking-tighter text-[#031629] uppercase leading-none">
                                    <?php echo e($selectedCar->make ?? 'Unknown'); ?> <span class="text-[#ff6900] font-medium"><?php echo e($selectedCar->model ?? 'Vehicle'); ?></span>
                                </h3>
                                <p class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mt-2 italic opacity-60">Specifications & Identification</p>
                            </div>

                            <div class="flex items-center gap-8 border-t border-slate-50 pt-5">
                                <div class="space-y-1">
                                    <p class="text-[0.55rem] font-bold text-slate-500 uppercase tracking-widest opacity-80">Deployment Point</p>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                        <span class="text-[0.9rem] font-bold text-[#031629] italic truncate"><?php echo e($selectedCar->location ?? 'Sharjah'); ?></span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[0.55rem] font-bold text-slate-500 uppercase tracking-widest opacity-80">Registration</p>
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="fingerprint" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                        <span class="text-[0.9rem] font-bold text-[#031629] italic leading-none"><?php echo e($selectedCar->plate_number ?? 'CERTIFIED-UNIT'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        
                        <div class="flex-1 p-10 bg-white">
                            <select name="car_id" class="w-full h-16 bg-slate-50 border border-slate-100 px-6 rounded-2xl font-bold text-[#031629] text-sm outline-none focus:bg-white focus:border-[#ff6900] transition-all">
                                <option value="">Select Asset for Calibration...</option>
                                <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($car->id); ?>"><?php echo e($car->year); ?> <?php echo e($car->make); ?> <?php echo e($car->model); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="md:col-span-1">
                <?php if($lead): ?>
                <div class="group relative bg-white rounded-[1.25rem] shadow-xl border border-slate-100 overflow-hidden flex flex-col md:flex-row h-full min-h-[220px]">
                    
                    <div class="relative w-full md:w-[35%] bg-[#1d293d] overflow-hidden flex items-center justify-center border-b md:border-b-0 md:border-r border-slate-50">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#031629] via-transparent to-transparent opacity-60"></div>

                        
                        <div class="relative z-20 w-16 h-16 bg-white/95 backdrop-blur-md rounded-full flex items-center justify-center shadow-xl border-2 border-white group-hover:scale-105 transition-transform duration-500">
                            <i data-lucide="user" class="w-7 h-7 text-[#ff6900]"></i>
                        </div>

                        
                        <div class="absolute left-3 bottom-3 right-3 z-30">
                            <div class="bg-black/40 backdrop-blur-xl border border-white/10 rounded-xl px-3 py-2 shadow-2xl">
                                <p class="text-[0.4rem] font-bold text-slate-400 uppercase tracking-widest mb-1">Lead ID</p>
                                <p class="text-[0.8rem] font-bold text-white italic tracking-tighter leading-none">#L-<?php echo e($lead->id); ?></p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex-1 p-6 flex flex-col justify-center bg-white relative">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold italic tracking-tighter text-[#031629] uppercase leading-none truncate">
                                <?php echo e($lead->car_details['name'] ?? 'Authorized Contact'); ?>

                            </h3>
                            <p class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mt-2 italic opacity-60">Client Information</p>
                        </div>

                        <div class="space-y-3 border-t border-slate-50 pt-4">
                            <div class="space-y-1">
                                <p class="text-[0.55rem] font-bold text-slate-500 uppercase tracking-widest opacity-80">Phone Number</p>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                    <span class="text-[0.9rem] font-bold text-[#031629] italic"><?php echo e($lead->car_details['phone'] ?? '+971 --'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center gap-2">
                            <a href="tel:<?php echo e($lead->car_details['phone'] ?? '#'); ?>" class="flex-1 h-9 bg-[#1d293d] hover:bg-[#031629] text-white rounded-xl flex items-center justify-center gap-2 shadow-sm transition-all text-center">
                                <i data-lucide="phone-outgoing" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                <span class="text-[0.55rem] font-bold uppercase">Call</span>
                            </a>
                            <button type="button" onclick="window.location.href='https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $lead->car_details['phone'] ?? '')); ?>'" class="flex-1 h-9 bg-slate-50 hover:bg-emerald-50 border border-slate-100 rounded-xl flex items-center justify-center gap-2 transition-all">
                                <i data-lucide="message-square" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span class="text-[0.55rem] font-bold text-slate-500 uppercase">WhatsApp</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php
            $rawFields = json_decode(\App\Models\SystemSetting::get('inspection_fields', '[]'), true) ?: [];
            $inspSections = \App\Support\InspectionFieldsConfig::normalizeSections($rawFields);
        ?>

        <?php if(count($inspSections) > 0): ?>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-10">
                <?php $__currentLoopData = $inspSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $isHalf = ($section['width'] ?? 'full') === 'half'; ?>
                    <div class="bg-white rounded-[1rem] shadow-sm border border-slate-100 transition-all duration-500 <?php echo e($isHalf ? 'lg:col-span-6' : 'lg:col-span-12'); ?>"
                         x-data="{ isOpen: false }"
                         :class="isOpen ? 'overflow-visible' : 'overflow-hidden'">
                         
                         
                         <div class="flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-slate-50/80 transition-all select-none group"
                              @click="isOpen = !isOpen"
                              :class="isOpen ? 'bg-slate-50 border-b border-slate-100 rounded-t-[1rem]' : ''">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#ff6900] flex items-center justify-center border border-orange-100 shadow-sm group-hover:scale-110 transition-transform">
                                    <i data-lucide="list-checks" class="w-4 h-4"></i>
                                </div>
                                <div class="flex items-center gap-3">
                                    <h2 class="text-[0.75rem] font-bold text-[#031629] uppercase tracking-wider italic leading-none"><?php echo e($section['title'] ?? 'Technical Control Area'); ?></h2>
                                    <div class="w-1 h-1 rounded-full bg-slate-200"></div>
                                    <span class="text-[0.5rem] text-slate-400 font-bold uppercase tracking-widest opacity-60"><?php echo e(count($section['fields'])); ?> points</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-md bg-white border border-slate-100 flex items-center justify-center text-slate-300 transform transition-all duration-500"
                                     :class="isOpen ? 'rotate-180 border-orange-200 text-[#ff6900]' : ''">
                                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                                </div>
                            </div>
                        </div>

                        
                        <div x-show="isOpen" 
                             x-collapse
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="px-6 pb-6 md:px-8 md:pb-8 bg-white">
                             
                            <div class="flex flex-col gap-4 mt-4">
                                 <?php $__currentLoopData = $section['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <?php 
                                        $cfType = $cf['type'] ?? 'text'; 
                                        $fieldId = $cf['id'] ?? $index;
                                        $fieldLabel = $cf['label'] ?? $cf['name'] ?? 'Audit Field';
                                        $isRequired = !empty($cf['required']);
                                        $allowAttachment = !empty($cf['allow_attachment']);
                                        $allowNotes = !empty($cf['allow_notes']);
                                        $options = $cf['options'] ?? [];
                                     ?>
                                     <div class="space-y-2 p-4 rounded-2xl border border-slate-100 bg-slate-50/20 hover:bg-white hover:border-orange-100 hover:shadow-sm transition-all group/field">
                                        <div class="flex items-center justify-between gap-3 mb-1">
                                            <label class="text-[0.65rem] text-slate-800 font-black uppercase tracking-widest italic flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover/field:bg-[#ff6900] transition-colors"></span>
                                                <?php echo e($fieldLabel); ?>

                                                <?php if($isRequired): ?>
                                                    <span class="text-[#ff6900] text-xs">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <?php if($allowAttachment): ?>
                                                <span class="text-[0.55rem] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">Photo Required</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        
                                        <div class="relative">
                                            <?php if($cfType === 'text'): ?>
                                                <input type="text" name="custom_field[<?php echo e($fieldId); ?>]" 
                                                       <?php echo e($isRequired ? 'required' : ''); ?>

                                                       placeholder="Tap to enter <?php echo e($fieldLabel); ?>..."
                                                       class="w-full h-11 bg-white border border-slate-200 px-4 rounded-xl shadow-sm font-bold text-sm text-[#031629] outline-none focus:border-[#ff6900] transition-all placeholder:text-slate-300">
                                            
                                            <?php elseif($cfType === 'textarea'): ?>
                                                <textarea name="custom_field[<?php echo e($fieldId); ?>]" rows="2" 
                                                       <?php echo e($isRequired ? 'required' : ''); ?>

                                                       placeholder="Explain observations..."
                                                       class="w-full bg-white border border-slate-200 px-4 py-3 rounded-xl shadow-sm font-bold text-sm text-[#031629] outline-none focus:border-[#ff6900] transition-all placeholder:text-slate-300"></textarea>
                                            
                                            <?php elseif($cfType === 'image'): ?>
                                                <div class="flex items-center gap-3 p-3 bg-white border border-dashed border-slate-300 rounded-xl shadow-sm hover:border-[#ff6900] transition-all cursor-pointer relative overflow-hidden">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-slate-400"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Capture or Upload</span>
                                                    <input type="file" name="custom_field_img[<?php echo e($fieldId); ?>]" 
                                                           accept="image/*"
                                                           <?php echo e($isRequired ? 'required' : ''); ?>

                                                           class="absolute inset-0 opacity-0 cursor-pointer">
                                                </div>
                                            
                                            <?php elseif($cfType === 'checkbox'): ?>
                                                <label class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm cursor-pointer hover:border-emerald-200 transition-all select-none group/tap h-11">
                                                    <input type="checkbox" name="custom_field[<?php echo e($fieldId); ?>]" value="1"
                                                           class="w-5 h-5 rounded-lg accent-emerald-500">
                                                    <span class="text-sm font-black text-slate-600 group-hover/tap:text-slate-900 transition-colors">Condition OK / Passed</span>
                                                </label>

                                            <?php elseif($cfType === 'radio'): ?>
                                                <div class="p-2.5 bg-white border border-slate-200 rounded-xl shadow-sm flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optIndex => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="inline-flex items-center gap-3 px-3 py-2 bg-slate-50/50 rounded-lg text-xs font-bold text-slate-700 cursor-pointer hover:bg-[#ff6900]/5 hover:text-[#ff6900] hover:border-[#ff6900]/20 border border-transparent transition-all">
                                                            <input type="radio" name="custom_field[<?php echo e($fieldId); ?>]" value="<?php echo e($opt); ?>" class="w-3.5 h-3.5 accent-[#ff6900]">
                                                            <span><?php echo e($opt); ?></span>
                                                        </label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                            <?php elseif($cfType === 'dropdown'): ?>
                                                <div class="relative">
                                                    <select name="custom_field[<?php echo e($fieldId); ?>]" 
                                                            <?php echo e($isRequired ? 'required' : ''); ?>

                                                            class="w-full h-11 bg-white border border-slate-200 px-4 rounded-xl shadow-sm font-bold text-sm text-[#031629] outline-none focus:border-[#ff6900] transition-all appearance-none cursor-pointer">
                                                        <option value="">Choose Option...</option>
                                                        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($opt); ?>"><?php echo e($opt); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                                    </div>
                                                </div>

                                            <?php elseif($cfType === 'multi_checkbox'): ?>
                                                <div class="p-2.5 bg-white border border-slate-200 rounded-xl shadow-sm flex flex-wrap gap-2">
                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optIndex => $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <label class="inline-flex items-center gap-3 px-3 py-2 bg-slate-50/50 rounded-lg text-xs font-bold text-slate-700 cursor-pointer hover:bg-[#ff6900]/5 hover:text-[#ff6900] hover:border-[#ff6900]/20 border border-transparent transition-all">
                                                            <input type="checkbox" name="custom_field[<?php echo e($fieldId); ?>][]" value="<?php echo e($opt); ?>" class="w-3.5 h-3.5 rounded accent-[#ff6900]">
                                                            <span><?php echo e($opt); ?></span>
                                                        </label>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>

                                            <?php elseif($cfType === 'multi_select' || $cfType === 'dropdown_multi'): ?>
                                                
                                                <div x-data="{ 
                                                        open: false, 
                                                        selected: [],
                                                        options: <?php echo \Illuminate\Support\Js::from($options)->toHtml() ?>,
                                                        toggle(opt) {
                                                            if (this.selected.includes(opt)) {
                                                                this.selected = this.selected.filter(i => i !== opt);
                                                            } else {
                                                                this.selected.push(opt);
                                                            }
                                                        }
                                                     }" 
                                                     class="relative w-full">
                                                    
                                                    
                                                    <div @click="open = !open" 
                                                         class="min-h-[2.75rem] w-full bg-white border border-slate-200 px-3 py-1.5 rounded-xl shadow-sm cursor-pointer flex flex-wrap gap-1.5 items-center pr-10 hover:border-[#ff6900] transition-all">
                                                        
                                                        <template x-if="selected.length === 0">
                                                            <span class="text-xs font-bold text-slate-300 ml-1 italic">Choose multiple options...</span>
                                                        </template>
                                                        
                                                        <template x-for="item in selected" :key="item">
                                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-orange-50 text-[#ff6900] text-[0.6rem] font-black uppercase rounded-lg border border-orange-100/50">
                                                                <span x-text="item"></span>
                                                                <i @click.stop="toggle(item)" class="w-3 h-3 cursor-pointer opacity-60 hover:opacity-100">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                                                </i>
                                                            </span>
                                                        </template>

                                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m6 9 6 6 6-6"/></svg>
                                                        </div>
                                                    </div>

                                                    
                                                    <template x-for="item in selected" :key="item + '_val'">
                                                        <input type="hidden" name="custom_field[<?php echo e($fieldId); ?>][]" :value="item">
                                                    </template>

                                                    
                                                    <div x-show="open" 
                                                         @click.away="open = false"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                         class="absolute z-[999] left-0 right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar border-t-4 border-t-[#ff6900]">
                                                        
                                                        <template x-for="opt in options" :key="opt">
                                                            <div @click="toggle(opt)" 
                                                                 class="flex items-center justify-between px-4 py-2.5 rounded-xl cursor-pointer transition-all hover:bg-slate-50 group/opt"
                                                                 :class="selected.includes(opt) ? 'bg-orange-50/50' : ''">
                                                                <span class="text-xs font-bold text-slate-700 group-hover/opt:text-[#ff6900]" x-text="opt"></span>
                                                                <div x-show="selected.includes(opt)" class="text-[#ff6900]">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        
                                                        <div x-show="options.length === 0" class="py-10 text-center opacity-40">
                                                            <p class="text-[0.6rem] font-black uppercase tracking-widest">No options available</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <?php if($allowAttachment && $cfType !== 'image'): ?>
                                            <div class="mt-3 flex items-center gap-3">
                                                <div class="shrink-0 text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.51a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                                </div>
                                                <input type="file" name="custom_field_attach[<?php echo e($fieldId); ?>]" 
                                                       class="flex-1 text-[0.65rem] font-bold text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[0.6rem] file:font-black file:bg-slate-100 file:text-slate-600 hover:file:bg-slate-200 cursor-pointer">
                                            </div>
                                        <?php endif; ?>

                                        <?php if($allowNotes): ?>
                                            <div class="mt-3 relative group/note">
                                                <div class="absolute left-3 top-2.5 text-slate-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                                </div>
                                                <textarea name="custom_field_notes[<?php echo e($fieldId); ?>]" rows="1" 
                                                          placeholder="Add technical comments..."
                                                          class="w-full bg-slate-50/50 border border-dashed border-slate-200 pl-9 pr-4 py-2 rounded-lg font-bold text-xs text-[#031629] outline-none focus:border-slate-400 transition-all placeholder:text-slate-300"></textarea>
                                            </div>
                                        <?php endif; ?>
                                     </div>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\auction_app\resources\views/admin/inspections/create.blade.php ENDPATH**/ ?>