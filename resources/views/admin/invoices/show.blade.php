@extends('admin.layout')

@section('title', 'Financial Statement Review')

@section('content')
<div class="px-1 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Statement: #INV-{{ $invoice->id }}</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none">Global Revenue Hub Transmission</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
                <i data-lucide="arrow-left" class="w-3.5"></i> Back to Ledger
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-black text-white rounded-md font-black shadow-lg hover:bg-zinc-800 transition-all flex items-center gap-2 text-xs uppercase tracking-widest">
                <i data-lucide="printer" class="w-3.5"></i> Export PDF
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-xl border border-[#f1f5f9] overflow-hidden">
        {{-- Header Status --}}
        <div class="bg-[#111827] p-8 text-white flex justify-between items-start">
            <div class="flex flex-col gap-2">
                <span class="text-[0.6rem] text-gray-400 font-extrabold uppercase tracking-widest">Billing Entity</span>
                <div class="text-xl font-black italic tracking-tighter">UNITE<span class="text-zinc-600">CAR</span> AUCTION</div>
                <div class="text-[0.55rem] text-gray-500 font-black uppercase tracking-widest">Global Marketplace Registry</div>
            </div>
            <div class="text-right flex flex-col gap-1 items-end">
                @php
                    $statusColor = $invoice->status == 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                @endphp
                <span class="px-3 py-1.5 rounded-full text-[0.55rem] font-black uppercase tracking-widest border border-white/10 {{ $statusColor }}">
                    {{ strtoupper($invoice->status) }}
                </span>
                <span class="text-[0.65rem] font-bold text-gray-500 tabular-nums">Generated: {{ $invoice->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- Counterparty Info --}}
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-3">
                    <span class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest">Beneficiary Account</span>
                    <div class="bg-[#f8fafc] rounded-md p-4 border border-[#f1f5f9]">
                        <div class="font-black text-[0.85rem] text-[#111827] mb-1">{{ optional($invoice->user)->name }}</div>
                        <div class="text-[0.7rem] text-[#5a6a85] font-bold">{{ optional($invoice->user)->email }}</div>
                    </div>
                </div>
                <div class="space-y-3">
                    <span class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest">Subject Asset</span>
                    <div class="bg-[#f8fafc] rounded-md p-4 border border-[#f1f5f9]">
                        <div class="font-black text-[0.85rem] text-[#111827] mb-1">
                            {{ optional($invoice->auction->car)->year }} {{ optional($invoice->auction->car)->make }} {{ optional($invoice->auction->car)->model }}
                        </div>
                        <div class="text-[0.7rem] text-[#5a6a85] font-bold">Asset ID: #UN-{{ optional($invoice->auction)->id }}</div>
                    </div>
                </div>
            </div>

            {{-- Audit Matrix Table --}}
            <div class="space-y-4">
                <span class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest">Financial Calculation Ledger</span>
                <div class="bg-[#fcfdfe] rounded-lg border border-[#f1f5f9] overflow-hidden">
                    <div class="p-5 flex justify-between items-center border-b border-[#f1f5f9]">
                        <span class="text-[0.75rem] font-black text-[#5a6a85]">Primary Auction Bid Total</span>
                        <span class="text-[0.85rem] font-black text-[#111827] tabular-nums">${{ number_format($invoice->amount) }}</span>
                    </div>
                    <div class="p-5 flex justify-between items-center border-b border-[#f1f5f9]">
                        <span class="text-[0.75rem] font-black text-[#5a6a85]">Marketplace Transaction Fee (Comm.)</span>
                        <span class="text-[0.85rem] font-black text-[#111827] tabular-nums">+ ${{ number_format($invoice->commission_amount ?? 0) }}</span>
                    </div>
                    <div class="p-5 flex justify-between items-center bg-[#f8fafc]">
                        <span class="text-[0.8rem] font-black text-[#111827] uppercase tracking-widest">NET PAYABLE TOTAL</span>
                        <span class="text-xl font-black text-[#111827] tabular-nums underline decoration-2 decoration-emerald-500">${{ number_format($invoice->total_amount) }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Zone --}}
            <div class="bg-gray-50 rounded-lg p-6 border border-[#f1f5f9] flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center shadow-lg"><i data-lucide="check" class="w-5 h-5"></i></div>
                    <div>
                        <div class="text-[0.8rem] font-black text-[#111827] uppercase tracking-tight">Sync Payment Data</div>
                        <div class="text-[0.6rem] text-[#adb5bd] font-bold uppercase tracking-widest">Update global financial status</div>
                    </div>
                </div>
                <form action="{{ route('admin.invoices.status', $invoice) }}" method="POST" class="flex gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="paid">
                    @if($invoice->status != 'paid')
                        <button type="submit" class="px-5 py-2 bg-[#111827] text-white rounded-md font-black text-[0.65rem] uppercase tracking-widest shadow-lg hover:bg-black transition-all">Mark as Settled</button>
                    @else
                        <span class="text-[0.65rem] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4"></i> Settled At {{ $invoice->paid_at->format('M d, H:i') }}</span>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

