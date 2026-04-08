@extends('admin.layout')

@section('title', 'Navigation Architect')

@section('content')
<x-admin-page-standard 
    icon="menu" 
    title="Navigation" 
    highlight="Architect" 
    subtitle="Manage header & footer navigation menus"
    dot="violet"
>
    <x-slot name="actions">
        <div class="flex items-center gap-3">
            <div class="h-[52px] flex items-center gap-3 bg-white px-6 rounded-xl border-2 border-slate-100 shadow-sm transition-all hover:bg-slate-50">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ff6900] animate-pulse flex-shrink-0"></span>
                <span class="text-[0.7rem] font-black uppercase text-slate-500 tracking-[0.2em]">Active · {{ $menus->count() }} Zones</span>
            </div>
            <a href="{{ route('admin.pages.index') }}"
               class="h-[52px] px-8 bg-[#1d293d] hover:bg-[#ff6900] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-900/10 group">
                <i data-lucide="file-text" class="w-4 h-4 group-hover:scale-110 transition-transform duration-500"></i>
                Page Builder
            </a>
        </div>
    </x-slot>


    @if(session('success'))
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm mb-6">
        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
        {{ session('success') }}
    </div>
    @endif


    {{-- ══════════════════════════
         MENU CARDS GRID
    ══════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($menus as $menu)

        @php
            $locationIcons = [
                'header' => ['icon' => 'layout-template', 'color' => 'bg-blue-50 text-blue-600 border-blue-200', 'dot' => 'bg-blue-500'],
                'footer' => ['icon' => 'panel-bottom',    'color' => 'bg-violet-50 text-violet-600 border-violet-200', 'dot' => 'bg-violet-500'],
            ];
            $loc = $locationIcons[$menu->location] ?? ['icon' => 'menu', 'color' => 'bg-slate-50 text-slate-500 border-slate-200', 'dot' => 'bg-slate-400'];
        @endphp

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:border-slate-300 transition-all duration-200 group">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-slate-50 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    {{-- Location Badge --}}
                    <div class="w-8 h-8 rounded-lg {{ $loc['color'] }} border flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $loc['icon'] }}" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[0.7rem] font-black text-[#1d293d] uppercase tracking-wider group-hover:text-[#ff6900] transition-colors">{{ $menu->name }}</div>
                        <div class="text-[0.52rem] text-slate-400 font-bold uppercase tracking-widest">{{ strtoupper($menu->location ?? 'Custom') }} Zone</div>
                    </div>
                </div>

                {{-- Items count badge --}}
                <span class="px-2.5 py-1 {{ $menu->items_count > 0 ? 'bg-[#ff6900]/10 text-[#ff6900]' : 'bg-slate-100 text-slate-400' }} rounded-md text-[0.52rem] font-black uppercase tracking-widest">
                    {{ $menu->items_count }} Items
                </span>
            </div>

            {{-- Card Body --}}
            <div class="p-5 bg-[#f0f2f5]">

                @if($menu->items_count > 0)
                {{-- Navigation Items Preview --}}
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @foreach($menu->items->take(8) as $item)
                    <span class="inline-flex items-center gap-1.5 text-[0.6rem] font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full {{ $item->page_id ? 'bg-[#ff6900]' : 'bg-slate-300' }} flex-shrink-0"></span>
                        {{ $item->label }}
                    </span>
                    @endforeach
                    @if($menu->items_count > 8)
                    <span class="inline-flex items-center text-[0.58rem] text-slate-400 font-black self-center px-1">
                        +{{ $menu->items_count - 8 }} more
                    </span>
                    @endif
                </div>

                {{-- Divider --}}
                <div class="border-t border-slate-200 mb-4"></div>

                {{-- Stats bar --}}
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-[#1d293d]">{{ $menu->items_count }}</div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Total</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-[#ff6900]">{{ $menu->items->where('page_id', '!=', null)->count() }}</div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Pages</div>
                    </div>
                    <div class="bg-white rounded-lg border border-slate-200 px-3 py-2 text-center">
                        <div class="text-sm font-black text-slate-400">{{ $menu->items->where('page_id', null)->count() }}</div>
                        <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest">Links</div>
                    </div>
                </div>

                @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-8 text-center mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                    </div>
                    <p class="text-[0.6rem] text-slate-300 font-black uppercase tracking-widest">No items yet</p>
                    <p class="text-[0.55rem] text-slate-400 mt-0.5">Click Edit to add navigation links</p>
                </div>
                @endif

                <a href="{{ route('admin.menus.show', $menu) }}"
                   class="flex items-center justify-center gap-2 w-full py-2.5 bg-[#1d293d] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Menu
                </a>
            </div>
        </div>

        @empty
        <div class="md:col-span-2 py-24 text-center bg-white rounded-xl border border-dashed border-slate-200">
            <div class="flex flex-col items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-[#f0f2f5] flex items-center justify-center border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                </div>
                <div>
                    <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No Menu Zones Detected</p>
                    <p class="text-[0.58rem] text-slate-400 mt-1">Navigation menus will appear here once created</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ══ Legend ══ --}}
    <div class="flex items-center gap-5 text-[0.58rem] text-slate-400 font-bold px-1">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-[#ff6900] inline-block"></span>
            Linked to a dynamic page
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span>
            Manual URL
        </div>
        <div class="flex items-center gap-1.5 ml-auto">
            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
            Header Zone
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-violet-500 inline-block"></span>
            Footer Zone
        </div>
    </div>

</x-admin-page-standard>
@endsection
