@props([
    'dateName' => 'inspection_date',
    'timeName' => 'inspection_time',
    'dateId' => 'inspectionDateVal',
    'timeId' => 'inspectionTimeVal',
])

<div class="elite-picker-system space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- ── CUSTOM DATE PICKER ── --}}
        <div class="relative" id="datePicker">
            <input type="hidden" name="{{ $dateName }}" id="{{ $dateId }}">
            <button type="button" id="datePickerToggle"
                class="group w-full h-[52px] px-5 rounded-xl bg-slate-50 border-2 border-slate-100/50 hover:border-[#FF6900]/30 hover:bg-white flex items-center gap-3 text-left transition-all shadow-sm">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400 shrink-0 group-hover:text-[#FF6900] transition-colors"></i>
                <span id="datePickerLabel" class="text-[0.85rem] font-black text-slate-400 truncate italic">Select Date</span>
            </button>
            
            {{-- Calendar Dropdown --}}
            <div id="datePickerDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[500] rounded-xl overflow-hidden border border-slate-100 bg-white shadow-[0_30px_70px_-15px_rgba(15,23,42,0.25)] animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                    <button type="button" id="calPrev" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-400 hover:text-[#FF6900] transition-all">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <span id="calMonthYear" class="text-[0.75rem] font-black text-slate-800 uppercase tracking-[0.2em]"></span>
                    <button type="button" id="calNext" class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-400 hover:text-[#FF6900] transition-all">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="grid grid-cols-7 px-4 pt-3 pb-1">
                    @foreach(['S','M','T','W','T','F','S'] as $d)
                        <div class="text-center text-[0.6rem] font-black uppercase text-slate-300 py-1">{{ $d }}</div>
                    @endforeach
                </div>
                <div id="calDaysGrid" class="grid grid-cols-7 gap-1 px-4 pb-4"></div>
            </div>
        </div>

        {{-- ── CUSTOM TIME DRUM PICKER ── --}}
        <div class="relative" id="timePicker">
            <input type="hidden" name="{{ $timeName }}" id="{{ $timeId }}">
            <button type="button" id="timePickerToggle"
                class="group w-full h-[52px] px-5 rounded-xl bg-slate-50 border-2 border-slate-100/50 hover:border-[#FF6900]/30 hover:bg-white flex items-center gap-3 text-left transition-all shadow-sm">
                <i data-lucide="clock" class="w-4 h-4 text-slate-400 shrink-0 group-hover:text-[#FF6900] transition-colors"></i>
                <span id="timePickerLabel" class="text-[0.85rem] font-black text-slate-400 truncate italic">Time Slot</span>
            </button>

            {{-- Drum Picker Dropdown --}}
            <div id="timePickerDrawer" class="hidden absolute left-0 right-0 top-[calc(100%+0.75rem)] z-[500] rounded-xl border border-slate-100 bg-white shadow-[0_30px_70px_-15px_rgba(15,23,42,0.25)] animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                    <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">Pick Time</span>
                    <div class="flex gap-1.5">
                        <button type="button" id="amToggle_comp" class="px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all shadow-sm">AM</button>
                        <button type="button" id="pmToggle_comp" class="px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-slate-50 text-slate-400 transition-all">PM</button>
                    </div>
                </div>

                <div class="flex items-stretch gap-0 px-4 py-5">
                    <div class="flex-1 flex flex-col items-center">
                        <span class="text-[0.5rem] font-black uppercase tracking-widest text-slate-300 mb-3">Hour</span>
                        <button type="button" id="hrUp" class="w-10 h-8 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                            <i data-lucide="chevron-up" class="w-5 h-5"></i>
                        </button>
                        <div class="relative h-[90px] overflow-hidden w-full flex flex-col items-center justify-center">
                            <div id="hrPrev" class="text-[1rem] font-bold text-slate-200 leading-none py-1.5 text-center transition-all duration-300"></div>
                            <div id="hrCurrent" class="text-[1.8rem] font-black text-[#FF6900] leading-none py-2.5 px-6 bg-orange-50 rounded-2xl w-full text-center transition-all duration-300"></div>
                            <div id="hrNext" class="text-[1rem] font-bold text-slate-200 leading-none py-1.5 text-center transition-all duration-300"></div>
                        </div>
                        <button type="button" id="hrDown" class="w-10 h-8 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                            <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-center px-3">
                        <span class="text-3xl font-black text-slate-100">:</span>
                    </div>

                    <div class="flex-1 flex flex-col items-center">
                        <span class="text-[0.5rem] font-black uppercase tracking-widest text-slate-300 mb-3">Min</span>
                        <button type="button" id="minUp" class="w-10 h-8 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                            <i data-lucide="chevron-up" class="w-5 h-5"></i>
                        </button>
                        <div class="relative h-[90px] overflow-hidden w-full flex flex-col items-center justify-center">
                            <div id="minPrev" class="text-[1rem] font-bold text-slate-200 leading-none py-1.5 text-center"></div>
                            <div id="minCurrent" class="text-[1.8rem] font-black text-[#FF6900] leading-none py-2.5 px-6 bg-orange-50 rounded-2xl w-full text-center"></div>
                            <div id="minNext" class="text-[1rem] font-bold text-slate-200 leading-none py-1.5 text-center"></div>
                        </div>
                        <button type="button" id="minDown" class="w-10 h-8 flex items-center justify-center rounded-xl hover:bg-orange-50 text-slate-300 hover:text-[#FF6900] transition-all">
                            <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <button type="button" id="timeConfirm" class="w-full py-4 bg-[#FF6900] text-white rounded-2xl text-[0.7rem] font-black uppercase tracking-[0.2em] hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/20">
                        Confirm Appointment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function initElitePickers() {
        // ══════════════════════════════════════════
        // DATE PICKER ENGINE
        // ══════════════════════════════════════════
        (function() {
            const toggle     = document.getElementById('datePickerToggle');
            const drawer     = document.getElementById('datePickerDrawer');
            const label      = document.getElementById('datePickerLabel');
            const hiddenVal  = document.getElementById('{{ $dateId }}');
            const grid       = document.getElementById('calDaysGrid');
            const monthLabel = document.getElementById('calMonthYear');
            const btnPrev    = document.getElementById('calPrev');
            const btnNext    = document.getElementById('calNext');
            if (!toggle || !drawer) return;

            const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const today  = new Date(); today.setHours(0,0,0,0);
            let viewDate = new Date(today.getFullYear(), today.getMonth(), 1);
            let selected = null;

            function renderCalendar() {
                const yr  = viewDate.getFullYear();
                const mo  = viewDate.getMonth();
                monthLabel.textContent = MONTHS[mo] + ' ' + yr;
                grid.innerHTML = '';
                const firstDay = new Date(yr, mo, 1).getDay();
                const daysInMo = new Date(yr, mo + 1, 0).getDate();
                for (let i = 0; i < firstDay; i++) {
                    grid.appendChild(document.createElement('div'));
                }
                for (let d = 1; d <= daysInMo; d++) {
                    const date = new Date(yr, mo, d);
                    const btn  = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = d;
                    const isPast    = date < today;
                    const isToday   = date.getTime() === today.getTime();
                    const isSel     = selected && date.getTime() === selected.getTime();
                    btn.className = 'w-full aspect-square flex items-center justify-center rounded-xl text-[0.75rem] font-bold transition-all ';
                    if (isPast) {
                        btn.className += 'text-slate-100 cursor-not-allowed';
                        btn.disabled = true;
                    } else if (isSel) {
                        btn.className += 'bg-[#FF6900] text-white shadow-lg shadow-orange-500/20 font-black scale-105';
                    } else if (isToday) {
                        btn.className += 'ring-2 ring-[#FF6900]/30 text-[#FF6900] font-black hover:bg-orange-50';
                    } else {
                        btn.className += 'text-slate-600 hover:bg-orange-50 hover:text-[#FF6900]';
                    }
                    if (!isPast) {
                        btn.onclick = (e) => {
                            e.stopPropagation();
                            selected = date;
                            const iso = `${yr}-${String(mo+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                            if(hiddenVal) hiddenVal.value = iso;
                            if(label) label.textContent = date.toLocaleDateString('en-US', { weekday:'short', month:'short', day:'numeric' });
                            label.classList.remove('text-slate-400');
                            label.classList.add('text-slate-800');
                            toggle.classList.add('border-[#FF6900]/40');
                            drawer.classList.add('hidden');
                            renderCalendar();
                        };
                    }
                    grid.appendChild(btn);
                }
            }

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                document.getElementById('timePickerDrawer')?.classList.add('hidden');
                renderCalendar();
            });
            btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); viewDate.setMonth(viewDate.getMonth() - 1); renderCalendar(); });
            btnNext?.addEventListener('click', (e) => { e.stopPropagation(); viewDate.setMonth(viewDate.getMonth() + 1); renderCalendar(); });
            document.addEventListener('click', (e) => { if (!document.getElementById('datePicker').contains(e.target)) drawer?.classList.add('hidden'); });
            renderCalendar();
        })();

        // ══════════════════════════════════════════
        // TIME DRUM PICKER ENGINE
        // ══════════════════════════════════════════
        (function() {
            const toggle    = document.getElementById('timePickerToggle');
            const drawer    = document.getElementById('timePickerDrawer');
            const label     = document.getElementById('timePickerLabel');
            const hiddenVal = document.getElementById('{{ $timeId }}');
            if (!toggle || !drawer) return;

            const HOURS = [9,10,11,12,1,2,3,4,5,6,7,8];
            const MINUTES = Array.from({ length: 60 }, (_, i) => String(i).padStart(2, '0'));
            let hrIdx = 0, minIdx = 0, isPM = false;

            const hrPrev = document.getElementById('hrPrev'), hrCur = document.getElementById('hrCurrent'), hrNxt = document.getElementById('hrNext');
            const minPrev = document.getElementById('minPrev'), minCur = document.getElementById('minCurrent'), minNxt = document.getElementById('minNext');
            const amBtn = document.getElementById('amToggle_comp'), pmBtn = document.getElementById('pmToggle_comp'), confirmBtn = document.getElementById('timeConfirm');

            function renderDrums() {
                if(hrPrev) hrPrev.textContent = String(HOURS[(hrIdx - 1 + HOURS.length) % HOURS.length]).padStart(2,'0');
                if(hrCur) hrCur.textContent = String(HOURS[hrIdx]).padStart(2,'0');
                if(hrNxt) hrNxt.textContent = String(HOURS[(hrIdx + 1) % HOURS.length]).padStart(2,'0');
                if(minPrev) minPrev.textContent = MINUTES[(minIdx - 1 + MINUTES.length) % MINUTES.length];
                if(minCur) minCur.textContent = MINUTES[minIdx];
                if(minNxt) minNxt.textContent = MINUTES[(minIdx + 1) % MINUTES.length];
            }

            function setAMPM(pm) {
                isPM = pm;
                amBtn.className = !pm ? 'px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all shadow-sm' : 'px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-slate-50 text-slate-400 transition-all';
                pmBtn.className = pm ? 'px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-[#FF6900] text-white transition-all shadow-sm' : 'px-3 py-1.5 rounded-xl text-[0.6rem] font-black uppercase tracking-wider bg-slate-50 text-slate-400 transition-all';
            }

            document.getElementById('hrUp').onclick = (e) => { e.stopPropagation(); hrIdx = (hrIdx - 1 + HOURS.length) % HOURS.length; renderDrums(); };
            document.getElementById('hrDown').onclick = (e) => { e.stopPropagation(); hrIdx = (hrIdx + 1) % HOURS.length; renderDrums(); };
            document.getElementById('minUp').onclick = (e) => { e.stopPropagation(); minIdx = (minIdx - 1 + MINUTES.length) % MINUTES.length; renderDrums(); };
            document.getElementById('minDown').onclick = (e) => { e.stopPropagation(); minIdx = (minIdx + 1) % MINUTES.length; renderDrums(); };
            if(amBtn) amBtn.onclick = (e) => { e.stopPropagation(); setAMPM(false); };
            if(pmBtn) pmBtn.onclick = (e) => { e.stopPropagation(); setAMPM(true); };

            if(confirmBtn) confirmBtn.onclick = (e) => {
                e.stopPropagation();
                const timeStr = String(HOURS[hrIdx]).padStart(2,'0') + ':' + MINUTES[minIdx] + ' ' + (isPM ? 'PM' : 'AM');
                if(hiddenVal) hiddenVal.value = timeStr;
                if(label) {
                    label.textContent = timeStr;
                    label.classList.remove('text-slate-400');
                    label.classList.add('text-slate-800');
                }
                toggle.classList.add('border-[#FF6900]/40');
                drawer.classList.add('hidden');
            };

            toggle.onclick = (e) => {
                e.stopPropagation();
                drawer.classList.toggle('hidden');
                document.getElementById('datePickerDrawer')?.classList.add('hidden');
                renderDrums();
            };

            document.addEventListener('click', (e) => { if (!document.getElementById('timePicker').contains(e.target)) drawer?.classList.add('hidden'); });
            renderDrums();
        })();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initElitePickers);
    } else {
        initElitePickers();
    }
})();
</script>
