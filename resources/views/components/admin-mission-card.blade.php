@props([
    'task',
    'isToday' => false
])

@php 
    $details = $task->car_details ?? []; 
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row shadow-sm hover:translate-y-[-2px]']) }}>
    <!-- Left: Visual Identity -->
    <div class="w-full md:w-[180px] relative overflow-hidden shrink-0 bg-[#1d293d]">
        <img src="{{ $details['image_url'] ?? asset('images/cars/car-silver.png') }}" 
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">
        
        <!-- Authentic Brand Logo -->
        @php
            $rawMake = strtolower($details['make'] ?? 'generic');
            $makeSlug = \Illuminate\Support\Str::slug($rawMake);
            
            $searchPaths = [
                "images/brands/{$makeSlug}.svg",
                "images/brands/{$makeSlug}.png",
            ];
            
            if (str_contains($rawMake, 'mercedes')) { $searchPaths[] = "images/brands/mercedes.svg"; }
            if (str_contains($rawMake, 'rolls')) { $searchPaths[] = "images/brands/rolls-royce.png"; }
            
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
    <div class="flex-1 p-5 flex flex-col justify-between gap-4">
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
             <x-admin-button variant="outline" icon="phone" class="flex-1" onclick="window.location.href='tel:{{ $details['phone'] ?? '#' }}'">
                 Call Contact
             </x-admin-button>
            
            <x-admin-action icon="navigation" click="openMapModal('{{ addslashes($details['location'] ?? 'Dubai') }}')" title="Navigate" variant="slate" class="!h-14 !w-14" />

             <x-admin-button icon="zap" class="flex-1" onclick="window.location.href='{{ route('admin.inspections.create', ['car_id' => $task->car_id, 'lead_id' => $task->id ?? 0]) }}'">
                 Begin Audit
             </x-admin-button>
        </div>
    </div>
</div>
