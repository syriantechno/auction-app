@extends('admin.layout')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('content')
<div class="px-1 space-y-6 animate-in fade-in duration-500">

    <x-admin-header icon="file-text" title="{{ $invoice->invoice_number ?? 'Invoice' }}"
        :subtitle="optional($invoice->auction?->car)->year . ' ' . optional($invoice->auction?->car)->make . ' ' . optional($invoice->auction?->car)->model . ' · ' . $invoice->auction?->reference_code">
        <x-slot name="actions">
            <a href="{{ route('admin.finance.invoices') }}" class="px-4 h-10 bg-white border border-slate-200 text-slate-600 rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-slate-50 transition-all">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
            </a>
            {{-- Status Badge --}}
            @php
                $statusColors = ['pending'=>'bg-amber-50 text-amber-600 border-amber-200','partial'=>'bg-blue-50 text-blue-600 border-blue-200','paid'=>'bg-emerald-50 text-emerald-600 border-emerald-200','cancelled'=>'bg-red-50 text-red-500 border-red-100'];
                $sc = $statusColors[$invoice->status] ?? 'bg-slate-50 text-slate-500 border-slate-100';
            @endphp
            <span class="px-4 py-2 rounded-lg text-[0.6rem] font-black uppercase tracking-widest border {{ $sc }}">
                {{ ucfirst($invoice->status) }}
            </span>
        </x-slot>
    </x-admin-header>

    {{-- ═══════════════════════════════════════════════════════════
         P&L SUMMARY
    ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3">
        @php
            $pnl = [
                ['label'=>'Lead Asked',    'val'=>$invoice->lead_asking_price,   'icon'=>'user',        'color'=>'slate',  'note'=>'Lead asking price'],
                ['label'=>'Dealer Paid',   'val'=>$invoice->dealer_final_price,  'icon'=>'gavel',       'color'=>'orange', 'note'=>'Winning bid'],
                ['label'=>'Gross Profit',  'val'=>$invoice->gross_profit,        'icon'=>'trending-up', 'color'=>'emerald','note'=>'Before expenses'],
                ['label'=>'Expenses',      'val'=>$invoice->total_expenses,       'icon'=>'package',     'color'=>'amber',  'note'=>'All costs'],
                ['label'=>'Net Profit',    'val'=>$invoice->net_profit,           'icon'=>'dollar-sign', 'color'=>$invoice->net_profit >= 0 ? 'emerald' : 'red', 'note'=>'After expenses'],
                ['label'=>'Outstanding',   'val'=>$invoice->amount_remaining,    'icon'=>'clock',       'color'=>$invoice->amount_remaining > 0 ? 'red' : 'emerald', 'note'=>'Yet to collect'],
            ];
            $cm = ['slate'=>'bg-slate-50 text-slate-500 border-slate-200','orange'=>'bg-orange-50 text-[#ff6900] border-orange-200','emerald'=>'bg-emerald-50 text-emerald-600 border-emerald-200','amber'=>'bg-amber-50 text-amber-600 border-amber-200','red'=>'bg-red-50 text-red-500 border-red-200'];
        @endphp
        @foreach($pnl as $item)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg {{ $cm[$item['color']] }} flex items-center justify-center border">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                </div>
                <span class="text-[0.55rem] text-slate-400 font-black uppercase tracking-widest">{{ $item['label'] }}</span>
            </div>
            <div class="text-xl font-black text-[#031629] tabular-nums tracking-tighter">
                ${{ number_format((float)($item['val'] ?? 0), 2) }}
            </div>
            <div class="text-[0.5rem] text-slate-300 font-medium mt-0.5">{{ $item['note'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         TABS LAYOUT
    ═══════════════════════════════════════════════════════════ --}}
    <div x-data="{ tab: 'receipts' }">

        {{-- Tab Pills --}}
        <div class="flex gap-2 border-b border-slate-200 mb-6">
            @foreach(['receipts'=>['icon'=>'arrow-down-left','label'=>'Receipts (سندات قبض)','count'=>$invoice->receipts->count()],'vouchers'=>['icon'=>'arrow-up-right','label'=>'Vouchers (سندات صرف)','count'=>$vouchers->count()],'expenses'=>['icon'=>'package','label'=>'Expenses','count'=>$expenses->count()],'details'=>['icon'=>'info','label'=>'Invoice Details','count'=>null]] as $key => $tab)
            <button @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'border-b-2 border-[#ff6900] text-[#ff6900]' : 'text-slate-400 hover:text-slate-700'"
                class="flex items-center gap-2 px-4 py-3 text-[0.7rem] font-black uppercase tracking-widest transition-all">
                <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5"></i>
                {{ $tab['label'] }}
                @if($tab['count'] !== null)
                <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[0.5rem]">{{ $tab['count'] }}</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- ── TAB 1: RECEIPTS ────────────────────────────── --}}
        <div x-show="tab === 'receipts'" x-transition>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-black text-[#031629] uppercase tracking-widest">Payment Receipts</h3>
                <button onclick="document.getElementById('receiptModal').classList.remove('hidden')"
                    class="px-4 h-9 bg-emerald-600 text-white rounded-lg font-black text-[0.6rem] uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Receipt
                </button>
            </div>
            @if($invoice->receipts->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Receipt #</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Date</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Method</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Account</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-right font-black">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($invoice->receipts as $receipt)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-4 px-5 font-black text-[0.75rem] text-[#031629]">{{ $receipt->receipt_number }}</td>
                            <td class="py-4 px-5 text-[0.7rem] text-slate-500 font-bold">{{ $receipt->receipt_date->format('M d, Y') }}</td>
                            <td class="py-4 px-5">
                                <span class="px-2 py-1 rounded-md text-[0.55rem] font-black uppercase tracking-widest bg-slate-100 text-slate-600">{{ $receipt->payment_method }}</span>
                            </td>
                            <td class="py-4 px-5 text-[0.7rem] text-slate-500 font-bold">{{ $receipt->financialAccount->name }}</td>
                            <td class="py-4 px-5 text-right font-black text-emerald-600 tabular-nums">${{ number_format($receipt->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-emerald-50/50 border-t border-emerald-100">
                        <tr>
                            <td colspan="4" class="py-3 px-5 text-[0.65rem] font-black text-emerald-700 uppercase tracking-widest">Total Received</td>
                            <td class="py-3 px-5 text-right font-black text-emerald-700 tabular-nums text-sm">${{ number_format($invoice->amount_received, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="bg-white rounded-2xl border-2 border-dashed border-slate-100 py-12 text-center">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No receipts recorded yet</p>
            </div>
            @endif
        </div>

        {{-- ── TAB 2: PAYMENT VOUCHERS ────────────────────── --}}
        <div x-show="tab === 'vouchers'" x-transition>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-black text-[#031629] uppercase tracking-widest">Payment Vouchers</h3>
                <button onclick="document.getElementById('voucherModal').classList.remove('hidden')"
                    class="px-4 h-9 bg-red-500 text-white rounded-lg font-black text-[0.6rem] uppercase tracking-widest flex items-center gap-2 hover:bg-red-600 transition-all shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Voucher
                </button>
            </div>
            @if($vouchers->count() > 0)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50/80 border-b border-slate-100">
                        <tr>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Voucher #</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Date</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Paid To</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-left font-black">Category</th>
                            <th class="text-[0.6rem] text-slate-400 uppercase tracking-widest py-3 px-5 text-right font-black">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($vouchers as $voucher)
                        <tr class="hover:bg-red-50/20">
                            <td class="py-4 px-5 font-black text-[0.75rem] text-[#031629]">{{ $voucher->voucher_number }}</td>
                            <td class="py-4 px-5 text-[0.7rem] text-slate-500 font-bold">{{ $voucher->voucher_date->format('M d, Y') }}</td>
                            <td class="py-4 px-5 text-[0.7rem] font-bold text-slate-600">{{ $voucher->paid_to_name }}</td>
                            <td class="py-4 px-5">
                                <span class="px-2 py-1 rounded-md text-[0.55rem] font-black uppercase tracking-widest bg-amber-50 text-amber-600">{{ ucfirst($voucher->category) }}</span>
                            </td>
                            <td class="py-4 px-5 text-right font-black text-red-500 tabular-nums">${{ number_format($voucher->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-red-50/50 border-t border-red-100">
                        <tr>
                            <td colspan="4" class="py-3 px-5 text-[0.65rem] font-black text-red-600 uppercase tracking-widest">Total Paid Out</td>
                            <td class="py-3 px-5 text-right font-black text-red-600 tabular-nums text-sm">${{ number_format($vouchers->sum('amount'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="bg-white rounded-2xl border-2 border-dashed border-slate-100 py-12 text-center">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No vouchers for this auction</p>
            </div>
            @endif
        </div>

        {{-- ── TAB 3: EXPENSES ────────────────────────────── --}}
        <div x-show="tab === 'expenses'" x-transition>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-black text-[#031629] uppercase tracking-widest">Car Expenses</h3>
                <button onclick="document.getElementById('expenseModal').classList.remove('hidden')"
                    class="px-4 h-9 bg-amber-500 text-white rounded-lg font-black text-[0.6rem] uppercase tracking-widest flex items-center gap-2 hover:bg-amber-600 transition-all shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Expense
                </button>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @forelse($expenses as $expense)
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 flex items-start justify-between gap-4 hover:border-amber-200 transition-all">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-amber-50 border border-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="package" class="w-4 h-4 text-amber-600"></i>
                        </div>
                        <div>
                            <div class="text-[0.75rem] font-black text-[#031629]">{{ $expense->description }}</div>
                            <div class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ ucfirst($expense->category) }} · {{ $expense->expense_date->format('M d, Y') }}</div>
                            @if($expense->receipt_ref)
                            <div class="text-[0.5rem] text-slate-300 font-medium mt-0.5">Ref: {{ $expense->receipt_ref }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="text-base font-black text-amber-600 tabular-nums">${{ number_format($expense->amount, 2) }}</span>
                        <form action="{{ route('admin.finance.expenses.destroy', $expense) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Remove this expense?')"
                                class="w-7 h-7 bg-red-50 text-red-400 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-2 bg-white rounded-2xl border-2 border-dashed border-slate-100 py-12 text-center">
                    <i data-lucide="package" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                    <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No expenses recorded</p>
                </div>
                @endforelse
            </div>
            @if($expenses->count() > 0)
            <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-5 py-3 flex justify-between items-center">
                <span class="text-[0.65rem] font-black text-amber-700 uppercase tracking-widest">Total Expenses</span>
                <span class="font-black text-amber-700 tabular-nums">${{ number_format($expenses->sum('amount'), 2) }}</span>
            </div>
            @endif
        </div>

        {{-- ── TAB 4: INVOICE DETAILS ──────────────────────── --}}
        <div x-show="tab === 'details'" x-transition>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Car & Auction Info --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-4">Auction Info</h4>
                    <div class="space-y-3">
                        @php
                            $car = $invoice->auction?->car;
                            $details = [
                                'Reference'   => $invoice->auction?->reference_code ?? '—',
                                'Vehicle'     => ($car?->year . ' ' . $car?->make . ' ' . $car?->model),
                                'VIN'         => $car?->vin ?? '—',
                                'Dealer'      => optional($invoice->user)->name ?? '—',
                                'Dealer Email'=> optional($invoice->user)->email ?? '—',
                                'Invoice #'   => $invoice->invoice_number ?? '—',
                                'Due Date'    => $invoice->due_date ?? 'Not set',
                                'Type'        => ucfirst($invoice->type ?? 'auction_sale'),
                            ];
                        @endphp
                        @foreach($details as $lbl => $val)
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-[0.6rem] text-slate-400 font-black uppercase tracking-widest">{{ $lbl }}</span>
                            <span class="text-[0.75rem] font-black text-[#031629]">{{ $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Edit Form --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h4 class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest mb-4">Edit Invoice</h4>
                    <form action="{{ route('admin.finance.invoice.update', $invoice) }}" method="POST" class="space-y-4">
                        @csrf @method('PATCH')
                        <div>
                            <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Lead Asking Price ($)</label>
                            <input type="number" name="lead_asking_price" step="100"
                                value="{{ $invoice->lead_asking_price }}"
                                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black text-slate-800 outline-none focus:border-[#ff6900]">
                        </div>
                        <div>
                            <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Due Date</label>
                            <input type="date" name="due_date" value="{{ $invoice->due_date }}"
                                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm text-slate-700 outline-none focus:border-[#ff6900]">
                        </div>
                        <div>
                            <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Internal Notes</label>
                            <textarea name="internal_notes" rows="3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-700 outline-none focus:border-[#ff6900] resize-none">{{ $invoice->internal_notes }}</textarea>
                        </div>
                        <button type="submit" class="w-full h-11 bg-[#1d293d] text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest hover:bg-[#111827] transition-all">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>{{-- end x-data tabs --}}
</div>

{{-- ═══════════════════════════════════════════════════
     RECEIPT MODAL
═══════════════════════════════════════════════════ --}}
<div id="receiptModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="arrow-down-left" class="w-5 h-5 text-emerald-400"></i>
                <h3 class="font-black text-white text-sm">Add Receipt — سند قبض</h3>
            </div>
            <button onclick="document.getElementById('receiptModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.receipts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="auction_id" value="{{ $invoice->auction_id }}">
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
            <input type="hidden" name="purpose" value="auction_payment">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Received From</label>
                    <input type="text" name="received_from_name" value="{{ optional($invoice->user)->name }}"
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" value="{{ $invoice->amount_remaining }}" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black outline-none focus:border-emerald-400">
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
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account</label>
                    <select name="financial_account_id" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                        @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Date</label>
                    <input type="date" name="receipt_date" value="{{ now()->toDateString() }}" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Reference</label>
                    <input type="text" name="reference" placeholder="Cheque # / Transfer ID..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-emerald-400">
                </div>
            </div>
            <button type="submit" class="w-full h-12 bg-emerald-600 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                <i data-lucide="check" class="w-4 h-4"></i> Record Receipt
            </button>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     VOUCHER MODAL
═══════════════════════════════════════════════════ --}}
<div id="voucherModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="arrow-up-right" class="w-5 h-5 text-red-400"></i>
                <h3 class="font-black text-white text-sm">Add Voucher — سند صرف</h3>
            </div>
            <button onclick="document.getElementById('voucherModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.vouchers.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="auction_id" value="{{ $invoice->auction_id }}">
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
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account</label>
                    <select name="financial_account_id" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
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
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Method</label>
                    <select name="payment_method" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
                        <option value="cash">Cash</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
            </div>
            <input type="text" name="description" placeholder="Description / notes..."
                class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-red-400">
            <button type="submit" class="w-full h-12 bg-red-500 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-red-500/20">
                <i data-lucide="check" class="w-4 h-4"></i> Record Voucher
            </button>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     EXPENSE MODAL
═══════════════════════════════════════════════════ --}}
<div id="expenseModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="package" class="w-5 h-5 text-amber-400"></i>
                <h3 class="font-black text-white text-sm">Add Car Expense</h3>
            </div>
            <button onclick="document.getElementById('expenseModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.expenses.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="auction_id" value="{{ $invoice->auction_id }}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Category</label>
                    <select name="category" class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-amber-400">
                        @foreach(\App\Models\AuctionExpense::categories() as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Amount ($)</label>
                    <input type="number" name="amount" step="0.01" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black outline-none focus:border-amber-400">
                </div>
            </div>
            <div>
                <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Description</label>
                <input type="text" name="description" required placeholder="e.g. Full engine inspection..."
                    class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-amber-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Date</label>
                    <input type="date" name="expense_date" value="{{ now()->toDateString() }}" required
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-amber-400">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Receipt Ref</label>
                    <input type="text" name="receipt_ref" placeholder="Ext. receipt #..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-amber-400">
                </div>
            </div>
            <button type="submit" class="w-full h-12 bg-amber-500 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-amber-600 transition-all flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Add Expense
            </button>
        </form>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', () => { if (typeof lucide !== 'undefined') lucide.createIcons(); });</script>
@endsection
