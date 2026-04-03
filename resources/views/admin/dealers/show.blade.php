@extends('admin.layout')
@section('title', $user->name . ' — Dealer Profile')
@section('page_title', 'Dealer Profile')

@section('content')
<div class="px-2 space-y-8 pb-20">

    {{-- ── HERO PROFILE HEADER ──────────────────────────────────────────── --}}
    <div class="bg-white border border-slate-100 rounded-[2rem] overflow-hidden">

        {{-- Cover strip --}}
        <div class="h-24 bg-gradient-to-r from-[#031629] via-[#1d293d] to-[#031629] relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: repeating-linear-gradient(45deg, #ff6900 0, #ff6900 1px, transparent 0, transparent 50%); background-size: 12px 12px;">
            </div>
        </div>

        <div class="px-8 pb-8">
            <div class="flex flex-col md:flex-row md:items-end gap-6 -mt-10">

                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-2xl bg-[#1d293d] border-4 border-white shadow-2xl flex items-center justify-center flex-shrink-0 relative z-10">
                    <span class="text-2xl font-black text-[#ff6900] uppercase italic">{{ strtoupper(substr($user->name,0,2)) }}</span>
                </div>

                {{-- Name & meta --}}
                <div class="flex-1 pt-2">
                    <h1 class="text-2xl font-black text-[#031629] uppercase italic tracking-tight leading-none">
                        {{ $user->name }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 mt-2">
                        <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-3 h-3"></i> {{ $user->email }}
                        </span>
                        <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3 h-3"></i> Member since {{ $user->created_at->format('M Y') }}
                        </span>
                        @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="text-[0.6rem] font-black text-[#ff6900] uppercase tracking-widest flex items-center gap-1.5 hover:underline">
                            <i data-lucide="phone" class="w-3 h-3"></i> {{ $user->phone }}
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Quick Stats Row --}}
                <div class="flex items-center gap-px bg-slate-50 rounded-xl overflow-hidden border border-slate-100">
                    @foreach([
                        ['label' => 'Total Bids',     'value' => number_format($stats['total_bids']),    'color' => 'text-slate-700'],
                        ['label' => 'Auctions Won',   'value' => $stats['auctions_won'],                 'color' => 'text-emerald-600'],
                        ['label' => 'Active Now',     'value' => $stats['active_auctions'],              'color' => 'text-[#ff6900]'],
                        ['label' => 'Highest Bid',    'value' => '$'.number_format($stats['highest_bid']),'color' => 'text-purple-600'],
                        ['label' => 'Total Spent',    'value' => '$'.number_format($stats['total_spent']),'color' => 'text-[#031629]'],
                    ] as $stat)
                    <div class="px-5 py-4 text-center border-r border-slate-100 last:border-0">
                        <div class="text-lg font-black tabular-nums {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                        <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest mt-0.5 whitespace-nowrap">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    {{-- ── TABS ─────────────────────────────────────────────────────────── --}}
    <div x-data="{ tab: 'participating' }">

        {{-- Tab Nav --}}
        <div class="flex items-center gap-2 mb-6">
            <button @click="tab = 'participating'"
                :class="tab === 'participating' ? 'bg-[#1d293d] text-white shadow-lg shadow-slate-900/10' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-300'"
                class="px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5 transition-all">
                <i data-lucide="activity" class="w-4 h-4"></i>
                Active Auctions
                <span class="ml-1 bg-[#ff6900]/20 text-[#ff6900] rounded-full px-2 py-0.5 text-[0.55rem]">{{ $participating->count() }}</span>
            </button>
            <button @click="tab = 'won'"
                :class="tab === 'won' ? 'bg-[#1d293d] text-white shadow-lg shadow-slate-900/10' : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-300'"
                class="px-6 py-3 rounded-xl font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2.5 transition-all">
                <i data-lucide="trophy" class="w-4 h-4"></i>
                Won Auctions
                <span class="ml-1 bg-emerald-100 text-emerald-600 rounded-full px-2 py-0.5 text-[0.55rem]">{{ $won->count() }}</span>
            </button>
        </div>

        {{-- ── TAB 1: PARTICIPATING (ACTIVE) ──────────────────────────────── --}}
        <div x-show="tab === 'participating'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                @forelse($participating as $auction)
                @php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $path) {
                        if (file_exists(public_path($path))) { $finalLogo = $path; break; }
                    }
                    $carImage = $car?->images?->first()?->image_path
                                ?? $car?->image_url
                                ?? asset('images/cars/car-silver.png');
                @endphp

                <div class="group bg-white border border-slate-100 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row">

                    {{-- Left: Car Visual --}}
                    <div class="w-full md:w-[220px] relative overflow-hidden shrink-0 bg-[#1d293d]">
                        <img src="{{ is_string($carImage) ? (str_starts_with($carImage, 'http') ? $carImage : asset('storage/'.$carImage)) : asset('images/cars/car-silver.png') }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">

                        {{-- Brand Logo --}}
                        <div class="absolute inset-0 flex items-center justify-center z-20 transition-transform duration-500 group-hover:scale-125">
                            <div class="w-20 h-20 rounded-full bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl flex items-center justify-center p-4">
                                @if($finalLogo)
                                    <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain filter drop-shadow-md">
                                @else
                                    <i data-lucide="car-front" class="w-10 h-10 text-[#ff6900] opacity-80"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Leading badge --}}
                        @if($auction->is_leading)
                        <div class="absolute top-4 left-4 z-30">
                            <span class="bg-emerald-500 text-white text-[0.55rem] font-black px-2.5 py-1.5 rounded-full uppercase tracking-widest shadow-lg animate-pulse">Leading</span>
                        </div>
                        @else
                        <div class="absolute top-4 left-4 z-30">
                            <span class="bg-amber-500/90 text-white text-[0.55rem] font-black px-2.5 py-1.5 rounded-full uppercase tracking-widest shadow-lg">Outbid</span>
                        </div>
                        @endif

                        {{-- Ref --}}
                        <div class="absolute bottom-4 left-4 right-4 z-30">
                            <div class="bg-[#1d293d]/60 backdrop-blur-md p-2.5 rounded-md border border-white/10">
                                <div class="text-[0.5rem] text-white/50 font-bold uppercase tracking-widest mb-0.5">Auction Ref</div>
                                <div class="text-xs font-black text-white font-mono">{{ $auction->reference_code }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Info --}}
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        {{ $car?->make }} <span class="text-[#ff6900]">{{ $car?->model }}</span>
                                    </h3>
                                    <p class="text-[0.65rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide italic">
                                        {{ $car?->year }} — {{ ucfirst($auction->status) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.5rem] text-slate-400 font-black uppercase tracking-widest">My Bid</div>
                                    <div class="text-lg font-black text-[#031629] tabular-nums">${{ number_format($auction->user_bid_amount) }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-50">
                                <div class="space-y-1">
                                    <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest block">Top Bid</span>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="trending-up" class="w-3 h-3 text-[#ff6900]"></i>
                                        <span class="text-[0.75rem] font-black {{ $auction->is_leading ? 'text-emerald-600' : 'text-red-500' }}">
                                            ${{ number_format($auction->top_bid_amount) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest block">Ends</span>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="clock" class="w-3 h-3 text-[#ff6900]"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629] uppercase italic tracking-tighter">
                                            {{ $auction->end_time ? \Carbon\Carbon::parse($auction->end_time)->format('d M @ g:ia') : 'TBD' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.auctions.show', $auction) }}"
                               class="flex-1 h-12 bg-slate-50 hover:bg-white border border-slate-100 hover:border-orange-500/30 rounded-lg flex items-center justify-center gap-2 text-[0.6rem] font-black text-[#031629] uppercase tracking-widest transition-all">
                                <i data-lucide="eye" class="w-4 h-4 text-[#ff6900]"></i> View Auction
                            </a>
                            @if(!$auction->is_leading)
                            <div class="flex items-center gap-1.5 px-3 py-2 bg-red-50 border border-red-100 rounded-lg">
                                <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-red-500"></i>
                                <span class="text-[0.55rem] font-black text-red-500 uppercase tracking-widest">Outbid</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="activity" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Active Bids</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">This dealer has no ongoing auction activity</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── TAB 2: WON AUCTIONS ─────────────────────────────────────────── --}}
        <div x-show="tab === 'won'" x-cloak>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                @forelse($won as $auction)
                @php
                    $car = $auction->car;
                    $rawMake = strtolower($car?->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $path) {
                        if (file_exists(public_path($path))) { $finalLogo = $path; break; }
                    }
                    $carImage = $car?->images?->first()?->image_path ?? $car?->image_url ?? null;
                    $finalPrice = $auction->negotiation?->final_price ?? 0;
                @endphp

                <div class="group bg-white border border-emerald-100 rounded-[2rem] overflow-hidden hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 flex flex-col md:flex-row">

                    {{-- Left: Car Visual --}}
                    <div class="w-full md:w-[220px] relative overflow-hidden shrink-0 bg-[#031629]">
                        <img src="{{ $carImage ? (str_starts_with($carImage, 'http') ? $carImage : asset('storage/'.$carImage)) : asset('images/cars/car-silver.png') }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-60">

                        {{-- Brand Logo --}}
                        <div class="absolute inset-0 flex items-center justify-center z-20 transition-transform duration-500 group-hover:scale-125">
                            <div class="w-20 h-20 rounded-full bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl flex items-center justify-center p-4">
                                @if($finalLogo)
                                    <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain">
                                @else
                                    <i data-lucide="car-front" class="w-10 h-10 text-emerald-500 opacity-80"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Trophy badge --}}
                        <div class="absolute top-4 left-4 z-30">
                            <span class="bg-emerald-500 text-white text-[0.55rem] font-black px-2.5 py-1.5 rounded-full uppercase tracking-widest shadow-lg flex items-center gap-1">
                                <i data-lucide="trophy" class="w-3 h-3"></i> Won
                            </span>
                        </div>

                        {{-- Invoice ref --}}
                        <div class="absolute bottom-4 left-4 right-4 z-30">
                            <div class="bg-[#031629]/70 backdrop-blur-md p-2.5 rounded-md border border-white/10">
                                <div class="text-[0.5rem] text-white/50 font-bold uppercase tracking-widest mb-0.5">Ref</div>
                                <div class="text-xs font-black text-white font-mono">{{ $auction->reference_code }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Info --}}
                    <div class="flex-1 p-6 flex flex-col justify-between gap-5">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-black text-[#031629] leading-none uppercase italic">
                                        {{ $car?->make }} <span class="text-emerald-600">{{ $car?->model }}</span>
                                    </h3>
                                    <p class="text-[0.65rem] font-bold text-slate-400 mt-1.5 uppercase tracking-wide italic">{{ $car?->year }} Production</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-[0.5rem] text-slate-400 font-black uppercase tracking-widest">Final Price</div>
                                    <div class="text-xl font-black text-emerald-600 tabular-nums">${{ number_format($finalPrice) }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-50">
                                <div class="space-y-1">
                                    <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest block">Won On</span>
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="calendar-check" class="w-3 h-3 text-emerald-500"></i>
                                        <span class="text-[0.72rem] font-bold text-[#031629]">
                                            {{ $auction->negotiation?->updated_at?->format('d M Y') ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest block">Invoice Status</span>
                                    @php
                                        $inv = $auction->invoices?->first();
                                        $isc = ['paid'=>'text-emerald-600 bg-emerald-50','partial'=>'text-blue-600 bg-blue-50','pending'=>'text-amber-600 bg-amber-50'];
                                        $ic = $isc[$inv?->status] ?? 'text-slate-500 bg-slate-100';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[0.5rem] font-black uppercase tracking-widest {{ $ic }}">
                                        {{ ucfirst($inv?->status ?? 'no invoice') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.auctions.show', $auction) }}"
                               class="flex-1 h-12 bg-slate-50 hover:bg-white border border-slate-100 hover:border-emerald-500/30 rounded-lg flex items-center justify-center gap-2 text-[0.6rem] font-black text-[#031629] uppercase tracking-widest transition-all">
                                <i data-lucide="eye" class="w-4 h-4 text-emerald-500"></i> View Auction
                            </a>
                            @if($inv)
                            <a href="{{ route('admin.finance.invoice.show', $inv) }}"
                               class="flex-1 h-12 bg-[#1d293d] hover:bg-emerald-700 rounded-lg flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest shadow-lg transition-all hover:scale-[1.02]">
                                <i data-lucide="file-text" class="w-4 h-4 text-emerald-400"></i> Invoice
                            </a>
                            @else
                            <form action="{{ route('admin.finance.invoice.from-negotiation', $auction->negotiation) }}" method="POST">
                                @csrf
                                <button type="submit" class="h-12 px-4 bg-[#1d293d] hover:bg-[#ff6900] rounded-lg flex items-center justify-center gap-2 text-white text-[0.6rem] font-black uppercase tracking-widest shadow-lg transition-all hover:scale-[1.02]">
                                    <i data-lucide="zap" class="w-4 h-4 text-orange-400"></i> Create Invoice
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-24 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center justify-center gap-6 text-center">
                    <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center">
                        <i data-lucide="trophy" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#031629] uppercase italic">No Wins Yet</h3>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mt-3">This dealer hasn't won any auctions</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>{{-- end x-data tabs --}}

</div>
@endsection
