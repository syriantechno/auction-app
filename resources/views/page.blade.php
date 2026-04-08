@extends('layouts.app')

@section('title', $page->title . ' - Motor Bazar')


@section('content')
    {{-- Hero --}}
    <section class="relative pt-32 pb-20 overflow-hidden"
             style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);">
        <div class="absolute inset-0 opacity-20">
            @if($page->hero_image)
                <img src="{{ $page->hero_image }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
            @endif
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-[#0f172a]/60"></div>

        <div class="relative max-w-4xl mx-auto px-6 text-center text-white">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white/80 mb-6 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-[#ff4605]"></span>
                Motor Bazar
            </div>
            <h1 class="text-4xl lg:text-6xl font-black tracking-tight leading-[1.1] mb-6">
                {{ $page->title }}
            </h1>
            @if($page->meta_description)
                <p class="text-white/60 text-lg max-w-2xl mx-auto leading-relaxed">{{ $page->meta_description }}</p>
            @endif
        </div>
    </section>

    {{-- Main Content --}}
    <div class="bg-[#e7e7e7] min-h-screen">
        <div class="max-w-4xl mx-auto px-6 py-16">
            <div class="bg-white rounded-2xl shadow-lg shadow-black/5 border border-slate-100 overflow-hidden">

                {{-- Content Header --}}
                <div class="px-10 pt-10 pb-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-[#ff4605] rounded-full"></div>
                        <h2 class="text-sm font-black uppercase tracking-widest text-slate-400">Content</h2>
                    </div>
                    <div class="text-xs text-slate-400 font-bold">
                        Updated {{ $page->updated_at->diffForHumans() }}
                    </div>
                </div>

                {{-- The actual page content --}}
                <div class="page-content px-10 py-10 text-slate-700" style="font-size:1.05rem;line-height:1.8;">
                    @if($page->content)
                        {!! $page->content !!}
                    @else
                        <p class="text-slate-400 italic">This page has no content yet.</p>
                    @endif
                </div>

                {{-- Footer bar --}}
                <div class="px-10 py-6 border-t border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Home
                    </a>
                    <a href="{{ route('auctions.index') }}"
                       class="inline-flex items-center gap-2 bg-[#ff4605] text-white px-5 py-2.5 rounded-xl text-sm font-black shadow-lg shadow-[#ff4605]/20 hover:bg-[#e03d04] transition-all">
                        Browse Auctions <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
