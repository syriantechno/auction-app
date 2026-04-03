@extends('admin.layout')

@section('title', 'Financial Ledger')

@section('content')
<div class="px-1">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Revenue Ledger</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-sky-400 decoration-2 italic">Automated Billing & Commission Engine</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-emerald-50 px-4 py-2 rounded-md border border-emerald-100 flex flex-col items-center">
                <span class="text-[0.5rem] font-black text-emerald-600 uppercase tracking-widest">Total Resolved</span>
                <span class="text-sm font-black text-emerald-700 tabular-nums">${{ number_format($invoices->where('status', 'paid')->sum('total_amount')) }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-md mb-6 font-bold border border-emerald-100 flex items-center gap-2 text-xs shadow-sm">
            <i data-lucide="check-circle" class="w-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-[#f1f5f9] overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#f1f5f9] bg-[#f8fafc]">
                    <th class="text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Billing ID</th>
                    <th class="text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Target Account</th>
                    <th class="text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest text-center">Status</th>
                    <th class="text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Value Matrix</th>
                    <th class="text-right text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Operation</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-[#fbfcfe] transition-all">
                    <td class="py-4 px-6">
                        <div class="flex flex-col gap-1">
                            <span class="font-black text-[0.8rem] text-[#111827] tabular-nums">#INV-{{ $invoice->id }}</span>
                            <span class="text-[0.55rem] text-[#adb5bd] font-black uppercase tracking-widest">{{ $invoice->type ?? 'Auction Sale' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-sky-50 flex items-center justify-center text-sky-500 font-black text-[0.6rem] shadow-sm">INC</div>
                            <div>
                                <div class="font-bold text-[0.8rem] text-[#111827]">{{ optional($invoice->user)->name ?? 'Member Account' }}</div>
                                <div class="text-[0.65rem] font-bold text-[#adb5bd]">{{ optional($invoice->auction->car)->make ?? 'System' }} {{ optional($invoice->auction->car)->model ?? 'Item' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $statusMap = [
                                'pending' => ['bg-amber-50 text-amber-600 border-amber-100', 'In Queue'],
                                'paid' => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Verified'],
                                'cancelled' => ['bg-red-50 text-red-500 border-red-100', 'Annulled'],
                            ];
                            $style = $statusMap[$invoice->status] ?? ['bg-gray-50 text-gray-400', $invoice->status];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[0.55rem] font-black uppercase tracking-widest border {{ $style[0] }}">
                            {{ $style[1] }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-[0.85rem] font-black text-[#111827] tabular-nums">${{ number_format($invoice->total_amount) }}</div>
                        <div class="text-[0.55rem] text-[#adb5bd] font-black uppercase tracking-widest">Fees Included: ${{ number_format($invoice->commission_amount ?? 0) }}</div>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-[#111827] hover:text-white transition-all shadow-sm">
                                <i data-lucide="eye" class="w-3.5"></i>
                            </a>
                            <a href="#" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-zinc-800 hover:text-white transition-all border border-gray-100 shadow-sm"><i data-lucide="download" class="w-3.5"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.7rem]">Revenue ledger is empty. No financial activity.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
        <div class="mt-6 px-1">
            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection

