<div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest w-28 text-center">Photo</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Vehicle</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Reference</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Status</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Price</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Schedule</th>
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-slate-50">
            @forelse($auctions as $auction)
            <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                <td class="py-5 px-8 text-center">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shadow-sm transition-transform duration-500 group-hover:scale-110">
                        @if(optional($auction->car)->image_url)
                            <img src="{{ $auction->car->image_url }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="image" class="w-6 h-6 text-slate-300 mx-auto mt-5"></i>
                        @endif
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col">
                        <span class="text-base font-black text-[#031629] tracking-tight truncate max-w-[200px]">{{ optional($auction->car)->year }} {{ optional($auction->car)->make }} {{ optional($auction->car)->model }}</span>
                        <span class="text-[0.6rem] text-slate-400 mt-1 font-bold uppercase tracking-widest">#{{ $auction->id }}</span>
                    </div>
                </td>
                {{-- Reference Code --}}
                <td class="py-5 px-6 text-center">
                    @if($auction->reference_code)
                        <span class="inline-flex items-center gap-1.5 font-mono text-[0.7rem] font-black text-[#ff6900] bg-orange-50 border border-orange-200 px-3 py-1.5 rounded-lg">
                            <i data-lucide="hash" class="w-3 h-3"></i>
                            {{ $auction->reference_code }}
                        </span>
                    @else
                        <span class="text-[0.6rem] text-slate-300 font-medium">—</span>
                    @endif
                </td>
                <td class="py-5 px-6 text-center">
                    @php
                        // Smart status: if DB says active but end_at passed → treat as finished
                        $isExpired = $auction->status === 'active' && $auction->end_at && $auction->end_at->isPast();
                        $displayStatus = $isExpired ? 'finished' : $auction->status;
                    @endphp

                    @if($displayStatus === 'active')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border bg-emerald-50 text-emerald-600 border-emerald-200 shadow-sm shadow-emerald-500/10">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Live
                        </span>
                    @elseif($displayStatus === 'coming_soon')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border bg-amber-50 text-amber-600 border-amber-200 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-amber-400 inline-block"></span>
                            Coming Soon
                        </span>
                    @elseif($displayStatus === 'finished')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border bg-red-50 text-red-500 border-red-200 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-red-400 inline-block"></span>
                            Finished
                        </span>
                    @elseif($displayStatus === 'paused')
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border bg-yellow-50 text-yellow-600 border-yellow-200 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-yellow-400 inline-block"></span>
                            Paused
                        </span>
                    @else
                        <span class="inline-flex px-4 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border bg-slate-100 text-slate-400 border-slate-200">
                            {{ str_replace('_', ' ', $auction->status) }}
                        </span>
                    @endif
                </td>
                <td class="py-5 px-6 text-center">
                    <div class="flex flex-col">
                        <span class="text-lg font-black text-[#031629] tracking-tighter tabular-nums">${{ number_format($auction->current_price ?? $auction->initial_price) }}</span>
                        <span class="text-[0.55rem] text-slate-400 uppercase font-black tracking-widest mt-0.5">Start: ${{ number_format($auction->initial_price) }}</span>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-[0.65rem] text-slate-500 font-bold uppercase italic">
                            <i data-lucide="play" class="w-3 h-3 text-emerald-500"></i> {{ $auction->start_at->format('M d, H:i') }}
                        </div>
                        <div class="flex items-center gap-2 text-[0.65rem] {{ $auction->end_at->isPast() ? 'text-red-400' : 'text-slate-400' }} font-bold uppercase italic">
                            <i data-lucide="clock" class="w-3 h-3 {{ $auction->end_at->isPast() ? 'text-red-500' : 'text-red-400' }}"></i> {{ $auction->end_at->format('M d, H:i') }}
                            @if($auction->end_at->isPast()) <span class="text-red-400 font-black">• Expired</span> @endif
                        </div>
                    </div>
                </td>
                <td class="py-5 px-8 text-right">
                    <div class="flex items-center justify-end gap-3">

                        {{-- Negotiate: closed auctions --}}
                        @if($auction->status === 'closed')
                        <button onclick="startNegotiation({{ $auction->id }})" title="Start Negotiation"
                            class="h-10 px-4 rounded-md bg-purple-600 text-white flex items-center gap-2 hover:bg-purple-700 transition-all shadow-lg text-[0.6rem] font-black uppercase tracking-widest">
                            <i data-lucide="handshake" class="w-3.5 h-3.5"></i> Negotiate
                        </button>
                        @endif

                        @if($auction->status === 'deal_approved')
                        <span class="inline-flex items-center gap-1.5 text-[0.6rem] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-md">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Deal Approved
                        </span>
                        @endif

                        {{-- Go Live: coming_soon + paused --}}
                        @if(in_array($auction->status, ['coming_soon', 'paused']))
                        <button onclick="approveAuction({{ $auction->id }})" title="Go Live"
                            class="h-10 px-4 rounded-md bg-emerald-500 text-white flex items-center gap-2 hover:bg-emerald-600 transition-all shadow-lg text-[0.6rem] font-black uppercase tracking-widest">
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i> Go Live
                        </button>
                        @endif

                        {{-- Relaunch: expired active auctions --}}
                        @if($isExpired)
                        <button onclick="approveAuction({{ $auction->id }})" title="Relaunch Auction"
                            class="h-10 px-4 rounded-md bg-[#ff6900] text-white flex items-center gap-2 hover:bg-orange-600 transition-all shadow-lg text-[0.6rem] font-black uppercase tracking-widest">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Relaunch
                        </button>
                        @endif

                        <a href="{{ route('admin.auctions.edit', $auction) }}" title="Edit" class="w-10 h-10 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white transition-all shadow-sm border border-slate-100">
                            <i data-lucide="settings-2" class="w-4 h-4"></i>
                        </a>
                        <button onclick="purgeAuction({{ $auction->id }})" title="Delete" class="w-10 h-10 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm border border-slate-100">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-32 text-center bg-slate-50/30">
                    <div class="flex flex-col items-center gap-6 opacity-30">
                        <div class="w-20 h-20 bg-white rounded-[2rem] flex items-center justify-center border border-slate-200 shadow-xl">
                            <i data-lucide="gavel" class="w-10 h-10 text-slate-300"></i>
                        </div>
                        <h3 class="text-[0.7rem] font-black text-slate-500 uppercase tracking-[0.4em]">No auctions found</h3>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div id="paginationContainer">
        @if($auctions->hasPages())
        <div class="bg-slate-50/50 px-10 py-10 border-t border-slate-100 flex items-center justify-center">
            {{ $auctions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

