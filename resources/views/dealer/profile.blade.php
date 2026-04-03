@extends('layouts.app')

@section('title', $dealer->name . ' — Dealer Profile · Motor Bazar')

@section('head')
<style>
    /* ── HERO HEADER ─────────────────────────── */
    .profile-hero {
        background: linear-gradient(135deg, #0f1117 0%, #1a1d26 50%, #0f1117 100%);
        position: relative;
        overflow: hidden;
    }
    .profile-hero::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 70% 60% at 60% 50%, rgba(255,70,5,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .profile-hero .grid-lines {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    /* ── AVATAR ───────────────────────────────── */
    .dealer-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(255,70,5,0.5);
        box-shadow: 0 0 0 6px rgba(255,70,5,0.1), 0 20px 40px rgba(0,0,0,0.4);
        object-fit: cover;
        background: #1e2330;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 900; color: #ff4605;
        flex-shrink: 0;
    }

    /* ── STAT CARD ────────────────────────────── */
    .stat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 20px 24px;
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
    }
    .stat-card:hover {
        background: rgba(255,70,5,0.07);
        border-color: rgba(255,70,5,0.25);
        transform: translateY(-2px);
    }

    /* ── AUCTION CARDS ───────────────────────── */
    .auction-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .auction-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 48px rgba(0,0,0,0.12);
        border-color: #ff4605;
    }
    .auction-card .car-img {
        width: 100%; height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #f8fafc, #e8edf5);
        transition: transform 0.5s ease;
    }
    .auction-card:hover .car-img { transform: scale(1.04); }
    .car-img-wrap { overflow: hidden; position: relative; height: 200px; }

    .status-badge {
        position: absolute; top: 14px; left: 14px;
        padding: 5px 12px; border-radius: 999px;
        font-size: 0.6rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: 0.12em;
    }
    .badge-active   { background: rgba(16,185,129,0.1); color: #059669; border: 1px solid rgba(16,185,129,0.25); }
    .badge-closed   { background: rgba(100,116,139,0.08); color: #64748b; border: 1px solid rgba(100,116,139,0.15); }
    .badge-won      { background: rgba(255,70,5,0.1); color: #ff4605; border: 1px solid rgba(255,70,5,0.25); }
    .badge-coming   { background: rgba(251,191,36,0.1); color: #d97706; border: 1px solid rgba(251,191,36,0.25); }

    /* ── MY BID RIBBON ───────────────────────── */
    .my-bid-ribbon {
        background: linear-gradient(90deg, #ff4605, #ff6900);
        color: white;
        font-size: 0.6rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: 0.1em;
        padding: 8px 16px;
        display: flex; justify-content: space-between; align-items: center;
    }

    /* ── FILTER PILLS ────────────────────────── */
    .filter-pill {
        padding: 8px 20px; border-radius: 999px;
        font-weight: 700; font-size: 0.78rem;
        cursor: pointer; transition: all 0.2s ease;
        border: 1.5px solid #e2e8f0;
        color: #64748b; background: white;
    }
    .filter-pill.active, .filter-pill:hover {
        background: #ff4605; color: white; border-color: #ff4605;
    }

    /* ── EMPTY STATE ─────────────────────────── */
    .empty-state {
        padding: 80px 20px; text-align: center;
    }

    /* ── SCROLL FADE-IN ──────────────────────── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.6s ease both; }
    .fade-up:nth-child(1) { animation-delay: 0.05s; }
    .fade-up:nth-child(2) { animation-delay: 0.12s; }
    .fade-up:nth-child(3) { animation-delay: 0.19s; }
    .fade-up:nth-child(4) { animation-delay: 0.26s; }
    .fade-up:nth-child(5) { animation-delay: 0.33s; }
    .fade-up:nth-child(6) { animation-delay: 0.40s; }
</style>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HERO: Dealer Header
═══════════════════════════════════════════════════════════ --}}
<div class="profile-hero pt-28 pb-16 px-6">
    <div class="grid-lines"></div>
    <div class="max-w-6xl mx-auto relative z-10">

        {{-- Back Link --}}
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs font-bold uppercase tracking-widest mb-8 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5"></i> Back
        </a>

        <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
            {{-- Avatar --}}
            <div class="dealer-avatar flex-shrink-0">
                {{ strtoupper(substr($dealer->name, 0, 2)) }}
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $dealer->name }}</h1>
                    @if($auctionsWon > 0)
                    <span class="px-3 py-1 bg-[#ff4605]/15 text-[#ff6900] border border-[#ff4605]/30 rounded-full text-[0.6rem] font-black uppercase tracking-widest">
                        Verified Buyer
                    </span>
                    @endif
                </div>
                <p class="text-white/40 text-sm font-semibold">Member since {{ $dealer->created_at->format('M Y') }}</p>
                <p class="text-white/25 text-xs mt-1 font-medium">{{ $dealer->email }}</p>

                {{-- Quick Stats Row --}}
                <div class="flex flex-wrap gap-6 mt-5">
                    <div class="text-center">
                        <div class="text-2xl font-black text-white">{{ number_format($totalBids) }}</div>
                        <div class="text-[0.6rem] text-white/40 uppercase tracking-widest font-bold">Total Bids</div>
                    </div>
                    <div class="w-px bg-white/10 self-stretch"></div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-[#ff4605]">{{ number_format($auctionsWon) }}</div>
                        <div class="text-[0.6rem] text-white/40 uppercase tracking-widest font-bold">Won</div>
                    </div>
                    <div class="w-px bg-white/10 self-stretch"></div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-emerald-400">{{ $activeBids }}</div>
                        <div class="text-[0.6rem] text-white/40 uppercase tracking-widest font-bold">Active Bids</div>
                    </div>
                    @if($totalSpent > 0)
                    <div class="w-px bg-white/10 self-stretch"></div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-white">${{ number_format($totalSpent) }}</div>
                        <div class="text-[0.6rem] text-white/40 uppercase tracking-widest font-bold">Total Spent</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 gap-3 md:w-64 flex-shrink-0">
                <div class="stat-card col-span-2">
                    <div class="text-[0.55rem] text-white/30 uppercase tracking-widest font-bold mb-1">Auctions Participated</div>
                    <div class="text-3xl font-black text-white">{{ $auctions->total() }}</div>
                </div>
                <div class="stat-card">
                    <div class="text-[0.55rem] text-white/30 uppercase tracking-widest font-bold mb-1">Win Rate</div>
                    <div class="text-xl font-black text-[#ff4605]">
                        {{ $auctions->total() > 0 ? round($auctionsWon / $auctions->total() * 100) : 0 }}%
                    </div>
                </div>
                <div class="stat-card">
                    <div class="text-[0.55rem] text-white/30 uppercase tracking-widest font-bold mb-1">Avg. Bid</div>
                    <div class="text-xl font-black text-white">
                        @if($totalBids > 0)
                            ${{ number_format($avgBid) }}
                        @else N/A @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     AUCTION HISTORY
═══════════════════════════════════════════════════════════ --}}
<div class="bg-[#f5f7fb] min-h-screen py-12 px-6">
    <div class="max-w-6xl mx-auto">

        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-[#111827]">Auction History</h2>
                <p class="text-sm text-gray-400 font-semibold mt-1">All auctions {{ $dealer->name }} has participated in</p>
            </div>

            {{-- Filter Pills --}}
            <div class="flex flex-wrap gap-2" id="filter-pills">
                <button class="filter-pill active" data-filter="all">All ({{ $auctions->total() }})</button>
                <button class="filter-pill" data-filter="active">Live</button>
                <button class="filter-pill" data-filter="closed">Closed</button>
                <button class="filter-pill" data-filter="coming_soon">Coming Soon</button>
            </div>
        </div>

        {{-- Auction Grid --}}
        @if($auctions->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="auctions-grid">
            @foreach($auctions as $auction)
            @php
                $car          = $auction->car;
                $imgUrl       = $car->image_url ?? null;
                $dealerBid    = $auction->dealer_highest_bid ?? 0;
                $isWinner     = ($auction->negotiation?->winning_bidder_id ?? null) === $dealer->id
                                && $auction->negotiation?->status === 'accepted';
                $isHighest    = (float)$auction->current_price === (float)$dealerBid && $dealerBid > 0;
                $statusLabel  = match($auction->status) {
                    'active'      => 'Live',
                    'closed','deal_approved','sold' => 'Closed',
                    'coming_soon' => 'Coming Soon',
                    default       => ucfirst($auction->status),
                };
                $badgeClass   = match($auction->status) {
                    'active'      => 'badge-active',
                    'coming_soon' => 'badge-coming',
                    default       => $isWinner ? 'badge-won' : 'badge-closed',
                };
            @endphp
            <div class="auction-card fade-up" data-status="{{ $auction->status }}">
                {{-- Image --}}
                <div class="car-img-wrap">
                    @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $car->make }} {{ $car->model }}" class="car-img">
                    @else
                        <div class="car-img flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                            <i data-lucide="car" class="w-16 h-16 text-slate-300"></i>
                        </div>
                    @endif
                    {{-- Status Badge --}}
                    <span class="status-badge {{ $badgeClass }}">
                        @if($isWinner) 🏆 Won · {{ $statusLabel }}
                        @elseif($isHighest && $auction->status === 'active') 🔥 Leading
                        @else {{ $statusLabel }} @endif
                    </span>
                    {{-- Ref Code --}}
                    @if($auction->reference_code)
                    <span class="absolute top-14 left-14 text-[0.5rem] font-black text-white/70 bg-black/30 px-2 py-0.5 rounded-full backdrop-blur-sm">
                        {{ $auction->reference_code }}
                    </span>
                    @endif
                </div>

                {{-- My Bid Ribbon --}}
                @if($dealerBid > 0)
                <div class="my-bid-ribbon">
                    <span>My Highest Bid</span>
                    <span class="text-base font-black">${{ number_format($dealerBid) }}</span>
                </div>
                @endif

                {{-- Card Body --}}
                <div class="p-5">
                    <div class="mb-3">
                        <h3 class="text-[1rem] font-black text-[#111827] leading-tight">
                            {{ $car->year }} {{ $car->make }} {{ $car->model }}
                        </h3>
                        <p class="text-[0.65rem] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                            {{ $car->trim ?? '' }} {{ $car->color ?? '' }}
                        </p>
                    </div>

                    {{-- Price Row --}}
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <div class="text-[0.55rem] text-gray-400 uppercase tracking-widest font-bold mb-0.5">Current Price</div>
                            <div class="text-xl font-black text-[#111827]">${{ number_format($auction->current_price ?? $auction->initial_price) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[0.55rem] text-gray-400 uppercase tracking-widest font-bold mb-0.5">Total Bids</div>
                            <div class="text-xl font-black text-[#111827]">{{ $auction->bids_count }}</div>
                        </div>
                    </div>

                    {{-- Timer (if active) --}}
                    @if($auction->status === 'active' && $auction->end_at)
                    <div class="flex items-center gap-2 bg-red-50 text-red-600 rounded-lg px-3 py-2 mb-4">
                        <i data-lucide="clock" class="w-3.5 flex-shrink-0"></i>
                        <span class="text-[0.65rem] font-black tabular-nums auction-countdown" data-expires="{{ $auction->end_at->toIso8601String() }}">
                            Calculating...
                        </span>
                    </div>
                    @elseif($auction->status === 'coming_soon' && $auction->start_at)
                    <div class="flex items-center gap-2 bg-amber-50 text-amber-600 rounded-lg px-3 py-2 mb-4">
                        <i data-lucide="calendar" class="w-3.5 flex-shrink-0"></i>
                        <span class="text-[0.65rem] font-black">Opens {{ $auction->start_at->format('M d, H:i') }}</span>
                    </div>
                    @else
                    <div class="h-2 mb-4"></div>
                    @endif

                    {{-- CTA --}}
                    <a href="{{ route('auctions.show', $auction) }}"
                       class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-black text-sm transition-all
                              {{ $auction->status === 'active'
                                  ? 'bg-[#ff4605] text-white hover:bg-[#e03d04] shadow-lg shadow-orange-500/25 hover:shadow-orange-500/40'
                                  : 'bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-100' }}">
                        @if($auction->status === 'active')
                            <i data-lucide="zap" class="w-4"></i> Bid Now
                        @else
                            <i data-lucide="eye" class="w-4"></i> View Auction
                        @endif
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($auctions->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $auctions->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="empty-state">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="gavel" class="w-9 h-9 text-slate-300"></i>
            </div>
            <h3 class="text-xl font-black text-[#111827] mb-2">No Auctions Yet</h3>
            <p class="text-sm text-gray-400 font-semibold max-w-sm mx-auto">
                {{ $dealer->name }} hasn't participated in any auctions yet.
            </p>
            <a href="{{ route('auctions.index') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-[#ff4605] text-white rounded-xl font-black text-sm hover:bg-[#e03d04] transition-all shadow-lg shadow-orange-500/25">
                <i data-lucide="search" class="w-4"></i> Browse Auctions
            </a>
        </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();

    // ── Countdown Timers ──────────────────────────────
    function runCountdowns() {
        document.querySelectorAll('.auction-countdown[data-expires]').forEach(el => {
            const expires = new Date(el.dataset.expires).getTime();
            function tick() {
                const diff = expires - Date.now();
                if (diff <= 0) { el.textContent = 'Ended'; return; }
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                el.textContent = (h > 0 ? h + 'h ' : '') + String(m).padStart(2,'0') + 'm ' + String(s).padStart(2,'0') + 's left';
                setTimeout(tick, 1000);
            }
            tick();
        });
    }
    runCountdowns();

    // ── Filter Pills ──────────────────────────────────
    const pills = document.querySelectorAll('.filter-pill');
    const cards = document.querySelectorAll('.auction-card[data-status]');

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const filter = pill.dataset.filter;
            cards.forEach(card => {
                const st = card.dataset.status;
                const show = filter === 'all'
                    || (filter === 'closed' && ['closed','deal_approved','sold'].includes(st))
                    || (filter === st);
                card.style.display = show ? '' : 'none';
            });
        });
    });
});
</script>
@endsection
