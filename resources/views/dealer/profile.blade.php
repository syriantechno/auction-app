@extends('layouts.app')
@section('title', $dealer->name . ' — Dealer Profile · Motor Bazar')

@section('head')
<style>
    /* ── HERO ────────────────────────────────────── */
    .profile-hero {
        background: linear-gradient(135deg, #0a0d14 0%, #1a1d26 50%, #0a0d14 100%);
        position: relative; overflow: hidden;
    }
    .profile-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse 80% 60% at 65% 50%, rgba(255,105,0,0.13) 0%, transparent 70%);
    }
    .hero-grid {
        position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    /* ── TABS ────────────────────────────────────── */
    .tab-btn { transition: all 0.25s ease; }
    .tab-btn.active {
        background: #1d293d; color: white;
        box-shadow: 0 8px 24px rgba(29,41,61,0.18);
    }
    .tab-btn:not(.active) {
        background: white; color: #64748b;
        border: 1.5px solid #e2e8f0;
    }
    .tab-btn:not(.active):hover { border-color: #ff6900; color: #ff6900; }

    /* ── AUCTION CARD (Task-style horizontal) ──── */
    .deal-card {
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 2rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    }
    @media (min-width: 768px) { .deal-card { flex-direction: row; } }
    .deal-card:hover {
        box-shadow: 0 24px 60px rgba(255,105,0,0.08);
        transform: translateY(-3px);
    }
    .deal-card.won-card {
        border-color: #d1fae5;
    }
    .deal-card.won-card:hover {
        box-shadow: 0 24px 60px rgba(16,185,129,0.08);
    }

    /* ── CAR VISUAL PANEL ────────────────────────── */
    .car-panel {
        width: 100%; flex-shrink: 0;
        position: relative; overflow: hidden;
        background: #1d293d;
        min-height: 180px;
    }
    @media (min-width: 768px) { .car-panel { width: 220px; } }
    .car-panel img.car-bg {
        width: 100%; height: 100%; object-fit: cover;
        opacity: 0.65; filter: brightness(1.1) saturate(1.1);
        transition: transform 0.7s ease;
    }
    .deal-card:hover .car-bg { transform: scale(1.1); }
    .brand-logo-wrap {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 2;
        transition: transform 0.5s ease;
    }
    .deal-card:hover .brand-logo-wrap { transform: scale(1.18); }
    .brand-logo-inner {
        width: 80px; height: 80px; border-radius: 50%;
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.4);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        display: flex; align-items: center; justify-content: center; padding: 16px;
    }
    .car-ref {
        position: absolute; bottom: 16px; left: 16px; right: 16px; z-index: 3;
        background: rgba(29,41,61,0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; padding: 10px 12px;
    }

    /* ── STATUS BADGE ────────────────────────────── */
    .status-badge-abs {
        position: absolute; top: 14px; left: 14px; z-index: 3;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.55rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.14em;
    }

    /* ── FADE UP ─────────────────────────────────── */
    @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.55s ease both; }
    .fade-up:nth-child(1) { animation-delay:0.04s; }
    .fade-up:nth-child(2) { animation-delay:0.10s; }
    .fade-up:nth-child(3) { animation-delay:0.16s; }
    .fade-up:nth-child(4) { animation-delay:0.22s; }
</style>
@endsection

@section('content')

{{-- ══ HERO ═════════════════════════════════════════════════════ --}}
<div class="profile-hero pt-32 pb-16 px-6">
    <div class="hero-grid"></div>
    <div class="max-w-6xl mx-auto relative z-10">

        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs font-black uppercase tracking-widest mb-10 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back
        </a>

        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-10">

            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-2xl border-2 border-[#ff6900]/40 shadow-2xl shadow-black/40 bg-[#ff6900]/10 flex items-center justify-center flex-shrink-0">
                <span class="text-3xl font-black text-[#ff6900] uppercase italic">{{ strtoupper(substr($dealer->name,0,2)) }}</span>
            </div>

            {{-- Name & Info --}}
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-4xl font-black text-white uppercase italic tracking-tighter leading-none">{{ $dealer->name }}</h1>
                    @if($won->count() > 0)
                    <span class="px-3 py-1.5 bg-[#ff6900]/15 border border-[#ff6900]/30 text-[#ff6900] rounded-full text-[0.55rem] font-black uppercase tracking-widest">
                        ✦ Verified Buyer
                    </span>
                    @endif
                </div>
                <p class="text-white/40 text-sm font-bold mb-1">Member since {{ $dealer->created_at->format('M Y') }}</p>
                @if(auth()->check() && auth()->id() === $dealer->id)
                <p class="text-white/20 text-xs font-medium">{{ $dealer->email }}</p>
                @endif

                {{-- Quick stat pills --}}
                <div class="flex flex-wrap items-center gap-6 mt-6">
                    @foreach([
                        ['v' => number_format($totalBids),       'l' => 'Total Bids',    'c' => 'text-white'],
                        ['v' => $won->count(),                   'l' => 'Auctions Won',  'c' => 'text-[#ff6900]'],
                        ['v' => $participating->count(),          'l' => 'Active Bids',   'c' => 'text-emerald-400'],
                        ['v' => $winRate . '%',                   'l' => 'Win Rate',      'c' => 'text-sky-400'],
                        ['v' => '$' . number_format($highestBid), 'l' => 'Top Bid',       'c' => 'text-white'],
                    ] as $i => $s)
                    @if($i > 0)<div class="w-px h-8 bg-white/10"></div>@endif
                    <div class="text-center">
                        <div class="text-2xl font-black {{ $s['c'] }} tabular-nums">{{ $s['v'] }}</div>
                        <div class="text-[0.55rem] font-black text-white/35 uppercase tracking-widest mt-0.5">{{ $s['l'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right stat cards --}}
            <div class="grid grid-cols-2 gap-3 lg:w-60 flex-shrink-0">
                <div class="col-span-2 bg-white/5 border border-white/8 rounded-2xl p-5 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Total Participated</div>
                    <div class="text-3xl font-black text-white tabular-nums">{{ $participating->count() + $won->count() }}</div>
                </div>
                <div class="bg-white/5 border border-white/8 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Avg. Bid</div>
                    <div class="text-lg font-black text-white">${{ number_format($avgBid) }}</div>
                </div>
                <div class="bg-white/5 border border-white/8 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="text-[0.5rem] text-white/30 uppercase tracking-widest font-black mb-1">Spent</div>
                    <div class="text-lg font-black text-[#ff6900]">${{ number_format($totalSpent) }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ══ CONTENT AREA ═══════════════════════════════════════════════ --}}
<div class="bg-[#f0f2f7] min-h-screen py-12 px-6" x-data="{ tab: 'participating' }">
    <div class="max-w-6xl mx-auto">

        {{-- Tab Switcher --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <button @click="tab = 'participating'"
                :class="{ 'active': tab === 'participating' }"
                class="tab-btn px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5">
                <i data-lucide="activity" class="w-4 h-4"></i>
                Active Auctions
                <span class="ml-1 bg-[#ff6900]/20 text-[#ff6900] rounded-full px-2 py-0.5 text-[0.55rem]">{{ $participating->count() }}</span>
            </button>
            <button @click="tab = 'won'"
                :class="{ 'active': tab === 'won' }"
                class="tab-btn px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5">
                <i data-lucide="trophy" class="w-4 h-4"></i>
                Won Auctions
                <span class="ml-1 bg-emerald-100 text-emerald-700 rounded-full px-2 py-0.5 text-[0.55rem]">{{ $won->count() }}</span>
            </button>
        </div>

        {{-- ── TAB 1: ACTIVE PARTICIPATING ──────────────────────── --}}
        <div x-show="tab === 'participating'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @forelse($participating as $auction)
                @php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg","images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake,'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if (file_exists(public_path($p))) { $finalLogo = $p; break; } }
                    $carImg = $car?->image_url ?? asset('images/cars/car-silver.png');
                @endphp
                <div class="deal-card fade-up {{ $auction->is_leading ? '' : '' }}">

                    {{-- Car Panel --}}
                    <div class="car-panel">
                        <img src="{{ $carImg }}" alt="{{ $car?->make }}" class="car-bg absolute inset-0">
                        <div class="brand-logo-wrap">
                            <div class="brand-logo-inner">
                                @if($finalLogo)
                                    <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="car-front" class="w-10 h-10 text-[#ff6900] opacity-80"></i>
                                @endif
                            </div>
                        </div>
                        {{-- Badge --}}
                        @if($auction->is_leading)
                        <span class="status-badge-abs bg-emerald-500 text-white shadow-lg shadow-emerald-500/40">⚡ Leading</span>
                        @else
                        <span class="status-badge-abs bg-amber-400 text-white">⚠ Outbid</span>
                        @endif
                        {{-- Ref --}}
                        <div class="car-ref">
                            <div class="text-[0.45rem] text-white/40 font-black uppercase tracking-widest mb-0.5">Auction Ref</div>
                            <div class="text-xs font-black text-white font-mono">{{ $auction->reference_code }}</div>
                        </div>
                    </div>

                    {{-- Info Panel --}}
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        {{ $car?->make }} <span class="text-[#ff6900]">{{ $car?->model }}</span>
                                    </h3>
                                    <p class="text-[0.62rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide">{{ $car?->year }} · {{ ucfirst($auction->status) }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.45rem] text-slate-400 font-black uppercase tracking-widest">My Bid</div>
                                    <div class="text-xl font-black text-[#031629] tabular-nums">${{ number_format($auction->user_bid) }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Top Bid</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="trending-up" class="w-3 h-3 {{ $auction->is_leading ? 'text-emerald-500' : 'text-red-500' }}"></i>
                                        <span class="text-[0.8rem] font-black {{ $auction->is_leading ? 'text-emerald-600' : 'text-red-500' }}">${{ number_format($auction->top_bid) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Ends</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3 h-3 text-[#ff6900]"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629] uppercase italic tracking-tighter">
                                            {{ $auction->end_time ? \Carbon\Carbon::parse($auction->end_time)->format('d M @ g:ia') : 'TBD' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('auctions.show', $auction) }}"
                               class="flex-1 h-12 bg-[#1d293d] hover:bg-[#ff6900] rounded-xl flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest transition-all hover:scale-[1.02] shadow-lg shadow-slate-900/10">
                                <i data-lucide="zap" class="w-4 h-4 text-orange-400"></i>
                                {{ $auction->status === 'active' ? 'Bid Now' : 'View Auction' }}
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="activity" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Active Bids</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">No ongoing auction activity</p>
                    </div>
                    <a href="{{ route('auctions.index') }}" class="px-8 py-3 bg-[#1d293d] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-full hover:bg-[#ff6900] transition-all">
                        Browse Auctions
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── TAB 2: WON AUCTIONS ──────────────────────────────── --}}
        <div x-show="tab === 'won'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @forelse($won as $auction)
                @php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg","images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake,'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if (file_exists(public_path($p))) { $finalLogo = $p; break; } }
                    $carImg = $car?->image_url ?? asset('images/cars/car-silver.png');
                    $finalPrice = $auction->negotiation?->highest_bid ?? 0;
                    $inv = $auction->invoices?->first();
                @endphp
                <div class="deal-card won-card fade-up">

                    {{-- Car Panel (green tint) --}}
                    <div class="car-panel" style="background:#031629;">
                        <img src="{{ $carImg }}" alt="{{ $car?->make }}" class="car-bg absolute inset-0" style="opacity:0.55;">
                        <div class="brand-logo-wrap">
                            <div class="brand-logo-inner" style="background:rgba(255,255,255,0.88);">
                                @if($finalLogo)
                                    <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="car-front" class="w-10 h-10 text-emerald-500 opacity-80"></i>
                                @endif
                            </div>
                        </div>
                        {{-- Trophy Badge --}}
                        <span class="status-badge-abs bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 flex items-center gap-1">
                            🏆 Won
                        </span>
                        <div class="car-ref">
                            <div class="text-[0.45rem] text-white/40 font-black uppercase tracking-widest mb-0.5">Ref</div>
                            <div class="text-xs font-black text-white font-mono">{{ $auction->reference_code }}</div>
                        </div>
                    </div>

                    {{-- Info Panel --}}
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        {{ $car?->make }} <span class="text-emerald-600">{{ $car?->model }}</span>
                                    </h3>
                                    <p class="text-[0.62rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide">{{ $car?->year }} Production</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.45rem] text-slate-400 font-black uppercase tracking-widest">Final Price</div>
                                    <div class="text-xl font-black text-emerald-600 tabular-nums">${{ number_format($finalPrice) }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Won On</div>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar-check" class="w-3 h-3 text-emerald-500"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629]">{{ $auction->negotiation?->updated_at?->format('d M Y') ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-[0.45rem] font-black text-slate-400 uppercase tracking-widest mb-1">Invoice</div>
                                    @php
                                        $sc = ['paid'=>'bg-emerald-100 text-emerald-700','partial'=>'bg-blue-100 text-blue-700','pending'=>'bg-amber-100 text-amber-700'];
                                        $ic = $sc[$inv?->status] ?? 'bg-slate-100 text-slate-500';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[0.5rem] font-black uppercase tracking-widest {{ $ic }}">{{ ucfirst($inv?->status ?? 'No Invoice') }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('auctions.show', $auction) }}"
                           class="h-12 bg-[#031629] hover:bg-emerald-700 rounded-xl flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest transition-all hover:scale-[1.02] shadow-lg shadow-slate-900/10">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-400"></i> View Details
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="trophy" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Wins Yet</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">Start bidding to win your first auction</p>
                    </div>
                    <a href="{{ route('auctions.index') }}" class="px-8 py-3 bg-[#031629] text-white text-[0.65rem] font-black uppercase tracking-widest rounded-full hover:bg-[#ff6900] transition-all">
                        Browse Auctions
                    </a>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>
@endsection
