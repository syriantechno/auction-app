@extends('admin.layout')

@section('title', 'Page Builder')

@section('content')
@php
    $publishedCount = $pages->where('is_published', true)->count();
    $draftCount     = $pages->where('is_published', false)->count();
@endphp

    <x-admin-page-standard 
        icon="file-text" 
        title="Content" 
        highlight="Pages" 
        subtitle="Dynamic Content Management"
        dot="orange">
        
        <x-slot name="actions">
            <a href="{{ route('admin.pages.create') }}" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-orange-500/10">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-orange-400 group-hover:text-white"></i>
                <span>New Page</span>
            </a>
        </x-slot>

        <x-slot name="stats">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-admin-stat-card label="All Pages" :value="$pages->count()" icon="file-text" color="slate" />
                <x-admin-stat-card label="Published" :value="$publishedCount" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Drafts" :value="$draftCount" icon="clock" color="orange" />
            </div>
        </x-slot>

    @if(session('success'))
        <div class="bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.75rem] font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Page Title</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">URL Slug</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-center">In Menu</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-center">Status</th>
                    <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Created</th>
                    <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-right">Operations</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pages as $pg)
                @php
                    $menuItem = \App\Models\MenuItem::where('page_id', $pg->id)->with('menu')->first();
                @endphp
                <tr class="group hover:bg-slate-50/50 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-orange-500">
                    <td class="py-3 px-8">
                        <div class="text-[0.9rem] font-normal text-slate-700 group-hover:text-orange-600 transition-colors leading-tight">{{ $pg->title }}</div>
                        @if($pg->meta_description)
                            <div class="text-[0.6rem] text-slate-400 mt-0.5 truncate max-w-xs">{{ Str::limit($pg->meta_description, 60) }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        <code class="bg-slate-50 border border-slate-200 text-slate-500 px-2 py-0.5 rounded text-[0.65rem] font-mono">/{{ $pg->slug }}</code>
                    </td>
                    <td class="py-3 px-6 text-center">
                        @if($menuItem)
                            <span class="inline-flex items-center gap-1 text-[0.6rem] font-medium text-[#ff6900] bg-orange-50 border border-orange-100 px-2.5 py-1 rounded-full">
                                <i data-lucide="link-2" class="w-2.5 h-2.5"></i>
                                {{ $menuItem->menu->name ?? 'Menu' }}
                            </span>
                        @else
                            <span class="text-[0.65rem] text-slate-300 font-medium">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        @if($pg->is_published)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[0.6rem] font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[0.6rem] font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        <span class="text-[0.7rem] font-normal text-slate-500">{{ $pg->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="py-3 px-8 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('page.show', $pg->slug) }}" target="_blank"
                               class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                <i data-lucide="external-link" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $pg) }}"
                               class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-slate-800 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $pg) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this page?')">
                                @csrf @method('DELETE')
                                <button class="w-9 h-9 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-md border border-red-50 active:scale-95">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center bg-slate-50">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                                <i data-lucide="file-x-2" class="w-8 h-8 text-slate-200"></i>
                            </div>
                            <h3 class="text-xs font-medium text-slate-400 uppercase tracking-widest">No pages created yet</h3>
                            <a href="{{ route('admin.pages.create') }}"
                               class="text-[0.7rem] font-medium text-[#ff6900] hover:underline uppercase tracking-widest">
                               + Create First Page
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin-page-standard>
@endsection
