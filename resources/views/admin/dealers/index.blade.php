@extends('admin.layout')
@section('title', 'Dealers')
@section('page_title', 'Dealers')

@section('content')
<div class="px-1 space-y-5">

    <x-admin-header icon="users" title="Dealer" highlight="Network" dot="cyan"
        subtitle="Registered bidders & buyers on the platform">
    </x-admin-header>

    {{-- Toolbar --}}
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <form action="{{ route('admin.dealers.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Name or email..."
                        class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md pl-11 pr-4 text-[0.9rem] text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all shadow-sm">
                </div>
            </div>
            <button type="submit" class="h-[44px] px-6 bg-[#1d293d] text-white rounded-md text-[0.65rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="search" class="w-4 h-4"></i> Filter
            </button>
            <a href="{{ route('admin.dealers.index') }}" class="h-[44px] px-5 bg-slate-100 text-slate-600 border border-slate-300 rounded-md text-[0.65rem] font-black uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-400"></i> Reset
            </a>
            <div class="flex items-center justify-center bg-slate-800 h-[44px] border border-slate-700 rounded-md px-4 min-w-[6rem]">
                <span class="text-[0.55rem] font-bold uppercase tracking-[0.2em] text-slate-400">{{ $dealers->total() }} Dealers</span>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] w-16 text-center">#</th>
                    <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em]">Dealer Identity</th>
                    <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-center">Total Bids</th>
                    <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-center">Auctions Won</th>
                    <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-center">Highest Bid</th>
                    <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-center">Joined</th>
                    <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($dealers as $dealer)
                @php
                    $wonCount = \App\Models\Auction::whereHas('negotiation', fn($q) =>
                        $q->where('status','closed')->where('winning_bidder_id', $dealer->id)
                    )->count();
                    $highestBid = $dealer->bids->max('amount') ?? 0;
                @endphp
                <tr class="group hover:bg-slate-50/50 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-[#ff6900]">

                    {{-- # --}}
                    <td class="py-5 px-8 text-center text-slate-300 text-[0.65rem] font-mono group-hover:text-slate-900 transition-colors">
                        {{ $dealer->id }}
                    </td>

                    {{-- Identity --}}
                    <td class="py-5 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md bg-[#1d293d] group-hover:bg-[#ff6900] flex items-center justify-center text-white shadow-lg transition-colors flex-shrink-0">
                                <span class="text-[0.7rem] font-black uppercase italic">{{ strtoupper(substr($dealer->name,0,2)) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[0.85rem] font-black text-slate-900 tracking-tight">{{ $dealer->name }}</span>
                                <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-wider">{{ $dealer->email }}</span>
                                @if($dealer->phone)
                                <span class="text-[0.6rem] text-[#ff6900] font-bold tracking-wider">{{ $dealer->phone }}</span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Total Bids --}}
                    <td class="py-5 px-6 text-center">
                        <div class="inline-flex flex-col items-center">
                            <span class="text-xl font-black text-[#1d293d] tabular-nums">{{ number_format($dealer->bids_count) }}</span>
                            <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">bids</span>
                        </div>
                    </td>

                    {{-- Won --}}
                    <td class="py-5 px-6 text-center">
                        @if($wonCount > 0)
                        <div class="inline-flex flex-col items-center">
                            <span class="text-xl font-black text-emerald-600 tabular-nums">{{ $wonCount }}</span>
                            <span class="text-[0.5rem] font-black text-emerald-400 uppercase tracking-widest">won</span>
                        </div>
                        @else
                        <span class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">—</span>
                        @endif
                    </td>

                    {{-- Highest Bid --}}
                    <td class="py-5 px-6 text-center">
                        @if($highestBid > 0)
                        <span class="text-[0.85rem] font-black text-[#1d293d] tabular-nums">${{ number_format($highestBid) }}</span>
                        @else
                        <span class="text-[0.65rem] font-black text-slate-300">—</span>
                        @endif
                    </td>

                    {{-- Joined --}}
                    <td class="py-5 px-6 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-[0.75rem] font-bold text-slate-700">{{ $dealer->created_at->format('d M Y') }}</span>
                            <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">{{ $dealer->created_at->diffForHumans(null, true) }} ago</span>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td class="py-5 px-8 text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- View front-end profile --}}
                            <a href="{{ route('dealer.profile', $dealer) }}"
                               target="_blank"
                               title="View Public Profile"
                               class="w-10 h-10 rounded-md bg-orange-50 border border-orange-100 text-[#ff6900] flex items-center justify-center hover:bg-[#ff6900] hover:text-white transition-all shadow-sm active:scale-95 group/btn">
                                <i data-lucide="user-circle" class="w-4 h-4 transition-transform group-hover/btn:scale-110"></i>
                            </a>
                            {{-- Admin profile --}}
                            <a href="{{ route('admin.dealers.show', $dealer) }}"
                               title="Admin Profile"
                               class="w-10 h-10 rounded-md bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white transition-all shadow-sm active:scale-95 group/btn">
                                <i data-lucide="eye" class="w-4 h-4 transition-transform group-hover/btn:scale-110"></i>
                            </a>
                            {{-- WhatsApp --}}
                            @if($dealer->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $dealer->phone) }}"
                               target="_blank"
                               title="WhatsApp"
                               class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95 group/btn">
                                <i data-lucide="message-circle" class="w-4 h-4 transition-transform group-hover/btn:scale-110"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-32 text-center bg-slate-50">
                        <div class="flex flex-col items-center gap-6">
                            <div class="relative">
                                <div class="w-20 h-20 bg-white rounded-lg flex items-center justify-center border border-slate-200 shadow-xl">
                                    <i data-lucide="users" class="w-10 text-slate-200"></i>
                                </div>
                                <div class="absolute -right-2 -bottom-2 w-8 h-8 bg-[#ff6900] rounded-full flex items-center justify-center text-white text-[0.6rem] font-black border-4 border-slate-50">0</div>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-[0.7rem] font-black text-slate-400 uppercase tracking-[0.4em]">No Dealers Yet</h3>
                                <p class="text-[0.6rem] text-slate-300 font-bold">Dealers appear here once they place their first bid</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($dealers->hasPages())
        <div class="bg-slate-50 px-10 py-8 border-t border-slate-200 flex items-center justify-center">
            {{ $dealers->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
