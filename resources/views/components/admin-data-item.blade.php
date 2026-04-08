@props([
    'number' => null,
    'title' => 'Sample Title',
    'subtitle' => null,
    'icon' => null
])

<div {{ $attributes->merge(['class' => 'bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/40 flex flex-wrap md:flex-nowrap items-center justify-between transition-all hover:bg-white/60 group shadow-sm hover:shadow-md duration-500']) }}>
    <div class="flex items-center gap-5">
        @if($number)
            <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-[#ff6900] font-black italic text-xl shadow-lg shadow-slate-200 group-hover:scale-110 transition-transform duration-500">
                {{ $number }}
            </div>
        @elseif($icon)
             <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-[#ff6900] transition-colors duration-500 border border-slate-100">
                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
            </div>
        @endif
        
        <div class="min-w-0">
            <h5 class="font-black text-slate-800 uppercase text-[0.85rem] truncate">{{ $title }}</h5>
            @if($subtitle)
                <p class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-[0.1em] mt-1 italic">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <!-- Actions Area -->
    <div class="flex items-center gap-3 mt-4 md:mt-0 ml-auto md:ml-0">
        {{ $slot }}
    </div>
</div>
