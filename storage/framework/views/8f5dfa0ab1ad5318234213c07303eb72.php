<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'dateName' => 'date',
    'label' => 'Select Date',
    'dateId' => null
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
    'dateName' => 'date',
    'label' => 'Select Date',
    'dateId' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $dateId = $dateId ?? 'date_' . uniqid();
?>

<div class="elite-date-component space-y-2">
    <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block">
        <?php echo e($label); ?>

    </label>

    <div class="relative" id="datePicker_<?php echo e($dateId); ?>">
        <input type="hidden" name="<?php echo e($dateName); ?>" id="<?php echo e($dateId); ?>">
        <button type="button" id="datePickerToggle_<?php echo e($dateId); ?>"
            class="group w-full h-11 px-5 rounded-xl bg-slate-50 border-2 border-slate-100 hover:border-[#ff6900]/30 hover:bg-white flex items-center gap-3 text-left transition-all shadow-sm">
            <i data-lucide="calendar" class="w-4 h-4 text-slate-400 shrink-0 group-hover:text-[#ff6900] transition-colors"></i>
            <span id="datePickerLabel_<?php echo e($dateId); ?>" class="text-[0.85rem] font-black text-slate-400 truncate italic"><?php echo e($label); ?></span>
            <i data-lucide="chevron-down" class="w-4 h-4 ml-auto text-slate-300"></i>
        </button>
        
        
        <div id="datePickerDrawer_<?php echo e($dateId); ?>" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[9999] rounded-xl overflow-hidden border border-slate-100 bg-white shadow-[0_30px_70px_-15px_rgba(15,23,42,0.25)] animate-in fade-in zoom-in-95 duration-200">
            
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-50 bg-slate-50/50 gap-2">
                <select id="calMonth_<?php echo e($dateId); ?>" class="flex-1 h-9 bg-white border border-slate-200 rounded-lg text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/50 cursor-pointer">
                    <?php $__currentLoopData = ['January','February','March','April','May','June','July','August','September','October','November','December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($i); ?>"><?php echo e($month); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select id="calYear_<?php echo e($dateId); ?>" class="w-24 h-9 bg-white border border-slate-200 rounded-lg text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/50 cursor-pointer">
                    <?php for($y = date('Y') - 50; $y <= date('Y') + 10; $y++): ?>
                        <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="grid grid-cols-7 px-4 pt-3 pb-1">
                <?php $__currentLoopData = ['S','M','T','W','T','F','S']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-center text-[0.6rem] font-black uppercase text-slate-300 py-1"><?php echo e($d); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div id="calDaysGrid_<?php echo e($dateId); ?>" class="grid grid-cols-7 gap-1 px-4 pb-4"></div>
            
            
            <div class="px-4 pb-4">
                <button type="button" id="calToday_<?php echo e($dateId); ?>" class="w-full py-2.5 bg-slate-100 hover:bg-[#ff6900] hover:text-white rounded-xl text-[0.7rem] font-black uppercase tracking-wider transition-all text-slate-600">
                    Today
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const dateId = '<?php echo e($dateId); ?>';
        const toggle   = document.getElementById('datePickerToggle_' + dateId);
        const drawer   = document.getElementById('datePickerDrawer_' + dateId);
        const label    = document.getElementById('datePickerLabel_' + dateId);
        const hiddenVal= document.getElementById(dateId);
        const grid     = document.getElementById('calDaysGrid_' + dateId);
        const selMonth = document.getElementById('calMonth_' + dateId);
        const selYear  = document.getElementById('calYear_' + dateId);
        const btnToday = document.getElementById('calToday_' + dateId);
        
        if (!toggle || !drawer) return;

        const today = new Date();
        let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
        let selected = null;

        function updateSelectors() {
            selMonth.value = viewDate.getMonth();
            selYear.value = viewDate.getFullYear();
        }

        function renderCalendar() {
            updateSelectors();

            grid.innerHTML = '';
            const yr  = viewDate.getFullYear();
            const mo  = viewDate.getMonth();
            const firstDay = new Date(yr, mo, 1).getDay();
            const daysInMonth = new Date(yr, mo + 1, 0).getDate();

            // Empty slots
            for (let i = 0; i < firstDay; i++) {
                const empty = document.createElement('div');
                grid.appendChild(empty);
            }

            // Days
            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'aspect-square flex items-center justify-center rounded-xl text-[0.85rem] font-bold transition-all hover:bg-orange-50 hover:text-[#ff6900]';
                btn.textContent = d;

                const dateStr = yr + '-' + String(mo + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');

                // Check if today
                if (yr === today.getFullYear() && mo === today.getMonth() && d === today.getDate()) {
                    btn.classList.add('ring-2', 'ring-[#ff6900]/30', 'text-[#ff6900]');
                } else {
                    btn.classList.add('text-slate-600');
                }

                // Check if selected
                if (selected === dateStr) {
                    btn.classList.remove('text-slate-600', 'ring-2', 'ring-[#ff6900]/30');
                    btn.classList.add('bg-[#ff6900]', 'text-white');
                }

                btn.onclick = function(e) {
                    e.stopPropagation();
                    selected = dateStr;
                    hiddenVal.value = dateStr;
                    label.textContent = new Date(dateStr).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
                    label.classList.remove('text-slate-400');
                    label.classList.add('text-slate-800');
                    toggle.classList.add('border-[#ff6900]/40');
                    toggle.classList.remove('border-slate-100');
                    drawer.classList.add('hidden');
                    renderCalendar();
                };
                grid.appendChild(btn);
            }
        }

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            drawer.classList.toggle('hidden');
            renderCalendar();
        });

        selMonth?.addEventListener('change', function(e) {
            viewDate.setMonth(parseInt(e.target.value));
            renderCalendar();
        });

        selYear?.addEventListener('change', function(e) {
            viewDate.setFullYear(parseInt(e.target.value));
            renderCalendar();
        });
        
        btnToday?.addEventListener('click', function(e) {
            e.stopPropagation();
            const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
            selected = todayStr;
            hiddenVal.value = todayStr;
            label.textContent = new Date(todayStr).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
            label.classList.remove('text-slate-400');
            label.classList.add('text-slate-800');
            toggle.classList.add('border-[#ff6900]/40');
            drawer.classList.add('hidden');
            viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
            renderCalendar();
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('datePicker_' + dateId).contains(e.target)) {
                drawer?.classList.add('hidden');
            }
        });

        updateSelectors();
        renderCalendar();
    })();
</script>
<?php /**PATH D:\auction_app\resources\views/components/elite-date.blade.php ENDPATH**/ ?>