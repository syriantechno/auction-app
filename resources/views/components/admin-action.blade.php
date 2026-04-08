@props([
    'icon' => null,
    'variant' => 'slate',
    'title' => '',
    'click' => '',
    'href' => null
])

@php
    $variants = [
        'slate' => [
            'btn' => 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-orange-500/40 hover:text-[#ff6900]',
            'glow' => 'group-hover:shadow-orange-500/10'
        ],
        'red' => [
            'btn' => 'bg-white border-slate-200 text-red-500 hover:bg-red-50 hover:border-red-500/40 hover:text-red-600',
            'glow' => 'group-hover:shadow-red-500/10'
        ],
        'emerald' => [
            'btn' => 'bg-white border-slate-200 text-emerald-600 hover:bg-emerald-50 hover:border-emerald-500/40 hover:text-emerald-700',
            'glow' => 'group-hover:shadow-emerald-500/10'
        ],
        'orange' => [
            'btn' => 'bg-white border-slate-200 text-[#FF6900] hover:bg-orange-50 hover:border-orange-500/40 hover:text-[#FF6900]',
            'glow' => 'group-hover:shadow-orange-500/10'
        ]
    ];
    $v = $variants[$variant] ?? $variants['slate'];
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @endif
    @if($click) @click="{{ $click }}" @endif 
    title="{{ $title }}"
    class="group w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-xl active:scale-90 border {{ $v['btn'] }} {{ $v['glow'] }}">
    
    @if($icon == 'edit-3' || $icon == 'user-cog' || $icon == 'edit')
        <i data-lucide="edit-3" class="w-5 h-5 transition-transform duration-500 group-hover:rotate-12 group-hover:scale-110"></i>
    @elseif($icon == 'trash-2' || $icon == 'trash')
        <i data-lucide="trash-2" class="w-5 h-5 transition-transform duration-500 group-hover:-translate-y-0.5 group-hover:scale-110"></i>
    @elseif($icon == 'eye')
        <i data-lucide="eye" class="w-5 h-5 transition-transform duration-500 group-hover:scale-125"></i>
    @elseif($icon)
        <i data-lucide="{{ $icon }}" class="w-5 h-5 transition-transform duration-500 group-hover:scale-110"></i>
    @endif
    
    {{ $slot }}
</{{ $tag }}>
