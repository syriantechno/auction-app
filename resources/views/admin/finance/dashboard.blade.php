@extends('admin.layout')
@section('title', 'Finance Dashboard')
@section('content')
<div class="px-1 space-y-6 animate-in fade-in duration-500">

    <x-admin-header icon="landmark" title="Finance Dashboard"
        subtitle="Real-time financial overview">
        <x-slot name="actions">
            <a href="{{ route('admin.finance.receipts') }}" class="px-5 h-10 bg-emerald-600 text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-emerald-700 transition-all shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> New Receipt
            </a>
            <a href="{{ route('admin.finance.vouchers') }}" class="px-5 h-10 bg-red-500 text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-red-600 transition-all shadow-lg">
                <i data-lucide="minus" class="w-4 h-4"></i> New Voucher
            </a>
            <a href="{{ route('admin.finance.invoices') }}" class="px-5 h-10 bg-[#1d293d] text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-[#111827] transition-all shadow-lg">
                <i data-lucide="file-text" class="w-4 h-4"></i> Invoices
            </a>
        </x-slot>
    </x-admin-header>

    {{-- ── KPI CARDS ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
            $kpis = [
                ['label'=>'Total Received',   'val'=>'$'.number_format($totalReceived),  'icon'=>'trending-up',    'color'=>'emerald', 'sub'=>'All time receipts'],
                ['label'=>'Total Paid Out',   'val'=>'$'.number_format($totalPaid),      'icon'=>'trending-down',  'color'=>'red',     'sub'=>'Vouchers total'],
                ['label'=>'Expenses',         'val'=>'$'.number_format($totalExpenses),  'icon'=>'package',        'color'=>'amber',   'sub'=>'Per-car costs'],
                ['label'=>'Pending Invoices', 'val'=>$pendingInvoices,                   'icon'=>'clock',          'color'=>'orange',  'sub'=>'Awaiting payment'],
                ['label'=>'Partial Paid',     'val'=>$partialInvoices,                   'icon'=>'pie-chart',      'color'=>'blue',    'sub'=>'Partially settled'],
            ];
            $colorMap = ['emerald'=>'text-emerald-600 bg-emerald-50 border-emerald-100','red'=>'text-red-500 bg-red-50 border-red-100','amber'=>'text-amber-600 bg-amber-50 border-amber-100','orange'=>'text-[#ff6900] bg-orange-50 border-orange-100','blue'=>'text-blue-600 bg-blue-50 border-blue-100'];
        @endphp
        @foreach($kpis as $k)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl {{ $colorMap[$k['color']] }} flex items-center justify-center border">
                    <i data-lucide="{{ $k['icon'] }}" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-[#031629] tracking-tighter tabular-nums">{{ $k['val'] }}</div>
            <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mt-1">{{ $k['label'] }}</div>
            <div class="text-[0.55rem] text-slate-300 font-medium mt-0.5">{{ $k['sub'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ── ACCOUNT BALANCES ─────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($accounts as $account)
        <div class="bg-gradient-to-br {{ $account->type === 'bank' ? 'from-[#1d293d] to-[#0f1a2e]' : 'from-slate-800 to-slate-900' }} rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <i data-lucide="{{ $account->type === 'bank' ? 'building-2' : 'wallet' }}" class="w-5 h-5 text-[#ff6900]"></i>
                </div>
                <span class="text-[0.55rem] text-white/40 font-black uppercase tracking-widest">{{ strtoupper($account->type) }}</span>
            </div>
            <div class="text-3xl font-black tracking-tighter tabular-nums">${{ number_format($account->current_balance, 2) }}</div>
            <div class="text-sm font-bold text-white/60 mt-1">{{ $account->name }}</div>
            @if($account->bank_name)
            <div class="text-[0.6rem] text-white/30 mt-1">{{ $account->bank_name }}</div>
            @endif
        </div>
        @endforeach
        <a href="{{ route('admin.finance.accounts') }}" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center gap-2 hover:border-[#ff6900] hover:bg-orange-50/50 transition-all group">
            <div class="w-10 h-10 bg-slate-100 group-hover:bg-[#ff6900] rounded-xl flex items-center justify-center transition-all">
                <i data-lucide="plus" class="w-5 h-5 text-slate-400 group-hover:text-white transition-all"></i>
            </div>
            <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest">Add Account</span>
        </a>
    </div>

    {{-- ── CHART + RECENT ACTIVITY ─────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Monthly Chart --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-black text-[#031629] uppercase tracking-widest mb-6">6-Month Cash Flow</h3>
            <div class="space-y-3">
                @foreach($monthlyStats as $stat)
                @php $max = max(1, $monthlyStats->max(fn($s)=>max($s['received'],$s['paid'])),'1'); @endphp
                <div>
                    <div class="flex justify-between text-[0.6rem] text-slate-400 font-bold mb-1">
                        <span>{{ $stat['month'] }}</span>
                        <span class="text-emerald-600">+${{ number_format($stat['received']) }}</span>
                        <span class="text-red-500">-${{ number_format($stat['paid']) }}</span>
                    </div>
                    <div class="h-2 bg-slate-50 rounded-full overflow-hidden flex gap-0.5">
                        <div class="h-full bg-emerald-400 rounded-full transition-all" style="width:{{ min(100, $stat['received']/$max*100) }}%"></div>
                        <div class="h-full bg-red-400 rounded-full transition-all" style="width:{{ min(100, $stat['paid']/$max*100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Receipts --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-black text-[#031629] uppercase tracking-widest">Recent Receipts</h3>
                <a href="{{ route('admin.finance.receipts') }}" class="text-[0.6rem] text-[#ff6900] font-black uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="space-y-2">
                @forelse($recentReceipts as $receipt)
                <div class="flex items-center justify-between p-3 bg-slate-50/70 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="arrow-down-left" class="w-3.5 h-3.5 text-emerald-600"></i>
                        </div>
                        <div>
                            <div class="text-[0.7rem] font-black text-[#031629]">{{ $receipt->receipt_number }}</div>
                            <div class="text-[0.55rem] text-slate-400 font-bold">{{ $receipt->received_from_name ?? '—' }} · {{ optional($receipt->auction?->car)->make }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-black text-emerald-600 tabular-nums">${{ number_format($receipt->amount) }}</div>
                        <div class="text-[0.5rem] text-slate-300 font-bold">{{ $receipt->receipt_date->format('M d') }}</div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-slate-300 text-[0.65rem] font-black uppercase tracking-widest">No receipts yet</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── RECENT VOUCHERS ──────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-sm font-black text-[#031629] uppercase tracking-widest">Recent Payment Vouchers</h3>
            <a href="{{ route('admin.finance.vouchers') }}" class="text-[0.6rem] text-[#ff6900] font-black uppercase tracking-widest hover:underline">View All</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @forelse($recentVouchers as $voucher)
            <div class="bg-red-50/60 border border-red-100 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[0.55rem] font-black text-red-400 uppercase tracking-widest">{{ $voucher->voucher_number }}</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-red-400"></i>
                </div>
                <div class="text-lg font-black text-red-600 tabular-nums">${{ number_format($voucher->amount) }}</div>
                <div class="text-[0.6rem] font-bold text-slate-500 mt-1">{{ $voucher->paid_to_name }}</div>
                <div class="text-[0.55rem] text-slate-400 mt-0.5">{{ ucfirst($voucher->category) }}</div>
            </div>
            @empty
            <div class="col-span-4 py-8 text-center text-slate-300 text-[0.65rem] font-black uppercase tracking-widest">No vouchers yet</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
