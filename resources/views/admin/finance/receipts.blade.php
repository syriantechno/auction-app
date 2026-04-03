@extends('admin.layout')
@section('title', 'Receipts — القبض')
@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    <x-admin-header icon="arrow-down-left" title="Receipts — القبض"
        subtitle="All incoming payments recorded">
        <x-slot name="actions">
            <button onclick="document.getElementById('newReceiptModal').classList.remove('hidden')"
                class="px-5 h-10 bg-emerald-600 text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> New Receipt
            </button>
        </x-slot>
    </x-admin-header>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg border border-emerald-100 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Receipt #</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Date</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">From</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Auction / Car</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Method</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Account</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-right font-black">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($receipts as $receipt)
                <tr class="hover:bg-emerald-50/20 transition-all group">
                    <td class="py-3.5 px-5">
                        <span class="font-black text-[0.75rem] text-[#031629] font-mono">{{ $receipt->receipt_number }}</span>
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-500">
                        {{ $receipt->receipt_date->format('M d, Y') }}
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-700">
                        {{ $receipt->received_from_name ?? optional($receipt->receivedFromUser)->name ?? '—' }}
                    </td>
                    <td class="py-3.5 px-5">
                        @if($receipt->auction)
                        <div class="text-[0.65rem] font-black text-[#031629]">
                            {{ optional($receipt->auction->car)->make }} {{ optional($receipt->auction->car)->model }}
                        </div>
                        <div class="text-[0.55rem] text-[#ff6900] font-black font-mono">{{ $receipt->auction->reference_code }}</div>
                        @else
                        <span class="text-slate-300 text-[0.65rem]">—</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-5">
                        @php
                            $mc=['cash'=>'bg-slate-100 text-slate-600','transfer'=>'bg-blue-50 text-blue-600','cheque'=>'bg-purple-50 text-purple-600','pos'=>'bg-teal-50 text-teal-600'];
                            $ms=$mc[$receipt->payment_method]??'bg-slate-100 text-slate-500';
                        @endphp
                        <span class="px-2.5 py-1 rounded-md text-[0.55rem] font-black uppercase tracking-widest {{ $ms }}">{{ ucfirst($receipt->payment_method) }}</span>
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-500">
                        {{ $receipt->financialAccount->name }}
                    </td>
                    <td class="py-3.5 px-5 text-right font-black text-emerald-600 tabular-nums text-sm">
                        ${{ number_format($receipt->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                        <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No receipts recorded yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($receipts->count() > 0)
            <tfoot class="bg-emerald-50/60 border-t border-emerald-100">
                <tr>
                    <td colspan="6" class="py-3 px-5 text-[0.65rem] font-black text-emerald-700 uppercase tracking-widest">Total (this page)</td>
                    <td class="py-3 px-5 text-right font-black text-emerald-700 tabular-nums">${{ number_format($receipts->sum('amount'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($receipts->hasPages())
    <div class="flex justify-center">{{ $receipts->links() }}</div>
    @endif

</div>

{{-- New Receipt Modal --}}
<div id="newReceiptModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="arrow-down-left" class="w-5 h-5 text-emerald-400"></i>
                <h3 class="font-black text-white text-sm">New Receipt — سند قبض</h3>
            </div>
            <button onclick="document.getElementById('newReceiptModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.receipts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="purpose" value="other">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Received From</label>
                    <input type="text" name="received_from_name" required placeholder="Name..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black outline-none focus:border-emerald-400">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Auction (optional)</label>
                    <select name="auction_id" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                        <option value="">— General Receipt —</option>
                        @foreach(\App\Models\Auction::with('car')->latest()->take(50)->get() as $a)
                        <option value="{{ $a->id }}">{{ $a->reference_code }} — {{ optional($a->car)->make }} {{ optional($a->car)->model }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account</label>
                    <select name="financial_account_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                        @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="pos">POS</option>
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Date</label>
                    <input type="date" name="receipt_date" value="{{ now()->toDateString() }}" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                </div>
            </div>
            <input type="text" name="reference" placeholder="Reference (cheque # / transfer ID)..."
                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
            <input type="text" name="purpose" placeholder="Purpose (e.g. auction payment, deposit)..." value="auction_payment"
                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
            <button type="submit" class="w-full h-12 bg-emerald-600 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                <i data-lucide="check" class="w-4 h-4"></i> Save Receipt
            </button>
        </form>
    </div>
</div>
@endsection
