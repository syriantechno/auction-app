@extends('layouts.app')

@section('title'){{ $siteName ?? 'Motor Bazar' }} — Blog@endsection
@section('meta_description', 'Latest news, car tips, and auction insights from Motor Bazar.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Blog Header --}}
    <div class="text-center mb-14">
        <span class="text-[0.6rem] font-black text-[#ff6900] uppercase tracking-[0.3em] mb-3 block">Motor Bazar</span>
        <h1 class="text-5xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
            The <span class="text-[#ff6900]">Blog</span>
        </h1>
        <p class="text-slate-500 mt-4 text-sm font-medium max-w-xl mx-auto">
            Insights, tips, and news about cars, auctions, and the automotive world.
        </p>

        {{-- Category Filter --}}
        @if($categories->count())
        <div class="flex items-center justify-center flex-wrap gap-2 mt-8">
            <a href="{{ route('blog.index') }}"
               class="px-4 py-2 rounded-full text-[0.62rem] font-black uppercase tracking-widest transition-all
                      {{ !request('cat') ? 'bg-[#1d293d] text-white' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#ff6900] hover:text-[#ff6900]' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('blog.index', ['cat' => $cat->slug]) }}"
               class="px-4 py-2 rounded-full text-[0.62rem] font-black uppercase tracking-widest transition-all
                      {{ request('cat') == $cat->slug ? 'bg-[#ff6900] text-white' : 'bg-white border border-slate-200 text-slate-500 hover:border-[#ff6900] hover:text-[#ff6900]' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Posts Grid --}}
    @if($posts->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
        <article class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

            {{-- Cover Image --}}
            <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-[16/9] overflow-hidden bg-[#f0f2f5]">
                @if($post->featured_image)
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                </div>
                @endif
            </a>

            {{-- Content --}}
            <div class="p-6">
                {{-- Category Badge --}}
                @if($post->category)
                <span class="text-[0.52rem] font-black text-[#ff6900] uppercase tracking-widest bg-[#ff6900]/10 px-2.5 py-1 rounded-full">
                    {{ $post->category->name }}
                </span>
                @endif

                {{-- Title --}}
                <h2 class="mt-3 text-[0.95rem] font-black text-[#1d293d] leading-tight group-hover:text-[#ff6900] transition-colors line-clamp-2">
                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>

                {{-- Excerpt --}}
                @if($post->meta_description)
                <p class="mt-2 text-[0.72rem] text-slate-500 leading-relaxed line-clamp-2">
                    {{ $post->meta_description }}
                </p>
                @endif

                {{-- Footer --}}
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[0.58rem] font-bold text-slate-400 tabular-nums">
                        {{ ($post->published_at ?? $post->created_at)?->format('d M Y') }}
                    </span>
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="text-[0.58rem] font-black text-[#ff6900] uppercase tracking-widest hover:underline flex items-center gap-1">
                        Read More
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($posts->hasPages())
    <div class="mt-12 flex justify-center">
        {{ $posts->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-24">
        <div class="w-16 h-16 rounded-2xl bg-[#f0f2f5] border border-slate-200 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No articles yet</p>
        <p class="text-slate-300 text-sm mt-1">Check back soon for new content.</p>
    </div>
    @endif

</div>
@endsection
