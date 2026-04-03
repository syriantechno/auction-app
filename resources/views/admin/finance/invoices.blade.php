@extends('admin.layout')
@section('title', 'Invoices')
@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    <x-admin-header icon="file-text" title="Invoices" subtitle="All auction invoices">
        <x-slot name="actions">
            <div class="flex gap-2">
                @foreach([''=>'All','pending'=>'Pending','partial'=>'Partial','paid'=>'Paid'] as $val=>$lbl)
                <a href="{{ route('admin.finance.invoices', $val ? ['status'=>$val] : []) }}"
                   class="px-4 h-9 rounded-lg text-[0.6rem] font-black uppercase tracking-widest flex items-center transition-all {{ request('status')===$val ? 'bg-[#1d293d] text-white' : 'bg-white border border-slate-200 text-slate-500 hover:border-slate-300' }}">
                    {{ $lbl }}
                </a>
                @endforeach
            </div>
        </x-slot>
    </x-admin-header>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-left font-black">Invoice</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-left font-black">Auction / Vehicle</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-left font-black">Dealer</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-right font-black">Dealer Paid</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-right font-black">Net Profit</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-right font-black">Remaining</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-center font-black">Status</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-6 text-right font-black">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($invoices as $invoice)
                @php
                    $sc=['pending'=>'bg-amber-50 text-amber-600','partial'=>'bg-blue-50 text-blue-600','paid'=>'bg-emerald-50 text-emerald-600','cancelled'=>'bg-red-50 text-red-500'];
                    $c = $sc[$invoice->status] ?? 'bg-slate-100 text-slate-500';
                @endphp
                <tr class="hover:bg-slate-50/50 transition-all">
                    <td class="py-4 px-6">
                        <div class="font-black text-[0.75rem] text-[#031629]">{{ $invoice->invoice_number ?? '#INV-'.$invoice->id }}</div>
                        <div class="text-[0.55rem] text-slate-400 font-bold mt-0.5">{{ $invoice->type ?? 'auction_sale' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-[0.75rem] font-black text-[#031629]">{{ optional($invoice->auction?->car)->year }} {{ optional($invoice->auction?->car)->make }} {{ optional($invoice->auction?->car)->model }}</div>
                        <div class="text-[0.55rem] text-[#ff6900] font-black font-mono mt-0.5">{{ $invoice->auction?->reference_code }}</div>
                    </td>
                    <td class="py-4 px-6 text-[0.75rem] font-bold text-slate-600">{{ optional($invoice->user)->name ?? '—' }}</td>
                    <td class="py-4 px-6 text-right font-black text-[#031629] tabular-nums">${{ number_format((float)$invoice->dealer_final_price) }}</td>
                    <td class="py-4 px-6 text-right font-black tabular-nums {{ (float)$invoice->net_profit >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        ${{ number_format((float)$invoice->net_profit) }}
                    </td>
                    <td class="py-4 px-6 text-right font-black tabular-nums {{ (float)$invoice->amount_remaining > 0 ? 'text-red-500' : 'text-emerald-600' }}">
                        ${{ number_format((float)$invoice->amount_remaining) }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-3 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-widest {{ $c }}">{{ ucfirst($invoice->status) }}</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="{{ route('admin.finance.invoice.show', $invoice) }}"
                           class="w-8 h-8 bg-slate-50 border border-slate-100 text-slate-400 rounded-lg inline-flex items-center justify-center hover:bg-[#1d293d] hover:text-white transition-all">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-20 text-center text-slate-300 font-black uppercase tracking-widest text-[0.65rem]">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
    <div class="flex justify-center">{{ $invoices->withQueryString()->links() }}</div>
    @endif

</div>
@endsection
