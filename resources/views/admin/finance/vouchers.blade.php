@extends('admin.layout')
@section('title', 'Payments — الصرف')
@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    <x-admin-header icon="arrow-up-right" title="Payment" highlight="Vouchers" dot="rose"
        subtitle="All outgoing payment vouchers">
        <x-slot name="actions">
            <button onclick="document.getElementById('newVoucherModal').classList.remove('hidden')"
                class="px-5 h-10 bg-red-500 text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-red-600 transition-all shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> New Payment Voucher
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
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Voucher #</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Date</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Paid To</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Auction / Car</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Category</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-left font-black">Account</th>
                    <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-4 px-5 text-right font-black">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($vouchers as $voucher)
                <tr class="hover:bg-red-50/20 transition-all">
                    <td class="py-3.5 px-5">
                        <span class="font-black text-[0.75rem] text-[#031629] font-mono">{{ $voucher->voucher_number }}</span>
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-500">
                        {{ $voucher->voucher_date->format('M d, Y') }}
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-700">
                        {{ $voucher->paid_to_name }}
                    </td>
                    <td class="py-3.5 px-5">
                        @if($voucher->auction)
                        <div class="text-[0.65rem] font-black text-[#031629]">
                            {{ optional($voucher->auction->car)->make }} {{ optional($voucher->auction->car)->model }}
                        </div>
                        <div class="text-[0.55rem] text-[#ff6900] font-black font-mono">{{ $voucher->auction->reference_code }}</div>
                        @else
                        <span class="text-slate-300 text-[0.65rem]">—</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-5">
                        @php
                            $catColors = [
                                'lead_payment'  => 'bg-blue-50 text-blue-600',
                                'commission'    => 'bg-purple-50 text-purple-600',
                                'maintenance'   => 'bg-amber-50 text-amber-600',
                                'transport'     => 'bg-teal-50 text-teal-600',
                                'other'         => 'bg-slate-100 text-slate-500',
                            ];
                            $cc = $catColors[$voucher->category] ?? 'bg-slate-100 text-slate-500';
                        @endphp
                        <span class="px-2.5 py-1 rounded-md text-[0.55rem] font-black uppercase tracking-widest {{ $cc }}">{{ ucfirst(str_replace('_', ' ', $voucher->category)) }}</span>
                    </td>
                    <td class="py-3.5 px-5 text-[0.72rem] font-bold text-slate-500">
                        {{ $voucher->financialAccount->name }}
                    </td>
                    <td class="py-3.5 px-5 text-right font-black text-red-500 tabular-nums text-sm">
                        ${{ number_format($voucher->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-200 mx-auto mb-2"></i>
                        <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No payment vouchers yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($vouchers->count() > 0)
            <tfoot class="bg-red-50/60 border-t border-red-100">
                <tr>
                    <td colspan="6" class="py-3 px-5 text-[0.65rem] font-black text-red-600 uppercase tracking-widest">Total Paid Out (this page)</td>
                    <td class="py-3 px-5 text-right font-black text-red-600 tabular-nums">${{ number_format($vouchers->sum('amount'), 2) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($vouchers->hasPages())
    <div class="flex justify-center">{{ $vouchers->links() }}</div>
    @endif

</div>

{{-- New Voucher Modal --}}
<div id="newVoucherModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="arrow-up-right" class="w-5 h-5 text-red-400"></i>
                <h3 class="font-black text-white text-sm">New Payment Voucher — سند صرف</h3>
            </div>
            <button onclick="document.getElementById('newVoucherModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.vouchers.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Paid To</label>
                    <input type="text" name="paid_to_name" required placeholder="Lead owner / vendor..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black outline-none focus:border-red-400">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Category</label>
                    <select name="category" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        <option value="lead_payment">Lead Payment</option>
                        <option value="commission">Commission</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="transport">Transport</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Auction (optional)</label>
                    <select name="auction_id" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        <option value="">— General Voucher —</option>
                        @foreach(\App\Models\Auction::with('car')->latest()->take(50)->get() as $a)
                        <option value="{{ $a->id }}">{{ $a->reference_code }} — {{ optional($a->car)->make }} {{ optional($a->car)->model }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account</label>
                    <select name="financial_account_id" required class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Method</label>
                    <select name="payment_method" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Date</label>
                    <input type="date" name="voucher_date" value="{{ now()->toDateString() }}" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Reference</label>
                    <input type="text" name="reference" placeholder="Cheque # / Transfer ID..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                </div>
            </div>
            <input type="text" name="description" placeholder="Description / purpose..."
                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
            <button type="submit" class="w-full h-12 bg-red-500 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-red-500/20">
                <i data-lucide="check" class="w-4 h-4"></i> Save Voucher
            </button>
        </form>
    </div>
</div>
@endsection
