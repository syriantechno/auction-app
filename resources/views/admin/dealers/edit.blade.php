@extends('admin.layout')
@section('title', 'Set Limit — ' . $user->name)
@section('page_title', 'Dealer Deposit & Bidding Limit')

@section('content')
<div class="px-2 pb-20" x-data="{
    deposit: {{ $user->security_deposit ?? 0 }},
    limit: {{ $user->bidding_limit ?? 0 }},
    get ratio() {
        if (!this.deposit || !this.limit) return 0;
        return Math.round((this.deposit / this.limit) * 100);
    }
}">
    <div class="max-w-2xl mx-auto space-y-6">

        {{-- Back --}}
        <a href="{{ route('admin.dealers.show', $user) }}"
           class="inline-flex items-center gap-2 text-[0.6rem] font-black text-slate-400 uppercase tracking-widest hover:text-[#ff6900] transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Profile
        </a>

        {{-- Dealer Badge Header --}}
        <div class="bg-gradient-to-r from-[#031629] via-[#1d293d] to-[#031629] rounded-[2rem] p-8 flex items-center gap-6 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: repeating-linear-gradient(45deg, #ff6900 0, #ff6900 1px, transparent 0, transparent 50%); background-size: 12px 12px;"></div>

            <div class="w-16 h-16 rounded-2xl bg-[#ff6900]/20 border border-[#ff6900]/30 flex items-center justify-center flex-shrink-0 relative z-10">
                <span class="text-2xl font-black text-[#ff6900] uppercase italic">{{ strtoupper(substr($user->name,0,2)) }}</span>
            </div>

            <div class="relative z-10 flex-1">
                <h1 class="text-xl font-black text-white uppercase italic tracking-tight">{{ $user->name }}</h1>
                <p class="text-[0.6rem] font-black text-white/40 uppercase tracking-widest mt-1">{{ $user->email }}</p>
            </div>

            <div class="relative z-10 text-right">
                <div class="text-[0.5rem] font-black text-white/40 uppercase tracking-widest">Current Limit</div>
                <div class="text-2xl font-black text-[#ff6900] tabular-nums">${{ number_format($user->bidding_limit ?? 0) }}</div>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-4 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 flex-shrink-0"></i>
            <span class="text-[0.7rem] font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.dealers.update', $user) }}" method="POST" class="bg-white border border-slate-100 rounded-[2rem] overflow-hidden">
            @csrf
            @method('PUT')

            {{-- Section Title --}}
            <div class="px-8 pt-8 pb-6 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#ff6900]/10 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-[#ff6900]"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-[#031629] uppercase italic tracking-tight">Set Deposit & Bidding Limit</h2>
                        <p class="text-[0.55rem] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Define how much this dealer can bid based on their deposit</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8 space-y-8">

                {{-- Two fields side by side --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Security Deposit --}}
                    <div>
                        <label class="flex items-center gap-2 text-[0.6rem] font-black text-slate-500 uppercase tracking-widest mb-3">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-blue-500"></i>
                            Security Deposit Paid
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 font-black text-sm">$</span>
                            <input type="number" name="security_deposit" step="1" min="0"
                                   x-model.number="deposit"
                                   value="{{ old('security_deposit', $user->security_deposit ?? '') }}"
                                   class="w-full pl-8 pr-4 py-4 bg-blue-50/50 border-2 border-blue-100 rounded-xl font-black text-[#031629] text-lg focus:outline-none focus:ring-0 focus:border-blue-400 transition-all"
                                   placeholder="0">
                        </div>
                        <p class="text-[0.55rem] text-slate-400 font-bold mt-2 uppercase tracking-widest">Amount received as guarantee</p>
                        @error('security_deposit')
                            <p class="text-red-500 text-[0.6rem] font-black mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Bidding Limit --}}
                    <div>
                        <label class="flex items-center gap-2 text-[0.6rem] font-black text-slate-500 uppercase tracking-widest mb-3">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                            Max Bidding Limit
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#ff6900] font-black text-sm">$</span>
                            <input type="number" name="bidding_limit" step="1" min="0"
                                   x-model.number="limit"
                                   value="{{ old('bidding_limit', $user->bidding_limit ?? '') }}"
                                   class="w-full pl-8 pr-4 py-4 bg-orange-50/50 border-2 border-orange-100 rounded-xl font-black text-[#031629] text-lg focus:outline-none focus:ring-0 focus:border-[#ff6900] transition-all"
                                   placeholder="0">
                        </div>
                        <p class="text-[0.55rem] text-slate-400 font-bold mt-2 uppercase tracking-widest">Max auction value allowed to bid on</p>
                        @error('bidding_limit')
                            <p class="text-red-500 text-[0.6rem] font-black mt-1 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Live ratio bar --}}
                <div x-show="deposit > 0 && limit > 0" class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Coverage Ratio</span>
                        <span class="text-[0.75rem] font-black text-[#031629]" x-text="ratio + '%'"></span>
                    </div>
                    <div class="h-3 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-[#ff6900] rounded-full transition-all duration-500"
                             :style="'width: ' + Math.min(ratio, 100) + '%'"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-[0.55rem] font-black text-blue-500 uppercase tracking-widest" x-text="'Deposit: $' + deposit.toLocaleString()"></span>
                        <span class="text-[0.55rem] font-black text-[#ff6900] uppercase tracking-widest" x-text="'Limit: $' + limit.toLocaleString()"></span>
                    </div>
                </div>

                {{-- Info box --}}
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex items-start gap-3">
                    <i data-lucide="zap" class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-[0.6rem] font-black text-amber-700 uppercase tracking-widest mb-1">How it works</p>
                        <p class="text-[0.7rem] text-amber-600 leading-relaxed">
                            If the dealer paid <strong class="font-black">$25,000</strong> as deposit,
                            you can allow them to bid on auctions up to <strong class="font-black">$100,000</strong>.
                            The system uses these values to control bidding access.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center gap-3">
                <button type="submit"
                        class="flex-1 h-14 bg-[#1d293d] hover:bg-[#ff6900] rounded-xl flex items-center justify-center gap-2.5 text-white text-[0.65rem] font-black uppercase tracking-widest shadow-lg transition-all hover:scale-[1.01]">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Confirm Deposit & Set Limit
                </button>
                <a href="{{ route('admin.dealers.show', $user) }}"
                   class="h-14 px-6 bg-white hover:bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 text-[0.65rem] font-black uppercase tracking-widest transition-all">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
