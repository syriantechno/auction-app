@props([
    'label' => 'Total Units',
    'value' => '0',
    'icon'  => null,
    'color' => 'slate',
    'alpineValue' => null
])

@php
$colorMap = [
    'slate'   => ['text' => 'text-[#031629]', 'accent' => 'bg-slate-300',   'glow' => 'bg-slate-100',   'icon' => 'text-slate-400'],
    'orange'  => ['text' => 'text-[#ff6900]', 'accent' => 'bg-[#ff6900]',   'glow' => 'bg-orange-50',   'icon' => 'text-[#ff6900]'],
    'emerald' => ['text' => 'text-emerald-500', 'accent' => 'bg-emerald-500', 'glow' => 'bg-emerald-50', 'icon' => 'text-emerald-500'],
    'rose'    => ['text' => 'text-rose-500',    'accent' => 'bg-rose-500',    'glow' => 'bg-rose-50',    'icon' => 'text-rose-500'],
    'amber'   => ['text' => 'text-amber-500',   'accent' => 'bg-amber-500',   'glow' => 'bg-amber-50',   'icon' => 'text-amber-500'],
];
$c = $colorMap[$color] ?? $colorMap['slate'];
@endphp

<div {{ $attributes->merge(['class' => 'group relative bg-slate-50 rounded-3xl p-6 border border-slate-100/50 shadow-sm transition-all duration-500 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1 overflow-hidden']) }}>
    
    <!-- Accent Bar -->
    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $c['accent'] }} opacity-20 group-hover:opacity-100 transition-all duration-700"></div>

    <!-- Background Glow Icon (Faded) -->
    <div class="absolute -right-6 -bottom-6 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity duration-700">
        <i data-lucide="{{ $icon ?? 'box' }}" class="w-32 h-32 rotate-12"></i>
    </div>

    <!-- Header Section -->
    <div class="flex items-start justify-between mb-6 relative z-10">
        <div>
            <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.25em] mb-1 italic opacity-80 group-hover:opacity-100 transition-opacity">
                {{ $label }}
            </p>
            <div class="w-8 h-[2px] bg-slate-200 group-hover:w-12 group-hover:{{ $c['accent'] }} transition-all duration-500"></div>
        </div>
        
        @if($icon)
            <div class="w-12 h-12 rounded-2xl {{ $c['glow'] }} flex items-center justify-center shadow-sm border border-white transition-all duration-700 group-hover:rotate-[360deg] group-hover:scale-110">
                <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $c['icon'] }}"></i>
            </div>
        @endif
    </div>

    <!-- Value Section -->
    <div class="relative z-10">
        <h3 class="text-4xl font-black {{ $c['text'] }} italic leading-none tracking-tighter transition-transform duration-500 group-hover:translate-x-1" 
            @if($alpineValue) x-text="{{ $alpineValue }}" @endif>
            {{ $value }}
        </h3>
        
        <div class="mt-4 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full {{ $c['accent'] }} animate-pulse"></span>
            <span class="text-[0.5rem] font-bold text-slate-400 uppercase tracking-widest italic">Operational Metric Ready</span>
        </div>
    </div>
</div>
