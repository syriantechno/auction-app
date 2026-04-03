@extends('admin.layout')
@section('title', 'Cash & Bank Accounts')
@section('content')
<div class="px-1 space-y-6 animate-in fade-in duration-500">

    <x-admin-header icon="wallet" title="Cash & Bank Accounts"
        subtitle="Financial accounts & current balances">
        <x-slot name="actions">
            <button onclick="document.getElementById('newAccountModal').classList.remove('hidden')"
                class="px-5 h-10 bg-[#1d293d] text-white rounded-lg font-black text-[0.65rem] uppercase tracking-widest flex items-center gap-2 hover:bg-[#111827] transition-all shadow-lg">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Account
            </button>
        </x-slot>
    </x-admin-header>

    @if(session('success'))
    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-lg border border-emerald-100 text-sm font-bold flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Account Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($accounts as $account)
        @php
            $isBank = $account->type === 'bank';
            $balance = (float) $account->current_balance;
        @endphp
        <div class="relative overflow-hidden rounded-2xl shadow-xl {{ $isBank ? 'bg-gradient-to-br from-[#1d293d] to-[#0a1628]' : 'bg-gradient-to-br from-slate-700 to-slate-900' }}">
            {{-- Decorative circle --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>

            <div class="relative p-6">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                        <i data-lucide="{{ $isBank ? 'building-2' : 'wallet' }}" class="w-6 h-6 text-[#ff6900]"></i>
                    </div>
                    <div class="text-right">
                        <span class="text-[0.5rem] text-white/30 font-black uppercase tracking-widest block">TYPE</span>
                        <span class="text-[0.65rem] text-white/50 font-black uppercase tracking-widest">{{ strtoupper($account->type) }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-[0.6rem] text-white/40 font-black uppercase tracking-widest mb-1">Current Balance</div>
                    <div class="text-3xl font-black text-white tabular-nums tracking-tighter {{ $balance < 0 ? 'text-red-400' : '' }}">
                        ${{ number_format($balance, 2) }}
                    </div>
                </div>

                <div class="border-t border-white/10 pt-4 space-y-2">
                    <div class="flex justify-between text-[0.6rem]">
                        <span class="text-white/40 font-bold uppercase tracking-widest">Account Name</span>
                        <span class="text-white/70 font-black">{{ $account->name }}</span>
                    </div>
                    @if($account->bank_name)
                    <div class="flex justify-between text-[0.6rem]">
                        <span class="text-white/40 font-bold uppercase tracking-widest">Bank</span>
                        <span class="text-white/70 font-black">{{ $account->bank_name }}</span>
                    </div>
                    @endif
                    @if($account->account_number)
                    <div class="flex justify-between text-[0.6rem]">
                        <span class="text-white/40 font-bold uppercase tracking-widest">Account #</span>
                        <span class="text-white/70 font-mono font-black">{{ $account->account_number }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-[0.6rem]">
                        <span class="text-white/40 font-bold uppercase tracking-widest">Opening Balance</span>
                        <span class="text-white/50 font-black tabular-nums">${{ number_format($account->opening_balance, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <div class="flex-1 bg-emerald-500/10 border border-emerald-500/20 rounded-lg px-3 py-2 text-center">
                        <div class="text-[0.5rem] text-emerald-400 font-black uppercase tracking-widest">In</div>
                        <div class="text-sm font-black text-emerald-400 tabular-nums">${{ number_format($account->receipts->sum('amount'), 0) }}</div>
                    </div>
                    <div class="flex-1 bg-red-500/10 border border-red-500/20 rounded-lg px-3 py-2 text-center">
                        <div class="text-[0.5rem] text-red-400 font-black uppercase tracking-widest">Out</div>
                        <div class="text-sm font-black text-red-400 tabular-nums">${{ number_format($account->paymentVouchers->sum('amount'), 0) }}</div>
                    </div>
                    <div class="flex-1 bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-center">
                        <div class="text-[0.5rem] text-white/30 font-black uppercase tracking-widest">TX</div>
                        <div class="text-sm font-black text-white/50">{{ $account->receipts->count() + $account->paymentVouchers->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-16 text-center bg-white rounded-2xl border-2 border-dashed border-slate-100">
            <i data-lucide="wallet" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
            <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No accounts yet</p>
        </div>
        @endforelse
    </div>

</div>

{{-- New Account Modal --}}
<div id="newAccountModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-[#1d293d] px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="wallet" class="w-5 h-5 text-[#ff6900]"></i>
                <h3 class="font-black text-white text-sm">Add Financial Account</h3>
            </div>
            <button onclick="document.getElementById('newAccountModal').classList.add('hidden')" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <form action="{{ route('admin.finance.accounts.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account Name</label>
                    <input type="text" name="name" required placeholder="e.g. Main Cash Box, Savings Account..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-[#ff6900]">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Type</label>
                    <select name="type" id="accountType" onchange="toggleBankFields()"
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-[#ff6900]">
                        <option value="cash">Cash Box</option>
                        <option value="bank">Bank Account</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Opening Balance ($)</label>
                    <input type="number" name="opening_balance" step="0.01" value="0"
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black outline-none focus:border-[#ff6900]">
                </div>
            </div>
            <div id="bankFields" class="hidden grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Bank Name</label>
                    <input type="text" name="bank_name" placeholder="e.g. Emirates NBD..."
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-[#ff6900]">
                </div>
                <div>
                    <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest block mb-1.5">Account Number</label>
                    <input type="text" name="account_number" placeholder="IBAN / Account #"
                        class="w-full h-11 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm outline-none focus:border-[#ff6900]">
                </div>
            </div>
            <textarea name="notes" rows="2" placeholder="Notes (optional)..."
                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-[#ff6900] resize-none"></textarea>
            <button type="submit" class="w-full h-12 bg-[#1d293d] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-widest hover:bg-[#111827] transition-all flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> Create Account
            </button>
        </form>
    </div>
</div>

<script>
function toggleBankFields() {
    const type = document.getElementById('accountType').value;
    const fields = document.getElementById('bankFields');
    if (type === 'bank') {
        fields.classList.remove('hidden');
        fields.classList.add('grid');
    } else {
        fields.classList.add('hidden');
        fields.classList.remove('grid');
    }
}
</script>
@endsection
