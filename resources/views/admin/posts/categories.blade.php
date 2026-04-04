@extends('admin.layout')
@section('title', 'Blog Categories')

@section('content')
<div class="pb-20 space-y-5">

    {{-- ══ HEADER ══ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-amber-400 border-[3px] border-[#f8fafc]"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    Blog <span class="text-[#ff6900]">Categories</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    Organize articles by topic
                </p>
            </div>
        </div>
        <a href="{{ route('admin.posts.index') }}"
           class="px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#1d293d] flex items-center gap-2 text-[0.6rem] font-black uppercase tracking-widest transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Articles
        </a>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-white border border-emerald-200 text-emerald-700 px-5 py-3 rounded-lg text-[0.72rem] font-bold shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 items-start">

        {{-- ═══ LEFT: Categories List ═══ --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <span class="text-[0.6rem] font-black text-[#1d293d] uppercase tracking-widest">All Categories</span>
                    <span class="px-2 py-0.5 bg-[#ff6900]/10 text-[#ff6900] rounded-md text-[0.52rem] font-black uppercase tracking-widest">{{ $categories->count() }}</span>
                </div>

                <div class="divide-y divide-slate-100 bg-[#f0f2f5]">
                    @forelse($categories as $category)
                    <div class="flex items-center gap-4 px-5 py-3 bg-white border-b border-slate-100 group hover:bg-slate-50/50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-[#ff6900]/10 border border-[#ff6900]/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[0.75rem] font-black text-[#1d293d]">{{ $category->name }}</div>
                            <div class="text-[0.55rem] text-slate-400 font-mono">/blog?cat={{ $category->slug }}</div>
                        </div>
                        <span class="text-[0.6rem] font-black text-slate-400 tabular-nums">
                            {{ $category->posts_count }} article{{ $category->posts_count != 1 ? 's' : '' }}
                        </span>

                        {{-- Edit inline --}}
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                              class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
                            @csrf @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}"
                                   class="h-8 bg-white border border-slate-200 rounded-lg px-2.5 text-[0.7rem] font-bold text-[#1d293d] outline-none focus:border-[#ff6900] w-32 transition-all">
                            <button class="w-8 h-8 bg-[#1d293d] text-white rounded-lg flex items-center justify-center hover:bg-[#ff6900] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </button>
                        </form>

                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete {{ $category->name }}? Posts will become uncategorized.')">
                            @csrf @method('DELETE')
                            <button class="w-8 h-8 bg-red-50 border border-red-100 text-red-400 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="py-20 text-center bg-white">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#f0f2f5] border border-slate-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                            </div>
                            <p class="text-[0.6rem] font-black text-slate-300 uppercase tracking-widest">No categories yet</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT: Add Category ═══ --}}
        <div class="sticky top-6">
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-3 bg-[#1d293d]">
                    <div class="w-7 h-7 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    </div>
                    <div class="text-[0.62rem] font-black text-white uppercase tracking-widest">Add Category</div>
                </div>
                <div class="p-5 bg-[#f0f2f5] space-y-3">
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Category Name</label>
                            <input type="text" name="name" required
                                   class="w-full h-10 bg-white border border-slate-200 rounded-lg px-3 text-[0.82rem] font-bold text-[#1d293d] outline-none focus:border-[#ff6900] transition-all"
                                   placeholder="e.g. Car Tips">
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-[#ff6900] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#e55e00] transition-all shadow-md">
                            Create Category
                        </button>
                    </form>

                    @if($errors->any())
                    <div class="text-red-500 text-[0.62rem] font-bold">
                        {{ $errors->first() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
