@extends('admin.layout')

@section('title', 'Navigation Architect')

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-medium text-slate-800 tracking-tighter italic">Navigation Menus</h1>
            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
            <p class="text-[0.65rem] text-slate-500 font-medium uppercase tracking-[0.2em] hidden md:block">Navigation Management</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 bg-white px-5 py-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-[#ff6900] animate-pulse"></span>
                <span class="text-[0.6rem] font-medium uppercase text-slate-400 tracking-widest">Active Deployment</span>
            </div>
            <a href="{{ route('admin.pages.index') }}"
               class="px-6 h-[44px] bg-slate-800 text-white rounded-lg font-medium shadow-sm hover:scale-[1.02] active:scale-95 transition-all text-[0.65rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-white/70"></i> Page Builder
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.75rem] font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Menu Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @forelse($menus as $menu)
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-200 group overflow-hidden border-l-4 border-l-slate-200 hover:border-l-orange-500">
            <div class="p-6">
                <div class="flex justify-between items-start mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-md bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:scale-110 transition-transform">
                            @if($menu->location === 'header')
                                <i data-lucide="layout-template" class="w-5 h-5 text-slate-500"></i>
                            @elseif($menu->location === 'footer')
                                <i data-lucide="panel-bottom" class="w-5 h-5 text-slate-500"></i>
                            @else
                                <i data-lucide="menu" class="w-5 h-5 text-slate-500"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-[0.95rem] font-normal text-slate-700 tracking-tight italic group-hover:text-orange-600 transition-colors">{{ $menu->name }}</h2>
                            <span class="text-[0.55rem] text-slate-400 font-medium uppercase tracking-widest">{{ $menu->location ?? 'No Location' }} Profile</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-slate-50 rounded-full text-[0.6rem] font-medium text-slate-500 tabular-nums border border-slate-200">
                        {{ $menu->items_count }} Active Items
                    </span>
                </div>

                {{-- Items Preview --}}
                @if($menu->items_count > 0)
                    <div class="flex flex-wrap gap-1.5 mb-5">
                        @foreach($menu->items->take(6) as $item)
                            <span class="inline-flex items-center gap-1 text-[0.6rem] font-medium text-slate-500 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-md">
                                @if($item->page_id)
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#ff6900] inline-block"></span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 inline-block"></span>
                                @endif
                                {{ $item->label }}
                            </span>
                        @endforeach
                        @if($menu->items_count > 6)
                            <span class="text-[0.6rem] text-slate-400 font-medium self-center">+{{ $menu->items_count - 6 }} more</span>
                        @endif
                    </div>
                @else
                    <p class="text-[0.65rem] text-slate-400 font-medium italic mb-5">No items yet — click Edit to add links.</p>
                @endif

                <a href="{{ route('admin.menus.show', $menu) }}"
                   class="block w-full py-3 bg-slate-50 text-slate-500 rounded-md text-center text-[0.65rem] font-medium uppercase tracking-widest border border-slate-200 shadow-sm hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all">
                    Edit Menu
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 py-20 text-center bg-white rounded-lg border border-dashed border-slate-200 shadow-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center border border-slate-200">
                    <i data-lucide="menu" class="w-8 h-8 text-slate-200"></i>
                </div>
                <h3 class="text-xs font-medium text-slate-400 uppercase tracking-widest">No Nav Zones Detected</h3>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-5 text-[0.6rem] text-slate-400 font-medium px-1">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-[#ff6900] inline-block"></span> Linked to a dynamic page
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span> Manual URL
        </div>
    </div>

</div>
@endsection
