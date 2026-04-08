@extends('admin.layout')
@section('title', 'Editorial Hub')

@section('content')
<div class="pb-20 space-y-5">

    {{-- ══ HEADER ══ --}}
    <x-admin-page-standard 
        icon="pen-tool" 
        title="Editorial" 
        highlight="Hub" 
        subtitle="Blog posts & content management"
        dot="rose">
        
        <x-slot name="actions">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 h-14 bg-white border-2 border-slate-100 rounded-2xl text-slate-500 hover:text-slate-900 flex items-center gap-2 text-[0.7rem] font-black uppercase tracking-widest transition-all duration-300">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.posts.create') }}"
                   class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-rose-500/10">
                    <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-rose-400 group-hover:text-white"></i>
                    <span>New Article</span>
                </a>
            </div>
        </x-slot>

        <x-slot name="stats">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-admin-stat-card label="All Articles" :value="$totalCount" icon="file-text" color="slate" />
                <x-admin-stat-card label="Published" :value="$liveCount" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Drafts" :value="$draftCount" icon="clock" color="orange" />
            </div>
        </x-slot>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ══ TABLE ══ --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
            <span class="text-[0.6rem] font-black text-[#1d293d] uppercase tracking-widest">All Articles</span>
            <span class="text-[0.52rem] text-slate-400 font-bold uppercase tracking-widest">{{ $posts->total() }} total</span>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 bg-[#f0f2f5]">
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-5 font-black tracking-widest w-16">Cover</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Title</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Category</th>
                    <th class="text-center text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Status</th>
                    <th class="text-left text-[0.58rem] text-slate-400 uppercase py-3 px-4 font-black tracking-widest">Date</th>
                    <th class="text-right text-[0.58rem] text-slate-400 uppercase py-3 px-5 font-black tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($posts as $post)
                <tr class="group hover:bg-slate-50/50 transition-all border-l-4 border-l-transparent hover:border-l-[#ff6900]">

                    {{-- Cover --}}
                    <td class="py-4 px-5">
                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-[#f0f2f5] border border-slate-200 flex items-center justify-center flex-shrink-0">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            @endif
                        </div>
                    </td>

                    {{-- Title --}}
                    <td class="py-4 px-4">
                        <div class="font-black text-[0.82rem] text-[#1d293d] group-hover:text-[#ff6900] transition-colors line-clamp-1">{{ $post->title }}</div>
                        <div class="text-[0.58rem] text-slate-400 font-mono mt-0.5">/blog/{{ $post->slug }}</div>
                    </td>

                    {{-- Category --}}
                    <td class="py-4 px-4">
                        @if($post->category)
                        <span class="px-2.5 py-1 bg-[#ff6900]/10 text-[#ff6900] rounded-md text-[0.52rem] font-black uppercase tracking-widest">
                            {{ $post->category->name }}
                        </span>
                        @else
                        <span class="text-slate-300 text-[0.6rem] font-black">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="py-4 px-4 text-center">
                        @if($post->is_published)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[0.52rem] font-black uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-400 text-[0.52rem] font-black uppercase tracking-widest">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                        </span>
                        @endif
                    </td>

                    {{-- Date --}}
                    <td class="py-4 px-4">
                        <span class="text-[0.68rem] font-bold text-slate-500 tabular-nums">
                            {{ ($post->published_at ?? $post->updated_at)?->format('d M Y') }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="py-4 px-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($post->is_published)
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                               class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                            @endif
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white hover:border-[#1d293d] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Delete this article?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-lg bg-red-50 border border-red-100 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-24 text-center">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-[#f0f2f5] border border-slate-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </div>
                            <p class="text-[0.62rem] font-black text-slate-300 uppercase tracking-widest">No articles yet</p>
                            <a href="{{ route('admin.posts.create') }}" class="text-[0.6rem] text-[#ff6900] font-black uppercase tracking-widest hover:underline">
                                + Write First Article
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($posts->hasPages())
        <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex justify-center">
            {{ $posts->links() }}
        </div>
        @endif
    </x-admin-page-standard>
</div>
@endsection
