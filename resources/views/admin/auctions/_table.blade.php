<div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest w-28 text-center">Asset preview</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Marketplace Identity</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Operational Phase</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Pricing Dynamics</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Temporal Vector</th>
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-right">Directive Control</th>
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
                        <span class="text-[0.6rem] text-slate-400 mt-1 font-bold uppercase tracking-widest italic">Node ID # {{ str_pad($auction->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </td>
                <td class="py-5 px-6 text-center">
                    @php
                        $statusStyles = [
                            'coming_soon' => 'bg-amber-50 text-amber-600 border-amber-100 shadow-amber-500/10',
                            'active' => 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-emerald-500/10 animate-pulse-subtle',
                            'closed' => 'bg-slate-100 text-slate-400 border-slate-200',
                            'paused' => 'bg-red-50 text-red-500 border-red-100',
                        ];
                        $style = $statusStyles[$auction->status] ?? 'bg-slate-50 text-slate-500 border-slate-100';
                    @endphp
                    <span class="inline-flex px-4 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-[0.2em] border shadow-sm {{ $style }}">
                        {{ str_replace('_', ' ', $auction->status) }}
                    </span>
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
                        <div class="flex items-center gap-2 text-[0.65rem] text-slate-400 font-bold uppercase italic">
                            <i data-lucide="clock" class="w-3 h-3 text-red-400"></i> {{ $auction->end_at->format('M d, H:i') }}
                        </div>
                    </div>
                </td>
                <td class="py-5 px-8 text-right">
                    <div class="flex items-center justify-end gap-3">
                        @if($auction->status === 'coming_soon')
                        <button onclick="approveAuction({{ $auction->id }})" title="Go Live" class="h-10 px-4 rounded-md bg-emerald-500 text-white flex items-center justify-center gap-2 hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20 text-[0.6rem] font-black uppercase tracking-widest scale-100 hover:scale-105">
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i> Go Live
                        </button>
                        @endif

                        <a href="{{ route('admin.auctions.edit', $auction) }}" title="Recalibrate" class="w-10 h-10 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white transition-all shadow-sm border border-slate-100">
                            <i data-lucide="settings-2" class="w-4.5 h-4.5"></i>
                        </a>
                        <button onclick="purgeAuction({{ $auction->id }})" title="Purge Node" class="w-10 h-10 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm border border-slate-100">
                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
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

    <!-- Matrix Paging -->
    <div id="paginationContainer">
        @if($auctions->hasPages())
        <div class="bg-slate-50/50 px-10 py-10 border-t border-slate-100 flex items-center justify-center">
            {{ $auctions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

