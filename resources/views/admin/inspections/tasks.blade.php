@extends('admin.layout')

@section('title', 'Field Tasks')
@section('page_title', 'Field Tasks')

@section('content')
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
                <span class="text-3xl font-black text-[#031629] tabular-nums tracking-tighter">{{ count($tasks) }}</span>
                <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-[0.2em] italic">Open Pool</span>
            </div>
            <div class="h-8 w-px bg-slate-200"></div>
            <div class="flex items-baseline gap-3">
                <span class="text-3xl font-black text-[#ff6900] tabular-nums tracking-tighter">{{ count($tasks->where('inspection_date', date('Y-m-d'))) ?: '0' }}</span>
                <span class="text-[0.65rem] font-black text-orange-400 uppercase tracking-[0.2em] italic">Active Today</span>
            </div>
        </div>
    </div>

    <!-- Task Feed -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @forelse($tasks as $task)
            @php 
                $isToday = ($task->car_details['inspection_date'] ?? '') == date('Y-m-d');
            @endphp
            <x-admin-mission-card :task="$task" :isToday="$isToday" />
        @empty
            <x-admin-empty-state 
                title="Zero Operations" 
                subtitle="Current Deployment Queue is Empty" 
                icon="ghost" />
        @endforelse
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
        
        const googleKey = '{{ config('services.google_maps.key') }}'; // Or use window variable
        
        container.innerHTML = `<iframe width="100%" height="100%" frameborder="0" style="border:0" 
            src="https://www.google.com/maps/embed/v1/place?key=${googleKey}&q=${encodeURIComponent(address)}" allowfullscreen></iframe>`;
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
        document.getElementById('modalMapContainer').innerHTML = '';
    }
</script>
@endsection

