@extends('admin.layout')

@section('title', 'Inspections Calendar')
@section('page_title', 'Inspections Calendar')

@section('content')
<div class="px-1 flex flex-col gap-8">
    <!-- Page Header -->
    <div class="px-1 mb-6">
        <div class="flex items-center gap-4">
            <i data-lucide="calendar" class="w-8 h-8 text-[#ff6900]"></i>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Inspections Calendar</h1>
                <p class="text-slate-500 text-sm mt-1">Schedule and manage vehicle inspection appointments</p>
            </div>
        </div>
    </div>

    <!-- Action Hub -->
    <div class="flex justify-end items-center gap-2">
        <button class="px-5 py-2.5 bg-white border border-slate-200 rounded-md text-[0.65rem] font-bold uppercase tracking-widest text-slate-500 hover:bg-slate-50 transition-all">Export Report</button>
        <button class="px-5 py-2.5 bg-[#ff4605] text-white rounded-md text-[0.65rem] font-bold uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all">Manual Entry</button>
    </div>

    <div class="grid grid-cols-12 gap-8">
        <!-- Main: Temporal Grid -->
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-lg shadow-xl shadow-slate-100/50 border border-slate-100 p-8">
                 <div id="calendar" class="min-h-[750px]"></div>
            </div>
        </div>

        <!-- Sidebar: Today's Matrix Flow -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <div class="bg-white rounded-lg shadow-xl shadow-slate-100/50 border border-slate-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Today's Flow</h4>
                    <span class="px-3 py-1 bg-orange-50 text-[#ff6900] text-[0.6rem] font-bold rounded-lg uppercase italic">{{ date('d M') }}</span>
                </div>

                <div class="space-y-6 relative ml-4 border-l border-slate-100 pl-8">
                    @forelse($events->filter(fn($e) => \Carbon\Carbon::parse($e['start'])->isToday()) as $todayEvent)
                    <div class="relative group">
                        <div class="absolute -left-10 top-1.5 w-4 h-4 rounded-full border-2 border-white shadow-md transition-all group-hover:scale-125" style="background: {{ $todayEvent['backgroundColor'] }}"></div>
                        <div class="bg-slate-50 border border-slate-100 p-5 rounded-lg hover:bg-white hover:border-orange-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($todayEvent['start'])->format('h:i A') }}</div>
                            <h5 class="text-[0.85rem] font-medium text-slate-800 italic mt-1">{{ $todayEvent['title'] }}</h5>
                            <div class="flex items-center gap-2 mt-3">
                                <div class="w-6 h-6 rounded-lg bg-white border border-slate-100 flex items-center justify-center text-[0.55rem] font-bold text-slate-500 uppercase tracking-tighter">EX</div>
                                <span class="text-[0.65rem] text-slate-500 font-medium">{{ $todayEvent['extendedProps']['inspector'] }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 opacity-30 italic text-[0.7rem] uppercase font-bold tracking-[0.2em]">Operational silence</div>
                    @endforelse
                </div>
            </div>

            <!-- Additional Ops Context -->
            <div class="bg-[#1e293b] rounded-lg p-8 text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10">
                    <h4 class="text-[0.6rem] font-bold uppercase tracking-widest text-slate-400 mb-4">Operations Summary</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Total Deployments</span>
                            <span class="text-xs font-bold">{{ count($events) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Regional Coverage</span>
                            <span class="text-xs font-bold">100%</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 opacity-5">
                    <i data-lucide="shield-check" class="w-32 h-32 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hover Matrix Tooltip (Floating Engine) -->
<div id="hoverTooltip" class="hidden fixed pointer-events-none z-[160] transition-all duration-150">
    <div class="bg-white/95 backdrop-blur-xl border border-orange-100 shadow-2xl rounded-lg p-6 w-[260px] animate-in fade-in zoom-in-90">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-orange-50 rounded-md flex items-center justify-center text-[#ff6900]">
                <i data-lucide="car" class="w-5 h-5"></i>
            </div>
            <div>
                <div id="tipTitle" class="text-[0.9rem] font-medium text-slate-800 italic truncate">...</div>
                <div id="tipDate" class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest mt-0.5">...</div>
            </div>
        </div>
        <div class="space-y-2 border-t border-slate-50 pt-3">
             <div class="flex justify-between items-center text-[0.65rem] font-medium text-slate-500 italic">
                <span>Client:</span>
                <span id="tipClient" class="text-slate-800">...</span>
             </div>
             <div class="flex justify-between items-center text-[0.65rem] font-medium text-slate-500 italic">
                <span>Inspector:</span>
                <span id="tipInspector" class="text-[#ff4605] font-bold">...</span>
             </div>
             <div class="mt-4 p-2 bg-slate-50 rounded-lg text-[0.6rem] text-slate-400 font-medium italic overflow-hidden truncate">
                <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i> <span id="tipLoc">...</span>
             </div>
        </div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<style>
    .fc { font-family: inherit; }
    .fc .fc-toolbar { margin-bottom: 2rem !important; }
    .fc-toolbar-title { font-size: 1rem !important; font-weight: 800 !important; color: #1e293b !important; text-transform: uppercase; letter-spacing: 0.1em; }
    .fc-button { background: #f8fafc !important; border: 1px solid #f1f5f9 !important; color: #94a3b8 !important; font-weight: 800 !important; text-transform: uppercase !important; font-size: 0.6rem !important; letter-spacing: 0.1em !important; border-radius: 12px !important; padding: 10px 16px !important; transition: all 0.2s; }
    .fc-button-active { background: #ff4605 !important; border-color: #ff4605 !important; color: #fff !important; box-shadow: 0 10px 20px -5px rgba(255, 70, 5, 0.2); }
    .fc-button:hover:not(.fc-button-active) { background: #fff !important; border-color: #e2e8f0 !important; color: #1e293b !important; }
    
    .fc-daygrid-event { 
        border-radius: 9999px !important; 
        font-size: 0.7rem !important; 
        padding: 5px 12px 5px 12px !important; 
        font-weight: 700 !important; 
        border: none !important; 
        margin-top: 5px !important; 
        box-shadow: 0 4px 12px -2px rgba(0,0,0,0.1) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        color: #fff !important;
    }
    .fc-daygrid-event:hover {
        transform: scale(1.03) !important;
        box-shadow: 0 15px 25px -5px rgba(0,0,0,0.15) !important;
        z-index: 100 !important;
    }
    .fc-event-main { display: flex; align-items: center; justify-content: center; }
    .fc-daygrid-event-dot { display: none !important; }
    .fc-day-today { background: rgba(255, 70, 5, 0.02) !important; }
</style>

<script>
    function closeAuditModal() { document.getElementById('eventModal').classList.add('hidden'); }

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        const tooltip = document.getElementById('hoverTooltip');
        const eventModal = document.getElementById('eventModal');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: @json($events),
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                document.getElementById('modalTitle').innerText = event.title;
                document.getElementById('modalClient').innerText = props.client;
                document.getElementById('modalPhone').innerText = props.phone;
                document.getElementById('modalLoc').innerText = props.location;
                document.getElementById('modalInspector').innerText = props.inspector;
                document.getElementById('modalAsset').innerText = `${props.year} ${props.make} ${props.model}`;
                document.getElementById('modalVin').innerText = props.vin;
                document.getElementById('modalMileage').innerText = props.mileage + ' KM';
                document.getElementById('modalDateTime').innerText = event.start.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                const btn = document.getElementById('modalActionBtn');
                btn.href = `/admin/inspections/create?lead_id=${props.lead_id}`;

                eventModal.classList.remove('hidden');
            },
            eventMouseEnter: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                document.getElementById('tipTitle').innerText = event.title;
                document.getElementById('tipDate').innerText = event.start.toLocaleDateString('en-GB') + ' ' + event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                document.getElementById('tipClient').innerText = props.client;
                document.getElementById('tipInspector').innerText = props.inspector;
                document.getElementById('tipLoc').innerText = props.location;

                tooltip.classList.remove('hidden');
                
                const rect = info.el.getBoundingClientRect();
                tooltip.style.left = (rect.right + 10) + 'px';
                tooltip.style.top = (rect.top - 10) + 'px';
            },
            eventMouseLeave: function() {
                tooltip.classList.add('hidden');
            },
            eventDidMount: function(info) {
                const color = info.event.backgroundColor;
                if(color) {
                    info.el.style.backgroundColor = color;
                    info.el.style.borderLeft = `4px solid ${info.event.extendedProps.borderColor || 'rgba(0,0,0,0.1)'}`;
                }
            }
        });
        calendar.render();
    });

    function closeEventModal() { document.getElementById('eventModal').classList.add('hidden'); }
</script>

<!-- Fix #7: Event Detail Modal -->
<div id="eventModal" class="hidden fixed inset-0 z-[170] flex items-center justify-center bg-[#1d293d]/50 backdrop-blur-xl p-4 transition-all duration-300">
    <div class="bg-[#f8fafc] w-full max-w-xl rounded-[2.5rem] shadow-2xl border border-white/20 overflow-hidden animate-in zoom-in-95 duration-300">
        <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-white">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-6 h-6 text-[#ff6900]"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-xl font-black text-[#031629] uppercase italic leading-none">...</h3>
                    <p id="modalDateTime" class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-widest mt-2">...</p>
                </div>
            </div>
            <button onclick="closeEventModal()" class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div class="p-10 space-y-8">
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-1">
                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest block">Client Identity</span>
                    <p id="modalClient" class="text-sm font-black text-slate-900 italic">...</p>
                    <p id="modalPhone" class="text-[0.7rem] font-mono text-slate-500">...</p>
                </div>
                <div class="space-y-1">
                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest block">Assigned Auditor</span>
                    <p id="modalInspector" class="text-sm font-black text-[#ff6900] italic">...</p>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-3xl p-6 space-y-4 shadow-sm">
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-slate-400 uppercase tracking-tighter">Target Asset</span>
                    <span id="modalAsset" class="font-black text-slate-900 italic uppercase">...</span>
                </div>
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-4">
                    <span class="font-bold text-slate-400 uppercase tracking-tighter">VIN Reference</span>
                    <span id="modalVin" class="font-mono text-slate-700 bg-slate-50 px-2 py-0.5 rounded">...</span>
                </div>
                <div class="flex justify-between items-center text-xs border-t border-slate-50 pt-4">
                    <span class="font-bold text-slate-400 uppercase tracking-tighter">Odometer Display</span>
                    <span id="modalMileage" class="font-black text-emerald-600 italic">...</span>
                </div>
            </div>

            {{-- Location Display --}}
            <div class="flex items-center gap-3 p-4 bg-orange-50/50 border border-orange-100 rounded-2xl">
                <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6900] shrink-0"></i>
                <span id="modalLoc" class="text-[0.7rem] font-bold text-slate-700 italic"></span>
            </div>

            {{-- ── Schedule Inputs ────────────────────────── --}}
            <div class="bg-slate-50/80 border border-slate-100 rounded-3xl p-6 space-y-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-[#1d293d] flex items-center justify-center shrink-0">
                        <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                    </div>
                    <span class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest italic">Schedule Override / Confirm</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Date Picker --}}
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Confirmed Date</label>
                        <div class="relative">
                            <i data-lucide="calendar" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#ff6900] pointer-events-none"></i>
                            <input type="text" id="cal_sched_date" 
                                   class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl pl-11 pr-4 text-[0.85rem] font-bold text-slate-700 bazar-date cursor-pointer outline-none focus:border-orange-400 transition-all shadow-sm"
                                   placeholder="Select date...">
                        </div>
                    </div>

                    {{-- Time Picker --}}
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Verified Time Slot</label>
                        <div class="relative">
                            <i data-lucide="clock" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#ff6900] pointer-events-none"></i>
                            <input type="text" id="cal_sched_time"
                                   class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl pl-11 pr-4 text-[0.85rem] font-bold text-slate-700 bazar-time cursor-pointer outline-none focus:border-orange-400 transition-all shadow-sm"
                                   placeholder="Select time...">
                        </div>
                    </div>
                </div>

                {{-- Location Field--}}
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Inspection Location</label>
                    <div class="relative">
                        <i data-lucide="map-pin" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#ff6900] pointer-events-none"></i>
                        <input type="text" id="cal_location"
                               class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl pl-11 pr-4 text-[0.85rem] font-bold text-slate-700 outline-none focus:border-orange-400 transition-all shadow-sm"
                               placeholder="Override or confirm location...">
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-4">
                <a id="modalActionBtn" href="#" class="flex-1 bg-[#1d293d] text-white h-16 rounded-2xl flex items-center justify-center gap-3 text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-black transition-all">
                    <i data-lucide="zap" class="w-4 h-4 text-[#ff6900]"></i>
                    Proceed to Audit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

