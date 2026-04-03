@extends('admin.layout')

@section('title', 'Page Builder')

@section('content')
@php
    $publishedCount = $pages->where('is_published', true)->count();
    $draftCount     = $pages->where('is_published', false)->count();
@endphp

<div class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="text-[0.55rem] font-black uppercase tracking-[0.3em] text-slate-400 mb-1 flex items-center gap-2">
                <div class="w-4 h-px bg-slate-300"></div> Page Builder
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dynamic Pages</h1>
            <p class="text-xs text-slate-400 mt-0.5">Create, publish and link pages to your site navigation.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[0.75rem] font-black tracking-wide shadow-lg hover:bg-slate-800 transition-all shrink-0">
            <i data-lucide="plus" class="w-4 h-4"></i>
            New Page
        </a>
    </div>

    {{-- ── Stats ── --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-slate-900/5 flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5 text-slate-700"></i>
            </div>
            <div>
                <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Total</div>
                <div class="text-2xl font-black text-slate-900">{{ $pages->count() }}</div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <div>
                <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Published</div>
                <div class="text-2xl font-black text-slate-900">{{ $publishedCount }}</div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5 text-amber-500"></i>
            </div>
            <div>
                <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Drafts</div>
                <div class="text-2xl font-black text-slate-900">{{ $draftCount }}</div>
            </div>
        </div>
    </div>

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-bold">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>{{ session('success') }}
        </div>
    @endif

    {{-- ── Pages List ── --}}
    @if($pages->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-slate-200 p-20 text-center">
            <i data-lucide="file-x-2" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
            <div class="text-slate-400 font-bold text-sm">No pages yet.</div>
            <a href="{{ route('admin.pages.create') }}" class="mt-4 inline-flex items-center gap-2 text-[0.75rem] font-black text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg transition-all">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Create your first page
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70">
                        <th class="text-left px-6 py-3.5 text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Page</th>
                        <th class="text-left px-6 py-3.5 text-[0.6rem] font-black uppercase tracking-widest text-slate-400">URL</th>
                        <th class="text-left px-6 py-3.5 text-[0.6rem] font-black uppercase tracking-widest text-slate-400">In Menu</th>
                        <th class="text-left px-6 py-3.5 text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="text-left px-6 py-3.5 text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Created</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pages as $pg)
                    @php
                        $menuItem = \App\Models\MenuItem::where('page_id', $pg->id)->with('menu')->first();
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $pg->title }}</div>
                            @if($pg->meta_description)
                                <div class="text-[0.65rem] text-slate-400 mt-0.5 truncate max-w-xs">{{ Str::limit($pg->meta_description, 55) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md text-[0.7rem] font-mono">/{{ $pg->slug }}</code>
                        </td>
                        <td class="px-6 py-4">
                            @if($menuItem)
                                <span class="inline-flex items-center gap-1.5 text-[0.65rem] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">
                                    <i data-lucide="link-2" class="w-3 h-3"></i>
                                    {{ $menuItem->menu->name ?? 'Menu' }}
                                </span>
                            @else
                                <span class="text-[0.65rem] text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($pg->is_published)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[0.65rem] font-black uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[0.65rem] font-black uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[0.7rem] text-slate-400">{{ $pg->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('page.show', $pg->slug) }}" target="_blank"
                                   class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition-all">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                </a>
                                <a href="{{ route('admin.pages.edit', $pg) }}"
                                   class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $pg) }}" method="POST"
                                      onsubmit="return confirm('Delete this page?')">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
