@extends('admin.layout')

@section('title', 'Edit Auction')
@section('page_title', 'Edit Auction')

@section('content')
<div class="px-1 max-w-2xl mx-auto space-y-8 pb-20">

    {{-- Header --}}
    <div class="flex items-center gap-6 pb-8 border-b border-slate-100">
        <div class="w-14 h-14 rounded-2xl bg-[#1d293d] flex items-center justify-center shadow-xl">
            <i data-lucide="gavel" class="w-7 h-7 text-[#ff6900]"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                Edit Auction <span class="text-[#ff6900]">#{{ $auction->id }}</span>
            </h1>
            <p class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic mt-2">
                {{ optional($auction->car)->make }} {{ optional($auction->car)->model }} — {{ optional($auction->car)->year }}
            </p>
        </div>
        <a href="{{ route('admin.auctions.index') }}" class="ml-auto px-6 py-3 bg-white border border-slate-100 text-slate-400 hover:text-slate-900 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all shadow-sm">
            ← Back
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.auctions.update', $auction) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Status --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-5">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-[#ff6900] border border-orange-100 flex items-center justify-center">
                    <i data-lucide="radio" class="w-4 h-4"></i>
                </div>
                <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Broadcast Status</h2>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @foreach(['coming_soon' => ['🕓', 'Coming Soon', 'blue'], 'active' => ['🔴', 'Live Now', 'emerald'], 'paused' => ['⏸️', 'Paused', 'yellow'], 'closed' => ['🔒', 'Closed', 'red']] as $val => [$icon, $label, $color])
                <label class="relative flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all
                    {{ $auction->status === $val ? 'border-[#ff6900] bg-orange-50/50' : 'border-slate-100 hover:border-slate-200 bg-slate-50/50' }}">
                    <input type="radio" name="status" value="{{ $val }}" class="sr-only" {{ $auction->status === $val ? 'checked' : '' }}
                        onchange="this.closest('form').querySelectorAll('label').forEach(l=>l.classList.remove('border-[#ff6900]','bg-orange-50/50')); this.closest('label').classList.add('border-[#ff6900]','bg-orange-50/50')">
                    <span class="text-xl">{{ $icon }}</span>
                    <span class="font-black text-sm text-[#031629] uppercase tracking-tight italic">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Schedule --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-5">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 border border-blue-100 flex items-center justify-center">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
                <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest italic">Schedule Window</h2>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest ml-1">Start At</label>
                    <input type="datetime-local" name="start_at"
                           value="{{ $auction->start_at ? $auction->start_at->format('Y-m-d\TH:i') : '' }}"
                           class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest ml-1">End At</label>
                    <input type="datetime-local" name="end_at"
                           value="{{ $auction->end_at ? $auction->end_at->format('Y-m-d\TH:i') : '' }}"
                           class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 font-bold text-sm text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                </div>
            </div>

            {{-- Quick Go Live --}}
            <div class="pt-4 border-t border-slate-50">
                <p class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic mb-3">Quick Go Live</p>
                <div class="flex gap-2 flex-wrap">
                    @foreach([10 => '10 min', 20 => '20 min', 30 => '30 min', 60 => '1 hour', 120 => '2 hours'] as $mins => $label)
                    <button type="button"
                        onclick="setLive({{ $mins }})"
                        class="px-4 py-2 bg-slate-50 hover:bg-[#1d293d] hover:text-white text-slate-500 border border-slate-100 rounded-xl text-[0.65rem] font-black uppercase tracking-widest transition-all">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- Anti-Snipe Global Settings Note --}}
        <div class="bg-purple-50/60 border border-purple-100 rounded-2xl p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-[0.7rem] text-purple-700 font-bold">
                <i data-lucide="shield-alert" class="w-4 h-4 text-purple-500 shrink-0"></i>
                {{ __('messages.anti_snipe_global_note') }}
            </div>
            <a href="{{ route('admin.settings.auctions') }}"
               class="px-4 py-2 bg-purple-500 text-white rounded-xl text-[0.65rem] font-black uppercase tracking-widest hover:bg-purple-600 transition-all shrink-0">
                {{ __('messages.global_settings_link') }}
            </a>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full h-16 bg-[#1d293d] text-white rounded-2xl font-black shadow-2xl hover:bg-black active:scale-95 transition-all flex items-center justify-center gap-4 text-[0.7rem] uppercase tracking-[0.2em]">
            <i data-lucide="save" class="w-5 h-5 text-[#ff6900]"></i> Save Changes
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function setLive(minutes) {
        const now = new Date();
        const end = new Date(now.getTime() + minutes * 60000);
        const fmt = d => d.toISOString().slice(0, 16);
        document.querySelector('[name="start_at"]').value = fmt(now);
        document.querySelector('[name="end_at"]').value = fmt(end);
        // Auto-select Active
        document.querySelectorAll('[name="status"]').forEach(r => {
            r.checked = r.value === 'active';
            const lbl = r.closest('label');
            if (r.value === 'active') {
                lbl.classList.add('border-[#ff6900]', 'bg-orange-50/50');
                lbl.classList.remove('border-slate-100');
            } else {
                lbl.classList.remove('border-[#ff6900]', 'bg-orange-50/50');
                lbl.classList.add('border-slate-100');
            }
        });
    }
</script>
@endpush
