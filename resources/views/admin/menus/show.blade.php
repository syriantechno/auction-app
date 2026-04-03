@extends('admin.layout')

@section('title', 'Menu Builder — ' . $menu->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.menus.index') }}"
           class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
        </a>
        <div>
            <div class="text-[0.55rem] font-black uppercase tracking-[0.3em] text-slate-400 mb-0.5">
                Menu Builder · {{ $menu->location ?? 'No Location' }}
            </div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">{{ $menu->name }}</h1>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="ml-auto flex items-center gap-1.5 text-[0.65rem] font-black text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm hover:bg-slate-50 transition-all">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> New Page
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── LEFT: Current Items ── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Navigation Items</div>
                        <div class="text-sm font-black text-slate-900 mt-0.5">{{ $menu->items->count() }} items</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[0.6rem] font-bold text-slate-400">Drag to reorder</span>
                        <i data-lucide="move" class="w-3.5 h-3.5 text-slate-300"></i>
                    </div>
                </div>

                @forelse($menu->items as $item)
                    <div class="border-b border-slate-100 last:border-0 group" data-item-id="{{ $item->id }}">
                        <div class="flex items-center gap-3 px-6 py-4 hover:bg-slate-50/50 transition-colors">
                            {{-- Drag handle --}}
                            <div class="text-slate-300 cursor-grab shrink-0">
                                <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                            </div>

                            {{-- Status dot --}}
                            <div class="w-2 h-2 rounded-full {{ $item->page_id ? 'bg-indigo-400' : 'bg-slate-300' }} shrink-0"></div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    @if($item->icon)
                                        <i data-lucide="{{ $item->icon }}" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                                    @endif
                                    <span class="text-sm font-bold text-slate-900 truncate">{{ $item->label }}</span>
                                    @if($item->target === '_blank')
                                        <i data-lucide="external-link" class="w-3 h-3 text-slate-300 shrink-0"></i>
                                    @endif
                                    @if($item->page_id)
                                        <span class="inline-flex items-center gap-1 text-[0.55rem] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-full shrink-0">
                                            <i data-lucide="file-text" class="w-2.5 h-2.5"></i> Page
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[0.65rem] text-slate-400 mt-0.5 font-mono truncate">{{ $item->url ?? '—' }}</div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-1.5 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.menus.removeItem', $item) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Remove this item?')"
                                            class="w-7 h-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 hover:border-red-300 transition-all">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Children --}}
                        @if($item->children->count())
                            <div class="ml-16 border-l-2 border-slate-100 mb-3 space-y-0">
                                @foreach($item->children as $child)
                                    <div class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50/50 group/child transition-colors">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $child->page_id ? 'bg-indigo-300' : 'bg-slate-200' }} shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-xs font-bold text-slate-700 truncate">{{ $child->label }}</span>
                                            <div class="text-[0.6rem] text-slate-400 font-mono">{{ $child->url ?? '—' }}</div>
                                        </div>
                                        <form action="{{ route('admin.menus.removeItem', $child) }}" method="POST" class="inline opacity-0 group-hover/child:opacity-100 transition-opacity">
                                            @csrf @method('DELETE')
                                            <button class="w-6 h-6 rounded-lg flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <i data-lucide="menu" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                        <div class="text-xs font-bold text-slate-400">No items yet — add one from the panel →</div>
                    </div>
                @endforelse
            </div>

            {{-- Legend --}}
            <div class="mt-3 flex items-center gap-4 px-2">
                <div class="flex items-center gap-1.5 text-[0.6rem] text-slate-400 font-bold">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span> Linked to a page
                </div>
                <div class="flex items-center gap-1.5 text-[0.6rem] text-slate-400 font-bold">
                    <span class="w-2 h-2 rounded-full bg-slate-300 inline-block"></span> Manual URL
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Add Item ── --}}
        <div class="space-y-4">

            {{-- Add by Page --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="text-[0.6rem] font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                    <div class="w-4 h-px bg-indigo-300"></div> Add via Page Builder
                </div>

                <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" id="addByPageForm" class="space-y-3">
                    @csrf
                    <input type="hidden" name="_source" value="page">

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">Select Page</label>
                        <select name="page_id" id="pageSelect"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all"
                                onchange="onPageSelect(this)">
                            <option value="">— Choose a published page —</option>
                            @foreach($pages as $pg)
                                <option value="{{ $pg->id }}" data-title="{{ $pg->title }}" data-url="/{{ $pg->slug }}">
                                    {{ $pg->title }} · /{{ $pg->slug }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">Menu Label</label>
                        <input type="text" name="label" id="pageLinkLabel" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all"
                               placeholder="Auto-filled from page title">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">As sub-item of</label>
                        <select name="parent_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all">
                            <option value="">— Top-level item —</option>
                            @foreach($menu->items->whereNull('parent_id') as $ri)
                                <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-indigo-600 text-white rounded-xl text-[0.7rem] font-black uppercase tracking-widest shadow hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="link-2" class="w-3.5 h-3.5"></i> Link Page to Menu
                    </button>
                </form>
            </div>

            {{-- Add Manual URL --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                    <div class="w-4 h-px bg-slate-300"></div> Add Manual Link
                </div>

                <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" class="space-y-3">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">Label</label>
                        <input type="text" name="label" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-slate-400 transition-all"
                               placeholder="e.g. Contact Us">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">URL</label>
                        <input type="text" name="url"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-mono text-slate-700 outline-none focus:border-slate-400 transition-all"
                               placeholder="/contact or https://...">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">Open in</label>
                            <select name="target"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-slate-400 transition-all">
                                <option value="_self">Same tab</option>
                                <option value="_blank">New tab</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">Icon</label>
                            <input type="text" name="icon"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-[0.65rem] font-mono text-slate-700 outline-none focus:border-slate-400 transition-all"
                                   placeholder="home, car...">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.65rem] font-black text-slate-600 uppercase tracking-wider">As sub-item of</label>
                        <select name="parent_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-slate-400 transition-all">
                            <option value="">— Top-level item —</option>
                            @foreach($menu->items->whereNull('parent_id') as $ri)
                                <option value="{{ $ri->id }}">{{ $ri->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 bg-slate-800 text-white rounded-xl text-[0.7rem] font-black uppercase tracking-widest shadow hover:bg-slate-900 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Item
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
function onPageSelect(sel) {
    const opt = sel.options[sel.selectedIndex];
    const labelEl = document.getElementById('pageLinkLabel');
    if (opt.value && labelEl.value === '') {
        labelEl.value = opt.dataset.title || '';
    }
    // hidden url will be filled server-side via page_id
}
</script>
@endsection
