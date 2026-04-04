@extends('layouts.app')

@section('title'){{ $post->title }} — {{ $siteName ?? 'Motor Bazar' }}@endsection
@section('meta_description', $post->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($post->content['body'] ?? ''), 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 mb-8 text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">
        <a href="{{ route('blog.index') }}" class="hover:text-[#ff6900] transition-colors">Blog</a>
        <span class="text-slate-200">›</span>
        @if($post->category)
        <a href="{{ route('blog.index', ['cat' => $post->category->slug]) }}" class="hover:text-[#ff6900] transition-colors">
            {{ $post->category->name }}
        </a>
        <span class="text-slate-200">›</span>
        @endif
        <span class="text-slate-600 truncate max-w-xs">{{ $post->title }}</span>
    </div>

    {{-- Article Header --}}
    <header class="mb-10">
        @if($post->category)
        <span class="text-[0.58rem] font-black text-[#ff6900] uppercase tracking-widest bg-[#ff6900]/10 px-3 py-1.5 rounded-full">
            {{ $post->category->name }}
        </span>
        @endif

        <h1 class="mt-4 text-4xl lg:text-5xl font-black text-[#031629] uppercase italic tracking-tighter leading-tight">
            {{ $post->title }}
        </h1>

        @if($post->meta_description)
        <p class="mt-4 text-base text-slate-500 leading-relaxed font-medium">
            {{ $post->meta_description }}
        </p>
        @endif

        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-[#1d293d] flex items-center justify-center text-white font-black text-sm">
                MB
            </div>
            <div>
                <div class="text-[0.72rem] font-black text-[#1d293d]">Motor Bazar Editorial</div>
                <div class="text-[0.6rem] text-slate-400 font-bold mt-0.5">
                    {{ ($post->published_at ?? $post->created_at)?->format('d F Y') }}
                </div>
            </div>
        </div>
    </header>

    {{-- Featured Image --}}
    @if($post->featured_image)
    <div class="mb-10 rounded-2xl overflow-hidden aspect-[16/7] bg-[#f0f2f5]">
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
    </div>
    @endif

    {{-- Article Content --}}
    <article class="prose prose-slate prose-lg max-w-none
                    prose-headings:font-black prose-headings:text-[#031629] prose-headings:uppercase prose-headings:italic
                    prose-a:text-[#ff6900] prose-a:no-underline hover:prose-a:underline
                    prose-img:rounded-xl prose-img:shadow-md">
        {!! $post->content['body'] ?? '' !!}
    </article>

    {{-- Footer: Share + Navigation --}}
    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

        {{-- Back to blog --}}
        <a href="{{ route('blog.index') }}"
           class="flex items-center gap-2 text-[0.6rem] font-black text-slate-400 uppercase tracking-widest hover:text-[#ff6900] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            All Articles
        </a>

        {{-- Related category --}}
        @if($post->category)
        <a href="{{ route('blog.index', ['cat' => $post->category->slug]) }}"
           class="flex items-center gap-2 px-4 py-2 bg-[#ff6900]/10 text-[#ff6900] rounded-full text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#ff6900] hover:text-white transition-all">
            More in {{ $post->category->name }}
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
        @endif
    </div>

    {{-- Related Posts --}}
    @if($related->count())
    <div class="mt-16">
        <h2 class="text-2xl font-black text-[#031629] uppercase italic tracking-tighter mb-8">
            Related <span class="text-[#ff6900]">Articles</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $rel)
            <a href="{{ route('blog.show', $rel->slug) }}"
               class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all">
                @if($rel->featured_image)
                <div class="aspect-[16/9] overflow-hidden bg-[#f0f2f5]">
                    <img src="{{ $rel->featured_image }}" alt="{{ $rel->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                @endif
                <div class="p-4">
                    <div class="text-[0.7rem] font-black text-[#1d293d] group-hover:text-[#ff6900] transition-colors line-clamp-2">
                        {{ $rel->title }}
                    </div>
                    <div class="text-[0.55rem] text-slate-400 mt-1.5">
                        {{ ($rel->published_at ?? $rel->created_at)?->format('d M Y') }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
