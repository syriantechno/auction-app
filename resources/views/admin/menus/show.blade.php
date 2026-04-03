@extends('admin.layout')

@section('title', 'Menu Builder — ' . $menu->name)

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.menus.index') }}"
               class="w-9 h-9 rounded-md bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-400 hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all active:scale-95">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="text-3xl font-medium text-slate-800 tracking-tighter italic">{{ $menu->name }}</h1>
                <p class="text-[0.65rem] text-slate-500 font-medium uppercase tracking-[0.2em] mt-0.5">
                    Menu Builder · {{ $menu->location ?? 'No Location' }} Zone
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3 bg-white px-5 py-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="text-[0.6rem] font-medium uppercase text-slate-400 tracking-widest">Link Nodes:</span>
                <span class="text-sm font-medium text-slate-800 tabular-nums">{{ $menu->items->count() }}</span>
            </div>
            <a href="{{ route('admin.pages.create') }}"
               class="px-6 h-[44px] bg-slate-800 text-white rounded-lg font-medium shadow-sm hover:scale-[1.02] active:scale-95 transition-all text-[0.65rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-white/80"></i> New Page
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.75rem] font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5 items-start">

        {{-- ═══ LEFT: Items List (3/5) ═══ --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-lg border border-slate-200 shadow-xl overflow-hidden">

                {{-- Table Header --}}
                <div class="bg-slate-50 border-b border-slate-200 px-8 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 class="text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Navigation Items</h2>
                        <span class="px-2.5 py-0.5 bg-white border border-slate-200 rounded-full text-[0.6rem] font-medium text-slate-500 tabular-nums">{{ $menu->items->count() }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Order by drag</span>
                        <i data-lucide="move" class="w-3.5 h-3.5 text-slate-300"></i>
                    </div>
                </div>

                {{-- Items --}}
                @forelse($menu->items as $item)
                <div class="group border-b border-slate-100 last:border-0 border-l-4 border-l-transparent hover:border-l-orange-500 transition-all duration-200">
                    <div class="flex items-center gap-4 px-8 py-3.5 hover:bg-slate-50/50 transition-colors">
                        {{-- Drag handle --}}
                        <i data-lucide="grip-vertical" class="w-4 h-4 text-slate-200 group-hover:text-slate-400 cursor-grab shrink-0 transition-colors"></i>

                        {{-- Indicator --}}
                        <div class="w-2 h-2 rounded-full {{ $item->page_id ? 'bg-[#ff6900]' : 'bg-slate-300' }} shrink-0"></div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2.5">
                                @if($item->icon)
                                    <i data-lucide="{{ $item->icon }}" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                @endif
                                <span class="text-[0.9rem] font-normal text-slate-700 group-hover:text-orange-600 transition-colors truncate">
                                    {{ $item->label }}
                                </span>
                                @if($item->page_id)
                                    <span class="inline-flex items-center gap-1 text-[0.55rem] font-medium text-[#ff6900] bg-orange-50 border border-orange-100 px-2 py-0.5 rounded-full shrink-0">
                                        <i data-lucide="file-text" class="w-2.5 h-2.5"></i> Page
                                    </span>
                                @endif
                                @if($item->target === '_blank')
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-300 shrink-0"></i>
                                @endif
                            </div>
                            <div class="text-[0.6rem] text-slate-400 font-mono mt-0.5 truncate">{{ $item->url ?? '—' }}</div>
                        </div>

                        {{-- Delete --}}
                        <form action="{{ route('admin.menus.removeItem', $item) }}" method="POST" class="shrink-0">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Remove this item?')"
                                    class="w-9 h-9 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-600 hover:text-white opacity-0 group-hover:opacity-100 transition-all shadow-md border border-red-50 active:scale-95">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Children --}}
                    @if($item->children->count())
                        @foreach($item->children as $child)
                        <div class="flex items-center gap-4 pl-20 pr-8 py-2.5 border-t border-slate-50 hover:bg-slate-50/30 group/child transition-colors">
                            <div class="w-1.5 h-1.5 rounded-full {{ $child->page_id ? 'bg-[#ff6900]/60' : 'bg-slate-200' }} shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[0.75rem] font-normal text-slate-600 truncate group-hover/child:text-orange-500 transition-colors">{{ $child->label }}</span>
                                <div class="text-[0.55rem] text-slate-400 font-mono">{{ $child->url ?? '—' }}</div>
                            </div>
                            <form action="{{ route('admin.menus.removeItem', $child) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 rounded-md flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 opacity-0 group-hover/child:opacity-100 transition-all">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    @endif
                </div>
                @empty
                <div class="py-20 text-center bg-slate-50">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                            <i data-lucide="link" class="w-8 h-8 text-slate-200"></i>
                        </div>
                        <h3 class="text-xs font-medium text-slate-400 uppercase tracking-widest">No link nodes registered</h3>
                        <p class="text-[0.6rem] text-slate-400">Add items from the panel →</p>
                    </div>
                </div>
                @endforelse

                {{-- Legend --}}
                @if($menu->items->count() > 0)
                <div class="bg-slate-50 px-8 py-3 border-t border-slate-100 flex items-center gap-5">
                    <div class="flex items-center gap-1.5 text-[0.55rem] text-slate-400 font-medium uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-[#ff6900] inline-block"></span> Page-linked node
                    </div>
                    <div class="flex items-center gap-1.5 text-[0.55rem] text-slate-400 font-medium uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span> Manual URL
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- ═══ RIGHT: Add Panels (2/5) ═══ --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Add via Page Builder --}}
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-800 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-[0.65rem] font-medium uppercase tracking-[0.2em] text-white/80">Add via Page Builder</h4>
                        <p class="text-[0.55rem] text-white/30 font-normal uppercase mt-0.5">Link dynamic page → menu</p>
                    </div>
                    <div class="w-8 h-8 rounded-md bg-white/10 flex items-center justify-center border border-white/10">
                        <i data-lucide="file-text" class="w-4 h-4 text-white/60"></i>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" id="addByPageForm" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Select Page</label>
                            <div class="relative">
                                <select name="page_id" id="pageSelect"
                                        class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                        onchange="onPageSelect(this)">
                                    <option value="">— Choose a published page —</option>
                                    @foreach($pages as $pg)
                                        <option value="{{ $pg->id }}" data-title="{{ $pg->title }}" data-url="/{{ $pg->slug }}">
                                            {{ $pg->title }} · /{{ $pg->slug }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Menu Label</label>
                            <input type="text" name="label" id="pageLinkLabel" required
                                   class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                   placeholder="Auto-filled from page title">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">As Sub-item of</label>
                            <div class="relative">
                                <select name="parent_id"
                                        class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                                    <option value="">— Top-level item —</option>
                                    @foreach($menu->items->whereNull('parent_id') as $ri)
                                        <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <button type="submit" style="background: var(--primary-orange);"
                                class="w-full h-[44px] text-white rounded-lg text-[0.7rem] font-medium uppercase tracking-[0.2em] shadow-lg shadow-orange-500/10 flex items-center justify-center gap-2 transition-all hover:scale-[1.01] active:scale-98">
                            <i data-lucide="link-2" class="w-4 h-4 text-white/80"></i>
                            Link Page to Menu
                        </button>
                    </form>
                </div>
            </div>

            {{-- Add Manual Link --}}
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h4 class="text-[0.65rem] font-medium uppercase tracking-[0.2em] text-slate-500">Add Manual Link</h4>
                    <p class="text-[0.55rem] text-slate-400 font-normal mt-0.5 uppercase tracking-widest">Custom URL segment node</p>
                </div>

                <div class="p-6 space-y-4">
                    <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Label</label>
                            <input type="text" name="label" required
                                   class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                   placeholder="e.g. Contact Us">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">URL</label>
                            <input type="text" name="url"
                                   class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-mono font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                   placeholder="/contact or https://...">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Open in</label>
                                <div class="relative">
                                    <select name="target"
                                            class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                                        <option value="_self">Same tab</option>
                                        <option value="_blank">New tab</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Icon</label>
                                <input type="text" name="icon"
                                       class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.75rem] font-mono text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                       placeholder="home, car...">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">As Sub-item of</label>
                            <div class="relative">
                                <select name="parent_id"
                                        class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                                    <option value="">— Top-level item —</option>
                                    @foreach($menu->items->whereNull('parent_id') as $ri)
                                        <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full h-[44px] bg-slate-800 text-white/90 rounded-lg text-[0.7rem] font-medium uppercase tracking-[0.2em] shadow-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.01] active:scale-98 hover:bg-slate-700">
                            <i data-lucide="plus-circle" class="w-4 h-4 text-white/70"></i>
                            Append Node
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function onPageSelect(sel) {
    const opt = sel.options[sel.selectedIndex];
    const labelEl = document.getElementById('pageLinkLabel');
    if (opt.value && !labelEl.value) {
        labelEl.value = opt.dataset.title || '';
    }
}
</script>
@endsection
