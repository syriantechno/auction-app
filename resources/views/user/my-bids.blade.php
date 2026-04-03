@extends('layouts.app')

@section('title', 'مزايداتي - أوتومزاد')

@section('content')

{{-- Header --}}
<section class="relative pt-32 pb-12">
    <div class="absolute inset-0 bg-dark-800"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold-400/20 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        <h1 class="text-4xl font-black text-white mb-2">
            مزايداتي
        </h1>
        <p class="text-gray-500">جميع المزايدات التي شاركت فيها</p>
    </div>
</section>

<section class="relative py-12">
    <div class="absolute inset-0 bg-dark-800"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
        @if(isset($bids) && $bids->count() > 0)
        <div class="space-y-4">
            @foreach($bids as $bid)
            <div class="premium-card p-6 flex flex-col md:flex-row md:items-center gap-6">
                {{-- Car Image --}}
                <div class="w-full md:w-48 h-32 rounded-md overflow-hidden flex-shrink-0">
                    <img src="{{ $bid->auction->car->image_url ?? '/images/auction-listing-bg.png' }}"
                         alt="{{ $bid->auction->car->make ?? '' }}"
                         class="w-full h-full object-cover" loading="lazy">
                </div>

                {{-- Details --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-white mb-1">
                        {{ $bid->auction->car->year ?? '' }} {{ $bid->auction->car->make ?? '' }} {{ $bid->auction->car->model ?? '' }}
                    </h3>
                    <div class="flex items-center gap-3 text-xs text-gray-500">
                        <span>{{ $bid->created_at->format('Y/m/d H:i') }}</span>
                        @if($bid->auction->status === 'active')
                            <span class="inline-flex items-center gap-1 text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                مباشر
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Bid Amount --}}
                <div class="text-left md:text-center flex-shrink-0">
                    <div class="text-xs text-gray-500 mb-1">مزايدتك</div>
                    <div class="text-2xl font-black gold-text">{{ number_format($bid->amount) }} <span class="text-sm">ر.س</span></div>
                </div>

                {{-- Action --}}
                <a href="/auctions/{{ $bid->auction->id }}" class="flex-shrink-0 btn-outline-gold text-sm px-6 py-2.5 inline-flex items-center gap-2">
                    <span>عرض المزاد</span>
                    <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-24 glass-strong rounded-lg">
            <div class="w-20 h-20 mx-auto rounded-lg bg-gold-400/5 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-gray-400 text-xl font-bold mb-2">لم تشارك في أي مزاد بعد</p>
            <p class="text-gray-600 text-sm mb-8">تصفح المزادات المتاحة وابدأ المزايدة</p>
            <a href="/auctions" class="btn-gold px-8 py-3 inline-flex items-center gap-2">
                <span>تصفح المزادات</span>
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>

@endsection

