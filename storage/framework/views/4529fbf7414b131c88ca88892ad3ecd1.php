<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'show'    => 'opened',
    'title'   => 'Item',
    'subtitle' => 'Fill the info to save',
    'icon'     => 'settings',
    'size'    => 'max-w-4xl',
    'submit'  => 'submit()',
    'loading' => 'loading',
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
    'show'    => 'opened',
    'title'   => 'Item',
    'subtitle' => 'Fill the info to save',
    'icon'     => 'settings',
    'size'    => 'max-w-4xl',
    'submit'  => 'submit()',
    'loading' => 'loading',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>



<div x-show="<?php echo e($show); ?>" 
     x-cloak 
     class="fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md transition-all duration-500"
     @keydown.escape.window="<?php echo e($show); ?> = false">
    
    <div class="bg-[#e7e7e7] w-full <?php echo e($size); ?> max-h-[90vh] rounded-lg shadow-[0_40px_100px_-20px_rgba(0,0,0,0.15)] border border-slate-200 overflow-hidden flex flex-col animate-in zoom-in-95 duration-300"
         @click.away="<?php echo e($show); ?> = false">
        
        <!-- Header: Slate-50 (Official) -->
        <div class="bg-slate-50 px-10 py-8 border-b border-slate-200 flex items-center justify-between shrink-0 relative">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 bg-white border border-slate-200 rounded-md flex items-center justify-center shadow-sm">
                    <i data-lucide="<?php echo e($icon); ?>" class="text-[#ff6900] w-7 h-7"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight italic" x-text="isEdit ? 'Edit <?php echo e($title); ?>' : 'Add <?php echo e($title); ?>'"></h3>
                    <p class="text-[0.6rem] text-slate-500 font-extrabold uppercase tracking-[0.3em] mt-1"><?php echo e($subtitle); ?></p>
                </div>
            </div>
            <button @click="<?php echo e($show); ?> = false" class="w-12 h-12 rounded-md bg-white hover:bg-slate-100 border border-slate-200 flex items-center justify-center transition-all group shadow-sm">
                <i data-lucide="x" class="w-6 h-6 text-slate-400 group-hover:text-red-500 group-hover:rotate-90 transition-all duration-300"></i>
            </button>
        </div>

        <!-- Scrollable Body Container -->
        <div class="flex-1 overflow-y-auto">
            <form id="ajaxForm<?php echo e($id ?? ''); ?>" @submit.prevent="<?php echo e($submit); ?>" class="p-10">
                <div class="grid grid-cols-12 gap-10">
                    <?php echo e($slot); ?>

                </div>

                <!-- Footer: Standardized Slate-100 Border & Unified Buttons -->
                <div class="pt-10 mt-10 border-t border-slate-100 flex items-center justify-end gap-5">
                    <!-- Explicit Cancel -->
                    <button type="button" 
                            @click="<?php echo e($show); ?> = false" 
                            class="px-10 h-16 rounded-xl text-[0.75rem] font-black uppercase tracking-widest text-slate-500 hover:bg-white hover:text-red-500 hover:shadow-xl transition-all flex items-center gap-3 group italic">
                        <i data-lucide="circle-slash" class="w-5 h-5 text-slate-300 group-hover:text-red-300 transition-colors"></i>
                        Cancel
                    </button>
                    
                    <!-- Explicit Save -->
                    <button type="submit" 
                            :disabled="<?php echo e($loading); ?>" 
                            class="bg-[#031629] px-12 h-16 rounded-xl text-white font-black text-[0.75rem] uppercase tracking-[0.4em] shadow-2xl hover:bg-[#ff6900] active:scale-95 disabled:opacity-50 transition-all flex items-center gap-4">
                        <template x-if="!<?php echo e($loading); ?>">
                            <div class="flex items-center gap-4">
                                <i data-lucide="check" class="w-5 h-5 text-orange-500"></i>
                                <span x-text="isEdit ? 'Save Changes' : 'Save Info'"></span>
                            </div>
                        </template>
                        <template x-if="<?php echo e($loading); ?>">
                            <div class="flex items-center gap-4">
                                <div class="w-5 h-5 border-2 border-white/30 border-t-orange-500 rounded-full animate-spin"></div>
                                <span>Saving...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH D:\auction_app\resources\views/components/admin-modal.blade.php ENDPATH**/ ?>