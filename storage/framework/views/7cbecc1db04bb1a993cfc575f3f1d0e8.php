<?php $__env->startSection('title', 'Field Tasks'); ?>
<?php $__env->startSection('page_title', 'Field Tasks'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-2 space-y-8 pb-20">
    <!-- Sleek Minimalist Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-10 border-b border-slate-100">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl shadow-[#031629]/20 transform rotate-3">
                    <i data-lucide="compass" class="w-7 h-7 text-[#ff6900]"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-lg bg-emerald-500 border-4 border-[#f8fafc] animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">Field <span class="text-[#ff6900]">Missions</span></h1>
                <div class="flex items-center gap-3 mt-4">
                     <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80">Deployment Tracking Architecture</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-10">
            <div class="flex items-baseline gap-3">
                <span class="text-3xl font-black text-[#031629] tabular-nums tracking-tighter"><?php echo e(count($tasks)); ?></span>
                <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] italic">Open Pool</span>
            </div>
            <div class="h-8 w-px bg-slate-200"></div>
            <div class="flex items-baseline gap-3">
                <span class="text-3xl font-black text-[#ff6900] tabular-nums tracking-tighter"><?php echo e(count($tasks->where('inspection_date', date('Y-m-d'))) ?: '0'); ?></span>
                <span class="text-[0.65rem] font-black text-orange-400 uppercase tracking-[0.2em] italic">Active Today</span>
            </div>
        </div>
    </div>

    <!-- Task Feed -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php 
                $isToday = ($task->car_details['inspection_date'] ?? '') == date('Y-m-d');
            ?>
            <?php if (isset($component)) { $__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-mission-card','data' => ['task' => $task,'isToday' => $isToday]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-mission-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['task' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($task),'isToday' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isToday)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a)): ?>
<?php $attributes = $__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a; ?>
<?php unset($__attributesOriginalaeefeaeeb49c692b275f9bf5b5113e0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a)): ?>
<?php $component = $__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a; ?>
<?php unset($__componentOriginalaeefeaeeb49c692b275f9bf5b5113e0a); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php if (isset($component)) { $__componentOriginal65234af813d4686e126cb0021011df06 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65234af813d4686e126cb0021011df06 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-empty-state','data' => ['title' => 'Zero Operations','subtitle' => 'Current Deployment Queue is Empty','icon' => 'ghost']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-empty-state'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Zero Operations','subtitle' => 'Current Deployment Queue is Empty','icon' => 'ghost']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $attributes = $__attributesOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__attributesOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65234af813d4686e126cb0021011df06)): ?>
<?php $component = $__componentOriginal65234af813d4686e126cb0021011df06; ?>
<?php unset($__componentOriginal65234af813d4686e126cb0021011df06); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Fix #9: Location Modal -->
<div id="mapModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/50 backdrop-blur-xl p-4 transition-all duration-500">
    <div class="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden animate-in zoom-in-95 duration-300">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center">
                    <i data-lucide="map" class="w-6 h-6 text-[#ff6900]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-[#031629] uppercase italic leading-none">Intelligence <span class="text-[#ff6900]">Location</span></h3>
                    <p id="modalAddress" class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-widest mt-2">Checking coordinates...</p>
                </div>
            </div>
            <button onclick="closeMapModal()" class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="modalMapContainer" class="h-[500px] w-full bg-slate-100 relative">
            <!-- Map will load here -->
        </div>
    </div>
</div>

<script>
    function openMapModal(address) {
        const modal = document.getElementById('mapModal');
        const container = document.getElementById('modalMapContainer');
        const addressEl = document.getElementById('modalAddress');
        
        addressEl.innerText = address;
        modal.classList.remove('hidden');
        
        const googleKey = '<?php echo e(config('services.google_maps.key')); ?>'; // Or use window variable
        
        container.innerHTML = `<iframe width="100%" height="100%" frameborder="0" style="border:0" 
            src="https://www.google.com/maps/embed/v1/place?key=${googleKey}&q=${encodeURIComponent(address)}" allowfullscreen></iframe>`;
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
        document.getElementById('modalMapContainer').innerHTML = '';
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/inspections/tasks.blade.php ENDPATH**/ ?>