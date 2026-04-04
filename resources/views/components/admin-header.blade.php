{{--
    ╔══════════════════════════════════════════════════════╗
    ║   Admin Page Header — Standard Component (v2)        ║
    ║   Based on: inspections/tasks.blade.php pattern      ║
    ╠══════════════════════════════════════════════════════╣
    ║  Props:                                              ║
    ║   icon="compass"       → Lucide icon name            ║
    ║   title="Field"        → First word (white)          ║
    ║   highlight="Missions" → Second word (orange)        ║
    ║   subtitle="Desc..."   → Small subtitle below        ║
    ║   dot="emerald"        → Dot color (default)         ║
    ║      Values: emerald|amber|blue|violet|rose|orange   ║
    ║                                                      ║
    ║  Slot: actions → right-side buttons/stats            ║
    ╠══════════════════════════════════════════════════════╣
    ║  Examples:                                           ║
    ║                                                      ║
    ║  <!-- Simple -->                                     ║
    ║  <x-admin-header                                     ║
    ║    icon="gavel"                                      ║
    ║    title="Live"                                      ║
    ║    highlight="Auctions"                              ║
    ║    subtitle="Manage all active auction sessions"     ║
    ║    dot="emerald"                                     ║
    ║  />                                                  ║
    ║                                                      ║
    ║  <!-- With actions slot -->                          ║
    ║  <x-admin-header icon="users" title="Lead"           ║
    ║    highlight="Management" dot="blue">                ║
    ║    <x-slot name="actions">                           ║
    ║      <a href="..." class="btn...">Add Lead</a>       ║
    ║    </x-slot>                                         ║
    ║  </x-admin-header>                                   ║
    ╚══════════════════════════════════════════════════════╝
--}}

@props([
    'icon'      => 'layout-dashboard',
    'title'     => 'Page',
    'highlight' => '',
    'subtitle'  => '',
    'dot'       => 'emerald',
])

@php
$dotColors = [
    'emerald' => 'bg-emerald-500',
    'amber'   => 'bg-amber-400',
    'blue'    => 'bg-blue-500',
    'violet'  => 'bg-violet-500',
    'rose'    => 'bg-rose-500',
    'orange'  => 'bg-[#ff6900]',
    'cyan'    => 'bg-cyan-500',
    'indigo'  => 'bg-indigo-500',
];
$dotClass = $dotColors[$dot] ?? 'bg-emerald-500';
@endphp

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-10 border-b border-slate-100">

    {{-- Left: Icon + Title --}}
    <div class="flex items-center gap-6">

        {{-- Icon Box with dot badge --}}
        <div class="relative">
            <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl shadow-[#031629]/20 transform rotate-3">
                <i data-lucide="{{ $icon }}" class="w-7 h-7 text-[#ff6900]"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg {{ $dotClass }} border-[3px] border-[#f8fafc] animate-pulse"></div>
        </div>

        {{-- Text --}}
        <div>
            <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                {{ $title }}
                @if($highlight)
                    <span class="text-[#ff6900]">{{ $highlight }}</span>
                @endif
            </h1>
            @if($subtitle)
            <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-3">
                {{ $subtitle }}
            </p>
            @endif
        </div>
    </div>

    {{-- Right: Actions slot --}}
    @if(isset($actions))
    <div class="flex items-center gap-3 flex-shrink-0">
        {{ $actions }}
    </div>
    @endif

</div>
