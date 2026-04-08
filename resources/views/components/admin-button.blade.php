@props([
    'icon' => null,
    'variant' => 'primary'
])

@php
$variants = [
    'primary'   => 'bg-slate-900 hover:bg-[#ff6900] text-white shadow-slate-200/50',
    'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 shadow-transparent',
    'orange'    => 'bg-[#ff6900] hover:bg-slate-900 text-white shadow-orange-200/50',
    'red'       => 'bg-red-50 hover:bg-red-500 text-red-600 hover:text-white shadow-transparent',
    'outline'   => 'bg-transparent border-2 border-slate-100 hover:border-[#ff6900]/30 text-slate-600 shadow-transparent'
];
$vClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button {{ $attributes->merge(['class' => "group $vClass px-6 py-3.5 rounded-md font-black text-[0.65rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center justify-center gap-2.5 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="w-4 h-4 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-12"></i>
    @endif
    
    <span>{{ $slot }}</span>
</button>
