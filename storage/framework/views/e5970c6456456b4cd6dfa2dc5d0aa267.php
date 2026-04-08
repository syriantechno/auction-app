<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'task',
    'isToday' => false,
    'status' => 'Pending'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'task',
    'isToday' => false,
    'status' => 'Pending'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php 
    $details = $task->car_details ?? []; 
    $statusColors = [
        'Pending' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600', 'dot' => 'bg-amber-500'],
        'Active'  => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500'],
        'Urgent'  => ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-600', 'dot' => 'bg-rose-500'],
    ];
    $s = $statusColors[$status] ?? $statusColors['Pending'];
?>

<div <?php echo e($attributes->merge(['class' => 'group bg-white border border-slate-100 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-[#ff6900]/10 transition-all duration-700 flex flex-col md:flex-row relative'])); ?>>
    
    <!-- Premium Glow Effect -->
    <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-amber-500 rounded-[2rem] blur opacity-0 group-hover:opacity-[0.03] transition-opacity duration-700"></div>

    <!-- Left: Asset Visual & Identity -->
    <div class="w-full md:w-[200px] relative overflow-hidden shrink-0 bg-[#031629]">
        <img src="<?php echo e($details['image_url'] ?? asset('images/cars/car-silver.png')); ?>" 
             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 brightness-110 saturate-125 opacity-60">
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#031629] via-transparent to-[#031629]/40 z-10"></div>

        <!-- Brand Icon / Logo Hub -->
        <div class="absolute inset-0 flex items-center justify-center z-20 transition-all duration-700 group-hover:bg-black/20">
            <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-2xl border border-white/20 shadow-2xl flex items-center justify-center p-4 transform transition-transform duration-700 group-hover:scale-110 group-hover:-rotate-3">
                <?php
                    $rawMake = strtolower($details['make'] ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $finalLogo = null;
                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                    foreach ($searchPaths as $path) { if (file_exists(public_path($path))) { $finalLogo = $path; break; } }
                ?>

                <?php if($finalLogo): ?>
                    <img src="<?php echo e(asset($finalLogo)); ?>" class="w-full h-full object-contain filter invert brightness-200">
                <?php else: ?>
                    <i data-lucide="shield-check" class="w-10 h-10 text-white/40"></i>
                <?php endif; ?>
            </div>
        </div>

        <?php if($isToday): ?>
        <div class="absolute top-5 left-5 z-30">
            <div class="flex items-center gap-2 bg-[#ff6900] text-white text-[0.6rem] font-black px-3.5 py-1.5 rounded-full uppercase tracking-[0.2em] shadow-lg shadow-orange-500/40">
                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                Live Mission
            </div>
        </div>
        <?php endif; ?>

        <!-- Floating VIN Badge -->
        <div class="absolute bottom-5 left-5 right-5 z-30">
            <div class="bg-white/5 backdrop-blur-xl p-3 rounded-xl border border-white/10 flex items-center justify-between group-hover:border-[#ff6900]/30 transition-colors">
                <div>
                    <div class="text-[0.45rem] text-white/40 font-black uppercase tracking-[0.3em] mb-1">Asset Serial</div>
                    <div class="text-[0.75rem] font-black text-white font-mono tracking-tighter"><?php echo e(strtoupper(substr($details['vin'] ?? 'MB-'.str_pad($task->id, 5, '0', STR_PAD_LEFT), -10))); ?></div>
                </div>
                <i data-lucide="cpu" class="w-4 h-4 text-white/20 group-hover:text-[#ff6900] transition-colors"></i>
            </div>
        </div>
    </div>

    <!-- Right: Operational Data & CTA -->
    <div class="flex-1 p-6 flex flex-col justify-between relative bg-slate-50">
        
        <!-- Header Info -->
        <div class="relative">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="<?php echo e($s['bg']); ?> <?php echo e($s['text']); ?> text-[0.55rem] font-black px-2.5 py-1 rounded-md uppercase tracking-[0.2em] flex items-center gap-2">
                             <span class="w-1 h-1 <?php echo e($s['dot']); ?> rounded-full"></span>
                             <?php echo e($status); ?>

                        </span>
                        <span class="bg-slate-50 text-slate-400 text-[0.55rem] font-black px-2.5 py-1 rounded-md uppercase tracking-[0.2em]">Priority: High</span>
                    </div>
                    <h3 class="text-3xl font-black text-[#031629] leading-none uppercase italic tracking-tighter group-hover:text-[#ff6900] transition-colors duration-500">
                        <?php echo e($details['make'] ?? 'Unknown'); ?> <span class="text-slate-300 group-hover:text-[#ff6900]/20 transition-colors uppercase not-italic"><?php echo e($details['model'] ?? 'Asset'); ?></span>
                    </h3>
                    <p class="text-[0.7rem] font-black text-slate-400 mt-4 uppercase tracking-[0.2em] flex items-center gap-2">
                        <span class="w-8 h-[2px] bg-slate-100 group-hover:w-12 group-hover:bg-[#ff6900]/20 transition-all"></span>
                        Quality Audit Phase v.<?php echo e($details['year'] ?? 'V4'); ?>

                    </p>
                </div>
                
                <button class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300 hover:text-[#ff6900] hover:bg-white hover:shadow-xl transition-all active:scale-90">
                    <i data-lucide="more-vertical" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-10 mt-10 pt-8 border-t border-slate-50 relative">
                <div class="absolute -top-[1px] left-0 w-16 h-[2px] bg-[#ff6900] transform translate-y-[0.5px]"></div>
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 group-hover:bg-orange-50 transition-colors">
                        <i data-lucide="navigation-2" class="w-5 h-5 text-slate-400 group-hover:text-[#ff6900]"></i>
                    </div>
                    <div>
                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-1">Deployment Zone</span>
                        <span class="text-[0.85rem] font-black text-slate-800 tracking-tight block truncate w-[140px] italic"><?php echo e($details['location'] ?? 'Global Hub'); ?></span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 group-hover:bg-orange-50 transition-colors">
                        <i data-lucide="calendar-check" class="w-5 h-5 text-slate-400 group-hover:text-[#ff6900]"></i>
                    </div>
                    <div>
                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-1">Launch Window</span>
                        <span class="text-[0.85rem] font-black text-[#ff6900] tracking-tight block italic uppercase">
                            <?php echo e($details['inspection_date'] ? \Carbon\Carbon::parse($details['inspection_date'])->format('D, d M') : 'TBD'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / CTA -->
        <div class="flex items-center gap-4 pt-4">
             <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['variant' => 'outline','icon' => 'phone','class' => 'flex-1 !h-[56px] !rounded-xl !text-[0.7rem] !font-black !uppercase !tracking-widest','onclick' => 'window.location.href=\'tel:'.e($details['phone'] ?? '#').'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','icon' => 'phone','class' => 'flex-1 !h-[56px] !rounded-xl !text-[0.7rem] !font-black !uppercase !tracking-widest','onclick' => 'window.location.href=\'tel:'.e($details['phone'] ?? '#').'\'']); ?>
                 Call Link
              <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
            
             <?php if (isset($component)) { $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-button','data' => ['icon' => 'zap','class' => 'flex-[1.5] !h-[56px] !rounded-xl !text-[0.7rem] !font-black !uppercase !tracking-widest !bg-[#031629] hover:!bg-[#ff6900] !border-none shadow-xl shadow-slate-200','onclick' => 'window.location.href=\''.e(route('admin.inspections.create', ['car_id' => $task->car_id, 'lead_id' => $task->id ?? 0])).'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'zap','class' => 'flex-[1.5] !h-[56px] !rounded-xl !text-[0.7rem] !font-black !uppercase !tracking-widest !bg-[#031629] hover:!bg-[#ff6900] !border-none shadow-xl shadow-slate-200','onclick' => 'window.location.href=\''.e(route('admin.inspections.create', ['car_id' => $task->car_id, 'lead_id' => $task->id ?? 0])).'\'']); ?>
                 Initialize Mission
              <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $attributes = $__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__attributesOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a)): ?>
<?php $component = $__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a; ?>
<?php unset($__componentOriginal4ff297b286d2dba35e6c7d18cf241e0a); ?>
<?php endif; ?>

             <?php if (isset($component)) { $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-action','data' => ['icon' => 'map','click' => 'openMapModal(\''.e(addslashes($details['location'] ?? 'Dubai')).'\')','title' => 'Map Intel','variant' => 'slate','class' => '!h-[56px] !w-[56px] !bg-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'map','click' => 'openMapModal(\''.e(addslashes($details['location'] ?? 'Dubai')).'\')','title' => 'Map Intel','variant' => 'slate','class' => '!h-[56px] !w-[56px] !bg-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $attributes = $__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__attributesOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e)): ?>
<?php $component = $__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e; ?>
<?php unset($__componentOriginal0b0faeaeb0fe8efbe159784d20e7958e); ?>
<?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/elite-mission-card.blade.php ENDPATH**/ ?>