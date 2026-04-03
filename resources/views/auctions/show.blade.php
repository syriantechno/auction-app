@extends('layouts.app')

@section('title', ($auction->car->year ?? '') . ' ' . ($auction->car->make ?? '') . ' ' . ($auction->car->model ?? '') . ' - UniteCar')

@section('head')
<style>
    @php
        $defaultAuctionImages = [
            '/images/cars/car-main.png',
            '/images/cars/car-1.png',
            '/images/cars/car-2.png',
            '/images/cars/car-3.png',
        ];

        $auctionImage = $auction->car->image_url ?? $defaultAuctionImages[0];
        $auctionThumbImages = [
            $auctionImage,
            $defaultAuctionImages[1],
            $defaultAuctionImages[2],
        ];
    @endphp

    .thumb-btn {
        width: 110px;
        height: 110px;
        border-radius: 28px;
        overflow: hidden;
        margin-bottom: 20px;
        background: white;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        padding: 6px;
    }
    .thumb-btn.active { 
        border-color: #ff4605;
        box-shadow: 0 10px 15px -3px rgba(255, 70, 5, 0.3);
    }
    .thumb-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 22px;
    }
    
    .bid-input {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 20px;
        width: 100%;
        font-size: 1rem;
        font-weight: 700;
        outline: none;
        margin-bottom: 15px;
        transition: border-color 0.3s ease;
    }
    .bid-input:focus { border-color: #ff4605; }

    .place-bid-btn {
        background: #ff4605;
        color: white;
        font-weight: 800;
        padding: 18px;
        border-radius: 18px;
        width: 100%;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        letter-spacing: -0.01em;
        font-size: 0.95rem;
    }
    .place-bid-btn:hover { 
        background: #e03d04; 
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(255, 70, 5, 0.5);
    }

    .spec-pill {
        background: #ffffff;
        border-radius: 24px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        border: 1px solid rgba(0,0,0,0.02);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
    }
    .spec-icon {
        width: 48px;
        height: 48px;
        background: #f8fafc;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .tab-pill {
        padding: 10px 24px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #5a6a85;
        transition: all 0.3s ease;
    }
    .tab-pill.active {
        background: #ff4605;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="pt-32 pb-20 px-6 lg:px-12 max-w-[1440px] mx-auto min-h-screen">
    {{-- Breadcrumbs & Header Title --}}
    <div class="mb-10">
        <nav class="flex items-center gap-2 text-[0.8rem] text-gray-400 font-semibold mb-3">
            <span>Home</span> <i data-lucide="chevron-right" class="w-3.5"></i>
            <span>Search</span> <i data-lucide="chevron-right" class="w-3.5"></i>
            <span>{{ $auction->car->make ?? 'Ford' }}</span> <i data-lucide="chevron-right" class="w-3.5"></i>
            <span class="text-gray-600">Performance Vehicles</span>
        </nav>
        <div class="flex items-center gap-5">
            <button class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm hover:bg-gray-50 transition-all border border-gray-50">
                <i data-lucide="arrow-left" class="w-5"></i>
            </button>
            <h1 class="text-5xl font-black tracking-tight text-[#2a3547] capitalize">
                {{ $auction->car->year ?? '' }} {{ $auction->car->make ?? 'Ford' }} {{ $auction->car->model ?? '' }}
            </h1>
            @if($auction->status === 'coming_soon')
                <span class="px-6 py-2 bg-[#ff6900] text-white rounded-lg text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-500/20 animate-pulse">Coming Soon</span>
            @elseif($auction->status === 'active')
                <span class="px-6 py-2 bg-emerald-500 text-white rounded-lg text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20">Live Auction</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        {{-- Left Navigation: Thumbnails Column --}}
        <div class="col-span-1 hidden lg:flex flex-col">
            <div class="thumb-btn active">
                <img src="{{ $auctionThumbImages[0] }}" alt="Auction car image 1">
            </div>
            <div class="thumb-btn">
                <img src="{{ $auctionThumbImages[1] }}" class="opacity-50 grayscale" alt="Auction car image 2">
            </div>
            <div class="thumb-btn">
                <img src="{{ $auctionThumbImages[2] }}" class="opacity-50 grayscale" alt="Auction car image 3">
            </div>
        </div>

        {{-- Center Module: Hero Car Display --}}
        <div class="col-span-1 lg:col-span-7">
            <div class="w-full h-[580px] overflow-hidden rounded-[80px] p-10 flex items-center justify-center bg-transparent relative group">
                <img src="{{ $auctionImage }}" class="max-w-full max-h-full object-contain transform group-hover:scale-105 transition-transform duration-700" alt="Main auction car image">
                
                {{-- Decorative background elements to match the reference --}}
                <div class="absolute inset-0 -z-10 bg-radial-gradient from-white/20 to-transparent opacity-50"></div>
            </div>
        </div>

        {{-- Right Side: Strategic Bidding Dashboard --}}
        <div class="col-span-1 lg:col-span-4 self-stretch">
            <div class="floating-card h-full p-8 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <span class="text-[0.7rem] text-gray-400 font-extrabold uppercase tracking-widest block mb-2">Current Bid (<span id="stats-bid-count">{{ $auction->bids_count }}</span> Bids)</span>
                            <div class="text-4xl font-black text-[#2a3547] transition-all duration-300" id="display-price" data-price="{{ $auction->current_price ?? $auction->initial_price }}">US ${{ number_format($auction->current_price ?? $auction->initial_price, 0) }}</div>
                        </div>
                        <button class="w-12 h-12 flex items-center justify-center bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <i data-lucide="bookmark" class="w-5 text-[#2a3547]"></i>
                        </button>
                    </div>

                    <div class="space-y-4 mb-2">
                        <div class="flex justify-between items-center py-1.5">
                            <span class="text-sm text-gray-400 font-semibold tracking-tight">Technical Audit Score</span>
                            <span class="px-3 py-1 rounded-full text-[0.7rem] font-black uppercase tracking-widest {{ ($auction->car->latestInspection->overall_score ?? 0) >= 80 ? 'bg-emerald-50 text-emerald-600' : 'bg-orange-50 text-orange-600' }}">
                                {{ $auction->car->latestInspection->overall_score ?? 'N/A' }}/100
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1.5">
                            @if($auction->status === 'coming_soon')
                                <span class="text-sm text-gray-400 font-semibold tracking-tight">Bidding Opens In</span>
                                <span class="text-[0.95rem] font-black text-[#ff6900] tabular-nums tracking-wider auction-timer" data-expires="{{ $auction->start_at->toIso8601String() }}">Calculating...</span>
                            @else
                                <span class="text-sm text-gray-400 font-semibold tracking-tight">Time Remaining</span>
                                <span class="text-[0.95rem] font-black text-[#f44336] tabular-nums tracking-wider auction-timer" data-expires="{{ $auction->end_at->toIso8601String() }}">Calculating...</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center py-1.5 text-[#2a3547]">
                            <span class="text-sm text-gray-400 font-semibold tracking-tight">Bidding Eligibility</span>
                            <span class="text-sm font-extrabold">Open Access</span>
                        </div>
                        <div class="flex justify-between items-center py-1.5 text-[#2a3547]">
                            <span class="text-sm text-gray-400 font-semibold tracking-tight">Buyer Premium</span>
                            <span class="text-sm font-extrabold">$250.00 Fixed</span>
                        </div>
                    </div>
                </div>

                {{-- Real-time Bidding Feed --}}
                <div class="mt-8 bg-gray-50/50 rounded-lg p-6 border border-dashed border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="text-[0.6rem] font-black text-[#2a3547] uppercase tracking-widest">Global Bid Feed</span>
                    </div>
                    <div class="space-y-3 max-h-[200px] overflow-y-auto pr-2 custom-scroll" id="global-bid-feed">
                        @forelse($auction->bids->take(5) as $bid)
                        <div class="flex justify-between items-center bg-white p-3 rounded-md shadow-sm border border-black/5">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-[0.6rem]">
                                    {{ substr($bid->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-[0.7rem] font-black text-[#111827]">{{ $bid->user->name }}</div>
                                    <div class="text-[0.55rem] text-gray-400 font-bold tabular-nums">{{ $bid->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="text-[0.75rem] font-black text-[#111827] tabular-nums underline decoration-[#ff4605] decoration-2 underline-offset-4">${{ number_format($bid->amount) }}</div>
                        </div>
                        @empty
                        <div class="py-4 text-center text-[0.6rem] text-gray-300 font-black uppercase tracking-widest">Silent Market. Be the first to bid.</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-12">
                    @if($auction->status === 'active')
                        <div class="text-[0.7rem] font-black uppercase tracking-[0.2em] mb-4 text-[#2a3547] opacity-60">Strategic Action Pad</div>
                        <form action="{{ route('auctions.placeBid', $auction) }}" method="POST" id="bid-form">
                            @csrf
                            @php
                                $nextBid = ($auction->current_price ?? $auction->initial_price) + 500;
                            @endphp
                            <input type="hidden" name="amount" value="{{ $nextBid }}">
                            
                            <button type="submit" id="bid-submit-btn" class="w-full h-20 bg-[#ff4605] text-white rounded-lg font-black shadow-2xl shadow-orange-500/30 hover:scale-[1.02] active:scale-95 transition-all flex flex-col items-center justify-center gap-1 group">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="zap" class="w-5 h-5 text-white animate-pulse"></i>
                                    <span class="text-xs uppercase tracking-widest leading-none">Place Quick Bid</span>
                                </div>
                                <div class="text-2xl italic tracking-tighter leading-none mt-1">+ $<span id="btn-total-increment">500</span> (Total: $<span id="btn-total-value">{{ number_format($nextBid) }}</span>)</div>
                            </button>
                        </form>
                    @else
                        <div class="bg-slate-50 p-6 rounded-lg border border-dashed border-gray-200 text-center">
                            <i data-lucide="lock" class="w-8 h-8 text-slate-300 mx-auto mb-4"></i>
                            <div class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                Bidding is locked until<br>
                                <span class="text-[#ff6900]">{{ $auction->start_at->format('M d, H:i') }}</span>
                            </div>
                        </div>
                    @endif
                    <p class="text-[0.6rem] text-center text-gray-400 mt-6 font-black uppercase tracking-[0.2em]">
                        Certified protocol secured by Motor Bazar
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Technical Details --}}
    @if($auction->car->latestInspection)
    <div class="mt-12 bg-white rounded-[40px] p-10 border border-[#f1f5f9] shadow-sm">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-14 h-14 bg-[#111827] rounded-lg flex items-center justify-center text-white shadow-xl shadow-black/10">
                <i data-lucide="shield-alert" class="w-7"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-[#111827]">Expert Audit Summary</h3>
                <p class="text-[0.7rem] text-[#adb5bd] font-black uppercase tracking-widest">Certified Technical Scorecard</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            <div class="p-6 bg-[#1d293d] text-white rounded-lg shadow-xl flex flex-col items-center justify-center">
                <div class="text-4xl font-black text-[#ff6900] mb-2">{{ $auction->car->latestInspection->overall_score }}%</div>
                <div class="text-[0.5rem] font-black text-white/40 uppercase tracking-[0.2em]">Overall Grade</div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 flex flex-col items-center">
                <div class="text-2xl font-black text-[#2a3547] mb-2">{{ $auction->car->latestInspection->engine_score }}%</div>
                <div class="text-[0.55rem] font-black text-gray-400 uppercase tracking-widest">Engine & Gear</div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 flex flex-col items-center">
                <div class="text-2xl font-black text-[#2a3547] mb-2">{{ $auction->car->latestInspection->paint_score }}%</div>
                <div class="text-[0.55rem] font-black text-gray-400 uppercase tracking-widest">Paint & Body</div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 flex flex-col items-center">
                <div class="text-2xl font-black text-[#2a3547] mb-2">{{ $auction->car->latestInspection->interior_score }}%</div>
                <div class="text-[0.55rem] font-black text-gray-400 uppercase tracking-widest">Interior</div>
            </div>
            <div class="p-6 bg-white rounded-lg border border-gray-100 flex flex-col items-center">
                <div class="text-2xl font-black text-[#2a3547] mb-2">{{ $auction->car->latestInspection->tires_score }}%</div>
                <div class="text-[0.55rem] font-black text-gray-400 uppercase tracking-widest">Tires & Suspension</div>
            </div>
        </div>
        
        <div class="mt-10 p-8 bg-slate-50 rounded-[32px] border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-md bg-orange-100 flex items-center justify-center text-[#ff6900] shrink-0">
                    <i data-lucide="message-square" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest block mb-1">Executive Summary</span>
                    <p class="text-sm font-bold text-[#031629] leading-relaxed italic">"{{ $auction->car->latestInspection->expert_summary ?? 'Technical audit cleared with no significant discrepancies.' }}"</p>
                </div>
            </div>
            <a href="{{ route('admin.inspections.show', $auction->car->latestInspection->id ?? 0) }}" class="px-8 py-4 bg-[#1d293d] text-white rounded-lg font-black text-[0.65rem] uppercase tracking-[0.2em] hover:bg-[#ff6900] transition-all shadow-xl shadow-slate-200">
                Full Anatomy Report
            </a>
        </div>
    </div>
    @endif

    {{-- Comprehensive Information Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-12">
        {{-- Auction Details --}}
        <div class="lg:col-span-4">
            <div class="floating-card h-full p-8 border-none bg-white">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <span class="text-[0.6rem] text-gray-400 font-black uppercase tracking-widest mb-1 block">Performance Vehicles</span>
                        <h3 class="text-xl font-black text-[#2a3547]">Auction Information</h3>
                    </div>
                    <div class="flex gap-3">
                        <button class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-full hover:bg-gray-100"><i data-lucide="link" class="w-4 opacity-50"></i></button>
                        <button class="w-10 h-10 flex items-center justify-center bg-gray-50 rounded-full hover:bg-gray-100"><i data-lucide="share-2" class="w-4 opacity-50"></i></button>
                    </div>
                </div>

                <div class="space-y-5 text-sm font-bold">
                    <div class="flex justify-between items-center"><span class="text-gray-400">Auction ID</span> <span class="text-[#2a3547]">3049029123</span></div>
                    <div class="flex justify-between items-center"><span class="text-gray-400">Auction Date</span> <span class="text-[#2a3547]">05/26/2023 08:59 am</span></div>
                    <div class="flex justify-between items-center"><span class="text-gray-400">Auction Location</span> <span class="text-[#2a3547]">4140 Parker Rd. Allentown</span></div>
                    <div class="flex justify-between items-center"><span class="text-gray-400">Last Auction Update</span> <span class="text-[#2a3547]">05/14/2023 03:09 pm</span></div>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-50 flex items-center gap-5">
                    <div class="relative">
                        <img src="https://i.pravatar.cc/150?u=phillip" class="w-14 h-14 rounded-full border-2 border-white shadow-sm object-cover">
                        <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5"><i data-lucide="check-circle" class="w-4 h-4 text-blue-500 fill-blue-500/10"></i></div>
                    </div>
                    <div class="flex-1">
                        <div class="font-black text-[0.9rem] text-[#2a3547]">Phillip Roberto</div>
                        <div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wide">100% positive feedback</div>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-[0.7rem] font-black text-[#2a3547]">35 Item Sold</div>
                </div>
            </div>
        </div>

        {{-- Technical Specs --}}
        <div class="lg:col-span-8">
            <div class="floating-card p-8 bg-white border-none">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                    <div>
                        <span class="text-[0.6rem] text-gray-400 font-black uppercase tracking-widest mb-1 block">Vehicles Details</span>
                        <h3 class="text-2xl font-black text-[#2a3547]">Key Specifications</h3>
                    </div>
                    <div class="flex flex-wrap gap-2 bg-[#f8fafc] p-1.5 rounded-lg border border-gray-50">
                        <button class="tab-pill">Wheels & Tyre</button>
                        <button class="tab-pill active shadow-sm">Engine Detail</button>
                        <button class="tab-pill">Suspension</button>
                        <button class="tab-pill">Electrical System</button>
                        <button class="tab-pill">Steering</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="spec-pill group hover:border-[#d9e685] transition-all duration-300">
                        <div class="spec-icon group-hover:bg-lime-50 transition-colors"><i data-lucide="gauge" class="text-amber-500 w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">134,536 mi (ACTUAL)</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Odo Meter</div></div>
                    </div>
                    <div class="spec-pill group hover:border-[#d9e685] transition-all">
                        <div class="spec-icon group-hover:bg-lime-50"><i data-lucide="zap" class="text-blue-500 w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">207 hp, 500 Nm</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Power & Torque</div></div>
                    </div>
                    <div class="spec-pill group hover:border-[#d9e685] transition-all">
                        <div class="spec-icon group-hover:bg-lime-50"><i data-lucide="settings" class="text-[#2a3547] w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">4x4, 10 Speed</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Drive Type & Gear Box</div></div>
                    </div>
                    <div class="spec-pill group hover:border-[#d9e685] transition-all">
                        <div class="spec-icon group-hover:bg-lime-50"><i data-lucide="fuel" class="text-emerald-500 w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">2.0L Diesel Engine</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Engine Type</div></div>
                    </div>
                    <div class="spec-pill group hover:border-[#d9e685] transition-all">
                        <div class="spec-icon group-hover:bg-lime-50"><i data-lucide="activity" class="text-[#ff6900] w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">Automatic</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Transmission</div></div>
                    </div>
                    <div class="spec-pill group hover:border-[#d9e685] transition-all">
                        <div class="spec-icon group-hover:bg-lime-50"><i data-lucide="hash" class="text-[#2a3547] w-5"></i></div>
                        <div><div class="text-[0.9rem] font-black text-[#2a3547] mb-0.5">2T3YL4DVOEW******</div><div class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-wider">Vin Number</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Thumbnail navigation
        const thumbBtns = document.querySelectorAll('.thumb-btn');
        const mainImage = document.querySelector('.col-span-1.lg\\:col-span-7 img');
        
        thumbBtns.forEach((btn, index) => {
            btn.addEventListener('click', function() {
                // Remove active from all
                thumbBtns.forEach(b => {
                    b.classList.remove('active');
                    const img = b.querySelector('img');
                    if(img) img.classList.add('opacity-50', 'grayscale');
                });
                
                // Add active to clicked
                this.classList.add('active');
                const clickedImg = this.querySelector('img');
                if(clickedImg) {
                    clickedImg.classList.remove('opacity-50', 'grayscale');
                    // Update main image
                    if(mainImage && clickedImg.src) {
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = clickedImg.src;
                            mainImage.style.opacity = '1';
                        }, 200);
                    }
                }
            });
        });
        
        // REAL-TIME AUCTION COUNTDOWN ENGINE
        function initCountdowns() {
            const updateCountdowns = () => {
                document.querySelectorAll('.auction-timer').forEach(el => {
                    const expiresAt = new Date(el.getAttribute('data-expires')).getTime();
                    const now = new Date().getTime();
                    const diff = expiresAt - now;

                    if (diff <= 0) {
                        el.innerHTML = '<span class="text-slate-400">SESSION CLOSED</span>';
                        return;
                    }

                    const h = Math.floor(diff / (1000 * 60 * 60));
                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                    el.innerText = (h > 0 ? h + 'h ' : '') + 
                                  String(m).padStart(2, '0') + 'm ' + 
                                  String(s).padStart(2, '0') + 's';
                });
            };
            
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        }

        initCountdowns();

        // REAL-TIME SYNC ENGINE (POLLING & AJAX)
        const bidForm = document.getElementById('bid-form');
        const displayPrice = document.getElementById('display-price');
        const bidCountText = document.getElementById('stats-bid-count');
        const bidFeedContainer = document.getElementById('global-bid-feed');
        const btnTotalValue = document.getElementById('btn-total-value');

        const priceFormatter = new Intl.NumberFormat('en-US', {
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        });

        const initialPriceValue = Number(displayPrice ? displayPrice.getAttribute('data-price') : 0) || 0;
        let currentPriceValue = initialPriceValue;
        let nextBidValue = currentPriceValue + 500;

        function formatMoney(amount) {
            return priceFormatter.format(Math.max(0, Math.round(Number(amount) || 0)));
        }

        function renderAuctionPrice(amount) {
            const normalizedAmount = Math.max(0, Math.round(Number(amount) || 0));

            currentPriceValue = normalizedAmount;
            nextBidValue = currentPriceValue + 500;

            if (displayPrice) {
                displayPrice.innerText = `US $${formatMoney(currentPriceValue)}`;
                displayPrice.setAttribute('data-price', String(currentPriceValue));
            }

            if (btnTotalValue) {
                btnTotalValue.innerText = formatMoney(nextBidValue);
            }

            const bidFormLocal = document.getElementById('bid-form');
            if (bidFormLocal) {
                const input = bidFormLocal.querySelector('input[name="amount"]');
                if (input) {
                    input.value = String(nextBidValue);
                }
            }
        }

        let isSubmitting = false;
        let syncFallbackTimer = null;

        if (bidForm) {
            bidForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                if (isSubmitting) return;

                // --- CALIBRATED LIGHTNING OPTIMISTIC UPDATE ---
                // WE USE data-price ATTRIBUTE FOR ABSOLUTE ACCURACY
                const currentPriceOnScreen = currentPriceValue || initialPriceValue;
                
                const targetBid = currentPriceOnScreen + 500;
                const afterTargetBid = targetBid + 500;
                
                const optimisticData = {
                    current_price_formatted: formatMoney(targetBid),
                    next_bid_amount: afterTargetBid,
                    next_bid_formatted: `$${formatMoney(afterTargetBid)}`,
                    bids_count: (parseInt(bidCountText ? bidCountText.innerText : '0') || 0) + 1,
                    latest_bids: [{
                        user_name: 'You',
                        user_initial: 'Y',
                        amount: `$${formatMoney(targetBid)}`,
                        time: 'Just now'
                    }, ...Array.from(bidFeedContainer ? bidFeedContainer.querySelectorAll('.flex.justify-between') : []).slice(0, 4).map(el => {
                        const nameEl = el.querySelector('.text-\\[0\\.7rem\\]');
                        const name = nameEl ? nameEl.innerText : 'User';
                        const amountEl = el.querySelector('.tabular-nums.underline');
                        const timeEl = el.querySelector('.text-\\[0\\.55rem\\]');
                        return {
                            user_name: name,
                            user_initial: name.substring(0,1),
                            amount: amountEl ? amountEl.innerText : '$0',
                            time: timeEl ? timeEl.innerText : '...'
                        };
                    })]
                };

                syncUI(optimisticData);
                
                // Set the exact value for the server
                const amountInputLocal = bidForm.querySelector('input[name="amount"]');
                if(amountInputLocal) amountInputLocal.value = targetBid;
                // ------------------------------------

                isSubmitting = true;
                const submitBtn = document.getElementById('bid-submit-btn');
                if(submitBtn) submitBtn.style.opacity = '0.7';

                try {
                    const formData = new FormData(bidForm);
                    const res = await fetch(bidForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if (data.success) {
                        syncUI(data); // Perfect Confirmed Data
                    } else if (data.error) {
                        console.error("[Bid Error]", data.error);
                    }
                } catch (err) {
                    console.error("Transmission Error", err);
                } finally {
                    isSubmitting = false;
                    if(submitBtn) submitBtn.style.opacity = '1';
                }
            });
        }

        function syncUI(data) {
            if (!data) return;
            
            // 1. Update Price Labels - Force Pure Integer Match
            let priceVal = data.current_price || (data.current_price_formatted ? parseInt(String(data.current_price_formatted).replace(/[^\d]/g, ''), 10) : 0);
            if (priceVal > 0) {
                const previousPrice = currentPriceValue;
                renderAuctionPrice(priceVal);

                if (displayPrice && previousPrice !== currentPriceValue) {
                    displayPrice.classList.add('text-emerald-500', 'scale-110');
                    setTimeout(() => displayPrice.classList.remove('text-emerald-500', 'scale-110'), 600);
                }
            }
            
            if (bidCountText && data.bids_count) {
                bidCountText.innerText = data.bids_count;
            }

            // 2. Update Action Button Total
            if (btnTotalValue && data.next_bid_amount) {
                nextBidValue = Math.max(0, Math.round(Number(data.next_bid_amount) || 0));
                btnTotalValue.innerText = formatMoney(nextBidValue);
            }
            
            const bidFormLocal = document.getElementById('bid-form');
            if (bidFormLocal && data.next_bid_amount) {
                const input = bidFormLocal.querySelector('input[name="amount"]');
                if(input) input.value = String(Math.max(0, Math.round(Number(data.next_bid_amount) || 0)));
            }

            // 3. Update Bid Feed
            if (data.latest_bids && bidFeedContainer) {
                let html = '';
                data.latest_bids.forEach(bid => {
                    const cleanAmount = bid.amount.split('.')[0];
                    html += `
                        <div class="flex justify-between items-center bg-white p-3 rounded-md shadow-sm border border-black/5 animate-in fade-in slide-in-from-top-1 duration-500">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg ${bid.user_name === 'You' || bid.user_name === 'Demo Bidder' ? 'bg-[#ff6900]' : 'bg-zinc-900'} text-white flex items-center justify-center font-bold text-[0.6rem]">${bid.user_initial}</div>
                                <div>
                                    <div class="text-[0.7rem] font-black text-[#111827]">${bid.user_name}</div>
                                    <div class="text-[0.55rem] text-gray-400 font-bold tabular-nums">${bid.time}</div>
                                </div>
                            </div>
                            <div class="text-[0.75rem] font-black text-[#111827] tabular-nums underline decoration-[#ff4605] decoration-2 underline-offset-4">${cleanAmount}</div>
                        </div>
                    `;
                });
                bidFeedContainer.innerHTML = html;
            }
        }

        // --- REAL-TIME WEBSOCKET HUB (SUB-SECOND SYNC) ---
        function stopAuctionSyncFallback() {
            if (syncFallbackTimer) {
                clearInterval(syncFallbackTimer);
                syncFallbackTimer = null;
            }
        }

        function startAuctionSyncFallback() {
            if (syncFallbackTimer) {
                return;
            }

            syncFallbackTimer = setInterval(async () => {
                try {
                    const response = await fetch(@json(route('auctions.sync', $auction)), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();
                    if (data && data.success) {
                        syncUI(data);
                    }
                } catch (error) {
                    console.warn('Auction sync fallback failed', error);
                }
            }, 15000);
        }

        if (window.Echo) {
            const echoConnection = window.Echo.connector?.pusher?.connection;

            if (echoConnection) {
                echoConnection.bind('state_change', ({ current }) => {
                    if (current === 'connected') {
                        stopAuctionSyncFallback();
                    }

                    if (current === 'disconnected' || current === 'unavailable' || current === 'failed') {
                        startAuctionSyncFallback();
                    }
                });
            }

            window.Echo.channel('auction.' + {{ $auction->id }})
                .listen('BidPlaced', (e) => {
                    console.log("[Reverb] Instant Sync Received", e);
                    
                    const realtimeData = {
                        current_price: e.current_price,
                        current_price_formatted: formatMoney(e.current_price),
                        next_bid_amount: e.current_price + 500,
                        bids_count: (parseInt(bidCountText ? bidCountText.innerText : '0') || 0) + 1,
                        latest_bids: [{
                            user_name: e.user_name,
                            user_initial: e.user_name.substring(0,1),
                            amount: `$${formatMoney(e.current_price)}`,
                            time: 'Just now'
                        }]
                    };
                    
                    syncUI(realtimeData);
                });

            if (!echoConnection || echoConnection.state !== 'connected') {
                startAuctionSyncFallback();
            }
        } else {
            startAuctionSyncFallback();
        }
    });
</script>
@endsection

