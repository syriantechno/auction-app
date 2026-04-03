<div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Reference</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Vehicle</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Status</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Purchase</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-center">Profit</th>
                <th class="py-5 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">Entry Date</th>
                <th class="py-5 px-8 text-[0.6rem] text-slate-400 font-black uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($entries as $entry)
            @php
                $statusColors = [
                    'in_stock'        => 'bg-blue-50 text-blue-600 border-blue-100',
                    'qc_in_progress'  => 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse',
                    'qc_approved'     => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'payment_pending' => 'bg-purple-50 text-purple-600 border-purple-100',
                    'delivered'       => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    'sold'            => 'bg-slate-100 text-slate-500 border-slate-200',
                ];
                $sc = $statusColors[$entry->status] ?? 'bg-slate-50 text-slate-500';
            @endphp
            <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                {{-- Ref Code --}}
                <td class="py-5 px-8">
                    <span class="font-mono text-[0.75rem] font-black text-[#ff6900] bg-orange-50 border border-orange-200 px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5">
                        <i data-lucide="hash" class="w-3 h-3"></i>
                        {{ $entry->reference_code ?? '—' }}
                    </span>
                </td>
                {{-- Vehicle --}}
                <td class="py-5 px-6">
                    <div class="font-black text-slate-900 text-sm">
                        {{ optional($entry->car)->year }} {{ optional($entry->car)->make }} {{ optional($entry->car)->model }}
                    </div>
                    <div class="text-[0.6rem] text-slate-400 font-medium mt-0.5">VIN: {{ optional($entry->car)->vin ?? 'N/A' }}</div>
                </td>
                {{-- Status --}}
                <td class="py-5 px-6 text-center">
                    <span class="inline-flex px-3 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-[0.15em] border {{ $sc }}">
                        {{ str_replace('_', ' ', $entry->status) }}
                    </span>
                </td>
                {{-- Purchase price (what we pay the lead) --}}
                <td class="py-5 px-6 text-center">
                    <div class="font-black text-slate-900 tabular-nums">${{ number_format($entry->purchase_price) }}</div>
                    <div class="text-[0.6rem] text-slate-400 mt-0.5">Dealer bid: ${{ number_format($entry->dealer_bid) }}</div>
                </td>
                {{-- Profit --}}
                <td class="py-5 px-6 text-center">
                    <span class="font-black text-emerald-600 tabular-nums {{ $entry->profit_margin > 0 ? '' : 'text-red-500' }}">
                        ${{ number_format($entry->profit_margin) }}
                    </span>
                </td>
                {{-- Entry Date --}}
                <td class="py-5 px-6">
                    <div class="text-sm font-medium text-slate-600">{{ $entry->entry_date?->format('M d, Y') ?? '—' }}</div>
                    @if($entry->qc_completed_date)
                    <div class="text-[0.6rem] text-emerald-500 font-black mt-0.5">QC: {{ $entry->qc_completed_date->format('M d') }}</div>
                    @endif
                </td>
                {{-- Actions --}}
                <td class="py-5 px-8 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if(in_array($entry->status, ['in_stock', 'qc_in_progress']))
                        <button onclick="openQcModal({{ $entry->id }})"
                            class="h-9 px-4 rounded-md bg-amber-500 text-white text-[0.6rem] font-black uppercase tracking-widest hover:bg-amber-600 transition-all flex items-center gap-1.5">
                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i> QC Check
                        </button>
                        @endif

                        @if($entry->status === 'qc_approved')
                        <button onclick="openCompleteDeal({{ $entry->id }})"
                            class="h-9 px-4 rounded-md bg-emerald-500 text-white text-[0.6rem] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center gap-1.5">
                            <i data-lucide="flag" class="w-3.5 h-3.5"></i> Complete Deal
                        </button>
                        @endif

                        @if($entry->status === 'sold')
                        <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">
                            Sold {{ $entry->delivery_date?->format('M d, Y') }}
                        </span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-24 text-center">
                    <div class="flex flex-col items-center gap-4 opacity-30">
                        <i data-lucide="warehouse" class="w-12 h-12 text-slate-300"></i>
                        <p class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">No vehicles in stock</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($entries->hasPages())
    <div class="px-8 py-6 border-t border-slate-100">
        {{ $entries->withQueryString()->links() }}
    </div>
    @endif
</div>
