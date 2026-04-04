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
            $details = $task->car_details ?? []; 
            $isToday = ($details['inspection_date'] ?? '') == date('Y-m-d');
        @endphp
        <div class="group bg-white border border-slate-100 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row">
            <!-- Left: Visual Identity -->
            <div class="w-full md:w-[240px] relative overflow-hidden shrink-0 bg-[#1d293d]">
                <img src="{{ $details['image_url'] ?? asset('images/cars/car-silver.png') }}" 
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">
                
                <!-- Authentic Brand Logo: Centered Hero -->
                @php
                    $rawMake = strtolower($details['make'] ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    
                    // Specific mapping for common brands
                    $searchPaths = [
                        "images/brands/{$makeSlug}.svg",
                        "images/brands/{$makeSlug}.png",
                    ];
                    
                    if (str_contains($rawMake, 'mercedes')) {
                        $searchPaths[] = "images/brands/mercedes.svg";
                    }
                    if (str_contains($rawMake, 'rolls')) {
                        $searchPaths[] = "images/brands/rolls-royce.png";
                    }
                    
                    $finalLogo = null;
                    foreach ($searchPaths as $path) {
                        if (file_exists(public_path($path))) {
                            $finalLogo = $path;
                            break;
                        }
                    }
                @endphp
                <div class="absolute inset-0 flex items-center justify-center z-20 transition-transform duration-500 group-hover:scale-125">
                    <div class="w-24 h-24 rounded-full bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl flex items-center justify-center p-5">
                        @if($finalLogo)
                            <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain filter drop-shadow-md">
                        @else
                            <i data-lucide="car-front" class="w-12 h-12 text-[#ff6900] opacity-80"></i>
                        @endif
                    </div>
                </div>

                @if($isToday)
                <div class="absolute top-4 left-4 z-30">
                    <span class="bg-[#ff6900] text-white text-[0.6rem] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-orange-500/30 animate-pulse">
                        Today's Mission
                    </span>
                </div>
                @endif
                
                <div class="absolute bottom-4 left-4 right-4 z-30">
                    <div class="bg-[#1d293d]/60 backdrop-blur-md p-3 rounded-md border border-white/10 uppercase">
                        <div class="text-[0.55rem] text-white/50 font-bold uppercase tracking-widest mb-1">Asset Reference</div>
                        <div class="text-xs font-black text-white font-mono">#{{ strtoupper(substr($details['vin'] ?? 'MB-'.str_pad($task->id, 5, '0', STR_PAD_LEFT), -8)) }}</div>
                    </div>
                </div>
            </div>

            <!-- Right: Actionable Intel -->
            <div class="flex-1 p-8 flex flex-col justify-between gap-6">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-black text-[#031629] leading-none uppercase italic">
                                {{ $details['make'] ?? 'Unknown' }} <span class="text-[#ff6900]">{{ $details['model'] ?? 'Asset' }}</span>
                            </h3>
                            <p class="text-[0.7rem] font-bold text-slate-400 mt-2 uppercase tracking-wide italic">{{ $details['year'] ?? '' }} Production Portfolio</p>
                        </div>
                        <div class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-[#ff6900] transition-colors">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                        <div class="space-y-1">
                            <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Deployment Point</span>
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                <span class="text-[0.8rem] font-bold text-slate-700 truncate block">{{ $details['location'] ?? 'Not Specified' }}</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Schedule</span>
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                <span class="text-[0.8rem] font-bold text-[#031629] uppercase italic tracking-tighter">{{ $details['inspection_date'] ? \Carbon\Carbon::parse($details['inspection_date'])->format('d-m-Y') : 'TBD' }} @ {{ $details['inspection_time'] ?? 'TBD' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="tel:{{ $details['phone'] ?? '#' }}" 
                        class="flex-1 h-14 bg-slate-50 hover:bg-white hover:border-orange-500/30 border border-slate-100 rounded-lg flex items-center justify-center gap-3 text-[0.65rem] font-black text-[#031629] uppercase tracking-widest transition-all group/btn">
                        <i data-lucide="phone" class="w-4 h-4 text-[#ff6900] group-hover/btn:scale-110 transition-transform"></i>
                        Call Contact
                    </a>
                    
                    <button onclick="openMapModal('{{ addslashes($details['location'] ?? 'Dubai') }}')"
                        class="h-14 w-14 shrink-0 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#ff6900] hover:border-orange-500/30 transition-all active:scale-95">
                        <i data-lucide="navigation" class="w-5 h-5"></i>
                    </button>

                    <a href="{{ route('admin.inspections.create', ['car_id' => $task->car_id, 'lead_id' => $task->id]) }}" 
                        class="flex-1 h-14 bg-[#1d293d] hover:bg-orange-600 rounded-lg flex items-center justify-center gap-3 text-white text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-900/10 transition-all hover:scale-[1.02]">
                        <i data-lucide="zap" class="w-4 h-4 text-orange-400"></i>
                         Begin Audit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-32 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center justify-center gap-8 text-center px-8">
            <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center">
                <i data-lucide="ghost" class="w-12 h-12 text-slate-200"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-[#031629] uppercase italic leading-none">Zero Operations</h3>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-4">Current Deployment Queue is Empty</p>
            </div>
            <a href="{{ route('admin.leads.index') }}" class="px-8 py-4 bg-[#1d293d] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-full hover:bg-orange-600 transition-all">
                Assign New Tasks
            </a>
        </div>
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

