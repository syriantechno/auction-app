@extends('admin.layout')

@section('title', 'Menu Builder — ' . $menu->name)

@section('content')
<div class="pb-20 space-y-5">

    {{-- ══════════════════════════
         HEADER
    ══════════════════════════ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-violet-500 border-[3px] border-[#f8fafc] animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    {{ $menu->name }} <span class="text-[#ff6900]">Builder</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    {{ strtoupper($menu->location ?? 'Custom') }} Zone · Drag to reorder items
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white border border-slate-200 rounded-lg px-4 py-2.5 flex items-center gap-2 shadow-sm">
                <span class="text-[0.55rem] font-black uppercase text-slate-400 tracking-widest">Items</span>
                <span class="text-lg font-black text-[#1d293d] tabular-nums" id="itemsCounter">{{ $menu->items->count() }}</span>
            </div>
            <a href="{{ route('admin.menus.index') }}"
               class="px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#1d293d] flex items-center gap-2 text-[0.6rem] font-black uppercase tracking-widest transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back
            </a>
            <a href="{{ route('admin.pages.create') }}"
               class="px-5 py-2.5 bg-[#1d293d] text-white rounded-lg font-black text-[0.62rem] uppercase tracking-widest flex items-center gap-2 hover:bg-[#ff6900] transition-all shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Page
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ══════════════════════════
         MAIN GRID
    ══════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5 items-start">

        {{-- ═══ LEFT: Items List (3/5) ═══ --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">

                {{-- Table Header --}}
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                        </div>
                        <span class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Navigation Items</span>
                        <span class="px-2 py-0.5 bg-[#ff6900]/10 text-[#ff6900] rounded-md text-[0.52rem] font-black uppercase tracking-widest">{{ $menu->items->count() }}</span>
                    </div>
                    {{-- Save order indicator --}}
                    <div class="flex items-center gap-2">
                        <span id="sortStatus" class="text-[0.52rem] font-black text-slate-300 uppercase tracking-widest hidden">Saving order...</span>
                        <div class="flex items-center gap-1.5 text-[0.52rem] font-bold text-slate-400 uppercase tracking-widest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                            Drag to reorder
                        </div>
                    </div>
                </div>

                {{-- Sortable Items List --}}
                <div id="sortableList" class="divide-y divide-slate-100 bg-[#f0f2f5]">

                    @forelse($menu->items as $item)
                    <div class="group bg-white border-b border-slate-100 last:border-0 border-l-4 border-l-transparent hover:border-l-[#ff6900] transition-all duration-200 sortable-item"
                         data-id="{{ $item->id }}">

                        <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50/40 transition-colors">

                            {{-- Drag Handle --}}
                            <div class="drag-handle cursor-grab active:cursor-grabbing p-1 text-slate-200 hover:text-slate-400 transition-colors flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                            </div>

                            {{-- Type Dot --}}
                            <div class="w-2.5 h-2.5 rounded-full flex-shrink-0 {{ $item->page_id ? 'bg-[#ff6900]' : 'bg-slate-300' }}"></div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    @if($item->icon)
                                    <i data-lucide="{{ $item->icon }}" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                                    @endif
                                    <span class="text-[0.78rem] font-bold text-[#1d293d] group-hover:text-[#ff6900] transition-colors truncate">
                                        {{ $item->label }}
                                    </span>
                                    @if($item->page_id)
                                    <span class="text-[0.5rem] font-black text-[#ff6900] bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded-md flex-shrink-0">Page</span>
                                    @endif
                                    @if($item->target === '_blank')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    @endif
                                </div>
                                <div class="text-[0.58rem] text-slate-400 font-mono mt-0.5 truncate">{{ $item->url ?? '—' }}</div>
                            </div>

                            {{-- Delete --}}
                            <form action="{{ route('admin.menus.removeItem', $item) }}" method="POST" class="flex-shrink-0">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Remove this item?')"
                                        class="w-7 h-7 rounded-lg bg-red-50 border border-red-100 text-red-300 flex items-center justify-center hover:bg-red-500 hover:text-white hover:border-red-500 opacity-0 group-hover:opacity-100 transition-all active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>

                        {{-- Children --}}
                        @if($item->children->count())
                        <div class="border-t border-slate-100 bg-slate-50/60">
                            @foreach($item->children as $child)
                            <div class="flex items-center gap-3 pl-14 pr-5 py-2.5 hover:bg-slate-100/60 group/child transition-colors">
                                <span class="text-slate-300 text-xs">└</span>
                                <div class="w-2 h-2 rounded-full {{ $child->page_id ? 'bg-[#ff6900]/50' : 'bg-slate-200' }} flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-[0.7rem] font-medium text-slate-600 group-hover/child:text-[#ff6900] transition-colors">{{ $child->label }}</span>
                                    <span class="text-[0.55rem] text-slate-400 font-mono ml-2">{{ $child->url ?? '—' }}</span>
                                </div>
                                <form action="{{ route('admin.menus.removeItem', $child) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="w-6 h-6 rounded-md flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 opacity-0 group-hover/child:opacity-100 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    @empty
                    <div class="py-20 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            </div>
                            <p class="text-[0.62rem] font-black text-slate-300 uppercase tracking-widest">No items yet</p>
                            <p class="text-[0.58rem] text-slate-400">Add items from the panel →</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Footer Legend --}}
                @if($menu->items->count() > 0)
                <div class="flex items-center gap-5 px-5 py-3 bg-slate-50 border-t border-slate-200">
                    <div class="flex items-center gap-1.5 text-[0.52rem] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-[#ff6900] inline-block"></span> Page-linked
                    </div>
                    <div class="flex items-center gap-1.5 text-[0.52rem] font-bold text-slate-400 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span> Manual URL
                    </div>
                    <div class="ml-auto text-[0.5rem] text-emerald-500 font-black uppercase tracking-widest hidden" id="savedBadge">
                        ✓ Order saved
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ═══ RIGHT: Add Panels (2/5) ═══ --}}
        <div class="xl:col-span-2 space-y-4 sticky top-6">

            {{-- Add via Page --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-3 bg-[#1d293d]">
                    <div class="w-7 h-7 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <div class="text-[0.62rem] font-black text-white uppercase tracking-widest">Link a Page</div>
                        <div class="text-[0.5rem] text-white/40 font-bold uppercase tracking-widest">Connect dynamic page to menu</div>
                    </div>
                </div>
                <div class="p-5 bg-[#f0f2f5] space-y-3">
                    <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Select Page</label>
                            <select name="page_id" id="pageSelect" onchange="onPageSelect(this)"
                                    class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-medium text-slate-700 appearance-none outline-none focus:border-[#ff6900] transition-all">
                                <option value="">— Choose a published page —</option>
                                @foreach($pages as $pg)
                                <option value="{{ $pg->id }}" data-title="{{ $pg->title }}" data-url="/{{ $pg->slug }}">
                                    {{ $pg->title }} · /{{ $pg->slug }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Menu Label</label>
                            <input type="text" name="label" id="pageLinkLabel" required
                                   class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-medium text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                                   placeholder="Auto-filled from page title">
                        </div>

                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">As Sub-item of</label>
                            <select name="parent_id"
                                    class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-medium text-slate-700 appearance-none outline-none focus:border-[#ff6900] transition-all">
                                <option value="">— Top-level item —</option>
                                @foreach($menu->items->whereNull('parent_id') as $ri)
                                <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-[#ff6900] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#e55e00] transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            Link Page to Menu
                        </button>
                    </form>
                </div>
            </div>

            {{-- Add Manual Link --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </div>
                    <div class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Manual Link</div>
                    <div class="ml-auto text-[0.5rem] text-slate-400 font-bold uppercase tracking-widest">Any URL</div>
                </div>
                <div class="p-5 bg-[#f0f2f5] space-y-3">
                    <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Label</label>
                            <input type="text" name="label" required
                                   class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-medium text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                                   placeholder="e.g. Contact Us">
                        </div>

                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">URL</label>
                            <input type="text" name="url"
                                   class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-mono text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                                   placeholder="/contact or https://...">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Open in</label>
                                <select name="target"
                                        class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] text-slate-700 appearance-none outline-none focus:border-[#ff6900] transition-all">
                                    <option value="_self">Same tab</option>
                                    <option value="_blank">New tab</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Icon</label>
                                <input type="text" name="icon"
                                       class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] font-mono text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                                       placeholder="home, car...">
                            </div>
                        </div>

                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">As Sub-item of</label>
                            <select name="parent_id"
                                    class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.78rem] text-slate-700 appearance-none outline-none focus:border-[#ff6900] transition-all">
                                <option value="">— Top-level item —</option>
                                @foreach($menu->items->whereNull('parent_id') as $ri)
                                <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full py-2.5 bg-[#1d293d] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#ff6900] transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Link
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
// ── Page select auto-fill label ──
function onPageSelect(sel) {
    const opt = sel.options[sel.selectedIndex];
    const labelEl = document.getElementById('pageLinkLabel');
    if (opt.value && !labelEl.value) {
        labelEl.value = opt.dataset.title || '';
    }
}

// ── Drag & Drop Reorder ──
const sortableList = document.getElementById('sortableList');
const sortStatus   = document.getElementById('sortStatus');
const savedBadge   = document.getElementById('savedBadge');

if (sortableList) {
    new Sortable(sortableList, {
        handle: '.drag-handle',
        animation: 180,
        ghostClass: 'bg-orange-50',
        chosenClass: 'shadow-lg',
        dragClass: 'opacity-50',

        onStart() {
            sortableList.classList.add('cursor-grabbing');
        },

        onEnd() {
            sortableList.classList.remove('cursor-grabbing');
            saveOrder();
        }
    });
}

async function saveOrder() {
    const items = sortableList.querySelectorAll('.sortable-item');
    const order = Array.from(items).map(el => parseInt(el.dataset.id));

    if (sortStatus)  { sortStatus.innerText = 'Saving...'; sortStatus.classList.remove('hidden'); }
    if (savedBadge)  savedBadge.classList.add('hidden');

    try {
        const res = await fetch('{{ route('admin.menus.reorder', $menu) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ order })
        });

        const data = await res.json();

        if (data.success) {
            if (sortStatus)  { sortStatus.innerText = '✓ Saved'; sortStatus.style.color = '#10b981'; }
            if (savedBadge)  savedBadge.classList.remove('hidden');
            setTimeout(() => {
                if (sortStatus)  sortStatus.classList.add('hidden');
                if (savedBadge)  savedBadge.classList.add('hidden');
            }, 2500);
        }
    } catch (err) {
        if (sortStatus) { sortStatus.innerText = '✗ Error'; sortStatus.style.color = '#ef4444'; }
        console.error('Reorder error:', err);
    }
}
</script>
@endsection
