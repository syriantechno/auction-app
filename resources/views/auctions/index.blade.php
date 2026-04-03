@extends('layouts.app')

@section('title', 'Browse Auctions - UniteCar')

@section('content')

{{-- Hero Header --}}
<section class="relative pt-40 pb-20 overflow-hidden hero-gradient">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8">
            <div>
                <nav class="flex items-center gap-2 text-[0.7rem] text-gray-400 font-black uppercase tracking-widest mb-4">
                    <span>Home</span> <i data-lucide="chevron-right" class="w-3"></i>
                    <span class="text-gray-600">Browse Auctions</span>
                </nav>
                <h1 class="text-5xl md:text-7xl font-black text-[#2a3547] leading-none tracking-tight">
                    Premium <br> <span class="opacity-50 italic">Inventory.</span>
                </h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white px-5 py-3 rounded-lg shadow-sm border border-white/50 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-[#5a6a85] uppercase tracking-widest">{{ $auctions->where('status', 'active')->count() }} Live Now</span>
                </div>
                <div class="bg-gray-100/50 px-5 py-3 rounded-lg flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $auctions->count() }} Total Listings</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Auctions Grid --}}
<section class="max-w-[1440px] mx-auto px-6 lg:px-12 py-12">
    @if(request('search'))
        <div class="mb-10 flex items-center gap-3 text-[#5a6a85] font-bold">
            <i data-lucide="search" class="w-5 transform -rotate-90"></i>
            <span>Results for "{{ request('search') }}"</span>
            <a href="{{ route('auctions.index') }}" class="ml-4 text-xs bg-white px-3 py-1.5 rounded-lg border border-gray-100 hover:bg-gray-50 transition-all">Clear Search</a>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        @forelse($auctions as $auction)
            <a href="{{ route('auctions.show', $auction) }}" class="floating-card overflow-hidden group card-hover flex flex-col h-full bg-white">
                {{-- Image Container --}}
                <div class="relative h-64 overflow-hidden p-6 bg-[#f8fafc]">
                    <img src="{{ $auction->car->image_url ?? '/images/cars/car-main.jpg' }}"
                         alt="{{ $auction->car->make ?? '' }} {{ $auction->car->model ?? '' }}"
                         class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700 car-card-image">
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-6 left-6">
                        @if($auction->status === 'active')
                            <span class="bg-white/95 backdrop-blur px-4 py-1.5 rounded-full text-[0.65rem] font-black uppercase tracking-widest flex items-center gap-2 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-lime-500 rounded-full animate-pulse"></span>
                                Live Auction
                            </span>
                        @else
                            <span class="bg-gray-800/90 text-white px-4 py-1.5 rounded-full text-[0.65rem] font-black uppercase tracking-widest flex items-center gap-2 shadow-sm">
                                <i data-lucide="calendar" class="w-3"></i>
                                Coming Soon
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Listing Details --}}
                <div class="p-8 flex flex-col flex-1 justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[0.65rem] text-gray-400 font-bold uppercase tracking-widest mb-3">
                            <span>{{ $auction->car->year }}</span>
                            <span class="text-gray-200">/</span>
                            <span>{{ $auction->car->make }}</span>
                        </div>
                        <h3 class="text-2xl font-black text-[#2a3547] mb-1 group-hover:text-lime-600 transition-colors">
                            {{ $auction->car->model }}
                        </h3>
                        <p class="text-[0.7rem] text-gray-400 font-bold mb-6 tracking-wide">{{ $auction->car->trim ?? 'Luxury Performance Edition' }}</p>
                        
                        <div class="grid grid-cols-2 gap-3 mb-8">
                            <div class="bg-gray-50/80 px-4 py-2.5 rounded-lg flex items-center gap-3">
                                <i data-lucide="gauge" class="w-4 text-gray-400"></i>
                                <span class="text-[0.7rem] font-black text-[#5a6a85]">{{ number_format($auction->car->mileage ?? 0) }} mi</span>
                            </div>
                            <div class="bg-gray-50/80 px-4 py-2.5 rounded-lg flex items-center gap-3">
                                <i data-lucide="users" class="w-4 text-gray-400"></i>
                                <span class="text-[0.7rem] font-black text-[#5a6a85]">{{ $auction->bids->count() }} Bids</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <div>
                            <span class="text-[0.65rem] text-gray-400 font-black uppercase tracking-widest block mb-1">Current Bid</span>
                            <div class="text-2xl font-black text-[#2a3547] tabular-nums">${{ number_format($auction->current_price ?? $auction->initial_price, 2) }}</div>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-[#d9e685]/20 flex items-center justify-center text-[#2a3547] group-hover:bg-[#d9e685] group-hover:scale-110 transition-all duration-300">
                            <i data-lucide="arrow-up-right" class="w-5"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-32 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i data-lucide="search-x" class="w-10 text-gray-200"></i>
                </div>
                <h3 class="text-2xl font-black text-[#2a3547] mb-2">No auctions found</h3>
                <p class="text-gray-400 font-bold uppercase tracking-widest text-[0.7rem]">Try adjusting your search criteria</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Simulation --}}
    @if($auctions->count() > 0)
        <div class="mt-20 flex justify-center gap-2">
            <button class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-sm border border-gray-50 text-gray-300 pointer-events-none transition-all"><i data-lucide="chevron-left"></i></button>
            <button class="w-12 h-12 rounded-lg bg-[#111827] text-white font-black shadow-lg">1</button>
            <button class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-sm border border-gray-50 text-gray-500 hover:bg-gray-50 transition-all">2</button>
            <button class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-sm border border-gray-50 text-gray-500 hover:bg-gray-50 transition-all"><i data-lucide="chevron-right"></i></button>
        </div>
    @endif
</section>

@endsection

@section('head')
<style>
    .hero-gradient {
        background: radial-gradient(circle at 10% 20%, rgba(217, 230, 133, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(217, 230, 133, 0.1) 0%, transparent 40%);
    }
    .card-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 50px -12px rgba(0, 0, 0, 0.08);
    }
</style>
@endsection

