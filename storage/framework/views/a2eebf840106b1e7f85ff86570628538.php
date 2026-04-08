<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'task',
    'isToday' => false,
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $rawDetails = $task->car_details ?? null;
    $details = is_array($rawDetails) ? $rawDetails : [];
    if ($details === [] && is_string($rawDetails) && $rawDetails !== '') {
        $decoded = json_decode($rawDetails, true);
        $details = is_array($decoded) ? $decoded : [];
    }
    $rawMake = strtolower($details['make'] ?? 'generic');
    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
    if (str_contains($rawMake, 'mercedes')) $searchPaths[] = 'images/brands/mercedes.svg';
    if (str_contains($rawMake, 'rolls')) $searchPaths[] = 'images/brands/rolls-royce.png';
    
    $finalLogo = null;
    foreach ($searchPaths as $path) {
        if (file_exists(public_path($path))) { $finalLogo = $path; break; }
    }
    $vinRef = strtoupper(substr($details['vin'] ?? 'MB-' . str_pad($task->id, 5, '0', STR_PAD_LEFT), -8));
?>

<div <?php echo e($attributes->merge(['class' => 'group relative bg-white rounded-[1.25rem] border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] transition-all duration-700 overflow-hidden flex flex-col md:flex-row min-h-[220px]'])); ?>>
    
    
    <div class="relative w-full md:w-[35%] bg-[#1d293d] overflow-hidden flex items-center justify-center border-b md:border-b-0 md:border-r border-slate-50">
        
        <img src="<?php echo e($details['image_url'] ?? asset('images/cars/car-silver.png')); ?>" 
             class="absolute inset-0 w-full h-full object-cover object-center opacity-90 transition-transform duration-[3s] group-hover:scale-110" alt="">
        
        
        <div class="absolute inset-0 bg-gradient-to-t from-[#031629]/90 via-transparent to-transparent"></div>

        
        <div class="relative z-20 w-24 h-24 bg-white/95 backdrop-blur-md rounded-full flex items-center justify-center p-5 shadow-xl border-2 border-white transform transition-transform duration-500 group-hover:scale-105">
            <?php if($finalLogo): ?>
                <img src="<?php echo e(asset($finalLogo)); ?>" alt="" class="w-full h-full object-contain">
            <?php else: ?>
                <i data-lucide="car-front" class="w-full h-full text-slate-300"></i>
            <?php endif; ?>
        </div>

        
        <div class="absolute left-4 bottom-4 right-4 z-30">
            <div class="bg-[#031629]/60 backdrop-blur-xl border border-white/20 rounded-2xl px-5 py-3 shadow-2xl">
                <p class="text-[0.45rem] font-bold text-slate-400 uppercase tracking-[0.3em] leading-none mb-1.5 opacity-80">Asset Reference</p>
                <p class="text-[1.1rem] font-bold text-white italic tracking-tighter leading-none">#<?php echo e($vinRef); ?></p>
            </div>
        </div>
    </div>

    
    <div class="flex-1 p-6 lg:p-7 flex flex-col justify-between bg-white relative">
        
        <div class="absolute right-6 top-6 w-12 h-12 rounded-full border border-slate-50 flex items-center justify-center text-slate-200 group-hover:text-[#ff6900] group-hover:border-orange-50 transition-all duration-500">
            <i data-lucide="shield-check" class="w-6 h-6 opacity-[0.8]"></i>
        </div>

        <div>
            
            <div class="mb-5">
                <h3 class="text-2xl font-bold italic tracking-tighter text-[#031629] uppercase leading-none">
                    <?php echo e($details['make'] ?? 'Unknown'); ?> <span class="text-[#ff6900] font-medium"><?php echo e($details['model'] ?? 'Vehicle'); ?></span>
                </h3>
                <p class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mt-2 italic opacity-60">Specifications Data</p>
            </div>

            
            <div class="grid grid-cols-2 gap-x-6 gap-y-4 border-t border-slate-50 pt-5 mt-4">
                <div class="space-y-1.5">
                    <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest opacity-90">Deployment Point</p>
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6900]"></i>
                        <span class="text-[0.95rem] font-bold text-[#031629] italic truncate"><?php echo e($details['location'] ?? 'Sharjah'); ?></span>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <p class="text-[0.65rem] font-bold text-slate-500 uppercase tracking-widest opacity-90">Schedule</p>
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-[#ff6900]"></i>
                        <span class="text-[0.95rem] font-bold text-[#031629] italic">
                            <?php echo e($details['inspection_date'] ? \Carbon\Carbon::parse($details['inspection_date'])->format('d M') : 'TBD'); ?>

                            <span class="text-[#ff6900] mx-0.5">@</span><?php echo e($details['inspection_time'] ?? '1:20P'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex items-center gap-3 mt-8">
            <button onclick="window.location.href='tel:<?php echo e($details['phone'] ?? '#'); ?>'" 
                    class="flex-1 h-12 bg-slate-50 hover:bg-[#ff6900]/5 border border-slate-100 rounded-xl flex items-center justify-center gap-2.5 transition-all group/call">
                <i data-lucide="phone" class="w-4 h-4 text-[#ff6900] group-hover/call:scale-110"></i>
                <span class="text-[0.65rem] font-bold text-[#031629] uppercase tracking-widest">Call Contact</span>
            </button>

            <button onclick="openMapModal('<?php echo e(addslashes($details['location'] ?? 'Dubai')); ?>')"
                    class="w-12 h-12 bg-slate-50 hover:bg-orange-50 border border-slate-100 rounded-xl flex items-center justify-center transition-all group/map">
                <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6900] group-hover/map:scale-110 transition-transform"></i>
            </button>

            <button onclick="window.location.href='<?php echo e(route('admin.inspections.create', ['car_id' => $task->car_id, 'lead_id' => $task->id ?? 0])); ?>'"
                    class="flex-[1.2] h-12 bg-[#1d293d] hover:bg-[#031629] rounded-xl flex items-center justify-center gap-2.5 shadow-lg shadow-slate-200 transition-all group/audit">
                <i data-lucide="zap" class="w-4 h-4 text-[#ff6900] fill-current group-hover:scale-110"></i>
                <span class="text-[0.65rem] font-bold text-white uppercase tracking-widest">Begin Audit</span>
            </button>
        </div>
    </div>
</div>
<?php /**PATH F:\auction_app\resources\views/components/admin-mission-card.blade.php ENDPATH**/ ?>