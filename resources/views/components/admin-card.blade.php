@props([
    'title' => null,
    'icon'  => null,
    'variant' => 'glass'
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-100 shadow-sm p-8 transition-all group']) }}>
    @if($title || $icon)
        <div class="flex items-center gap-3 mb-8 border-b border-slate-100/50 pb-5">
            @if($icon)
                <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-[#ff6900] shadow-lg shadow-slate-200">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                </div>
            @endif
            @if($title)
                <h4 class="text-[0.7rem] font-black uppercase text-slate-800 tracking-[0.3em]">{{ $title }}</h4>
            @endif
        </div>
    @endif
    
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
