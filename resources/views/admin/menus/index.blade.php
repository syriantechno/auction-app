@extends('admin.layout')

@section('title', 'Menu Builder')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[0.55rem] font-black uppercase tracking-[0.3em] text-slate-400 mb-1 flex items-center gap-2">
                <div class="w-4 h-px bg-slate-300"></div> Navigation
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Menu Builder</h1>
            <p class="text-xs text-slate-400 mt-0.5">Manage site navigation menus and link them to dynamic pages.</p>
        </div>
        <a href="{{ route('admin.pages.index') }}"
           class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-[0.75rem] font-black shadow-sm hover:bg-slate-50 transition-all shrink-0">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Page Builder
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Menu Cards --}}
    @forelse($menus as $menu)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        @if($menu->location === 'header')
                            <i data-lucide="layout-template" class="w-5 h-5 text-slate-600"></i>
                        @elseif($menu->location === 'footer')
                            <i data-lucide="panel-bottom" class="w-5 h-5 text-slate-600"></i>
                        @else
                            <i data-lucide="menu" class="w-5 h-5 text-slate-600"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900">{{ $menu->name }}</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            @if($menu->location)
                                <span class="text-[0.55rem] font-black uppercase tracking-widest text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">
                                    {{ $menu->location }}
                                </span>
                            @endif
                            <span class="text-[0.6rem] text-slate-400 font-bold">{{ $menu->items_count }} items</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.menus.show', $menu) }}"
                   class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-[0.7rem] font-black uppercase tracking-wider shadow hover:bg-slate-800 transition-all">
                    <i data-lucide="settings-2" class="w-3.5 h-3.5"></i> Manage
                </a>
            </div>

            {{-- Preview of items --}}
            @if($menu->items_count > 0)
                <div class="px-6 py-3 flex flex-wrap gap-2">
                    @foreach($menu->items->take(8) as $item)
                        <span class="inline-flex items-center gap-1.5 text-[0.65rem] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-full">
                            @if($item->icon)
                                <i data-lucide="{{ $item->icon }}" class="w-3 h-3"></i>
                            @endif
                            {{ $item->label }}
                            @if($item->page_id)
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block"></span>
                            @endif
                        </span>
                    @endforeach
                    @if($menu->items_count > 8)
                        <span class="text-[0.65rem] font-bold text-slate-400">+{{ $menu->items_count - 8 }} more</span>
                    @endif
                </div>
            @else
                <div class="px-6 py-4 text-[0.7rem] text-slate-400 font-bold italic">No items yet — click Manage to add links.</div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-xl border border-dashed border-slate-200 py-20 text-center">
            <i data-lucide="menu" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
            <div class="text-slate-400 font-bold text-sm">No menus found.</div>
            <p class="text-xs text-slate-300 mt-1">Create menus from the database seeder or add them manually.</p>
        </div>
    @endforelse

    {{-- Legend --}}
    <div class="flex items-center gap-6 text-[0.65rem] text-slate-400 font-bold px-2">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span> = Linked to a dynamic page
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span> = Manual URL
        </div>
    </div>

</div>
@endsection
