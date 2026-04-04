@extends('admin.layout')

@section('title', __('messages.auction_settings'))
@section('page_title', __('messages.auction_settings'))

@section('content')
<div class="max-w-3xl mx-auto space-y-8 pb-20">

    {{-- Header --}}
    <div class="flex items-center gap-6 pb-8 border-b border-slate-100">
        <div class="w-14 h-14 rounded-2xl bg-[#1d293d] flex items-center justify-center shadow-xl">
            <i data-lucide="gavel" class="w-7 h-7 text-[#ff6900]"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                Auction <span class="text-[#ff6900]">Settings</span>
            </h1>
            <p class="text-[0.65rem] text-slate-400 font-black uppercase tracking-widest italic mt-1">
                {{ __('messages.auction_settings_subtitle') }}
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 text-sm font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.auctions.update') }}" class="space-y-6">
        @csrf

        {{-- ── Anti-Sniping ── --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-500 border border-purple-100 flex items-center justify-center">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">{{ __('messages.anti_snipe_title') }}</h2>
                        <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">{{ __('messages.anti_snipe_subtitle') }}</p>
                    </div>
                </div>
                {{-- Toggle --}}
                <label class="relative inline-flex items-center cursor-pointer gap-3">
                    <span class="text-[0.65rem] font-black text-slate-400 uppercase" id="snipe-toggle-label">
                        {{ $settings['anti_snipe_enabled'] == '1' ? __('messages.anti_snipe_enabled') : __('messages.anti_snipe_disabled') }}
                    </span>
                    <input type="hidden" name="anti_snipe_enabled" value="0">
                    <div class="relative">
                        <input type="checkbox" name="anti_snipe_enabled" id="anti-snipe-toggle" class="sr-only peer"
                               onchange="document.getElementById('snipe-toggle-label').textContent = this.checked ? '{{ __('messages.anti_snipe_enabled') }}' : '{{ __('messages.anti_snipe_disabled') }}'"
                               {{ $settings['anti_snipe_enabled'] == '1' ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-['']
                            after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-500"></div>
                    </div>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-8 pt-2">
                {{-- Trigger Threshold --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-[0.7rem] font-black text-slate-600 tracking-wide uppercase">{{ __('messages.anti_snipe_trigger_at') }}</label>
                        <span id="threshold-val" class="text-base font-black text-purple-600">{{ $settings['time_extension_threshold'] }}s</span>
                    </div>
                    <input type="range" name="time_extension_threshold"
                           min="5" max="120" step="5"
                           value="{{ $settings['time_extension_threshold'] }}"
                           oninput="
                               document.getElementById('threshold-val').textContent = this.value + 's';
                               document.getElementById('threshold-preview').textContent = this.value;
                           "
                           class="w-full accent-purple-500 h-2">
                    <div class="flex justify-between text-[0.6rem] text-slate-300 font-bold">
                        <span>5s</span><span>60s</span><span>120s</span>
                    </div>
                </div>

                {{-- Extension Duration --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-[0.7rem] font-black text-slate-600 tracking-wide uppercase">{{ __('messages.anti_snipe_add_duration') }}</label>
                        <span id="extension-val" class="text-base font-black text-emerald-600">{{ $settings['time_extension_seconds'] }}s</span>
                    </div>
                    <input type="range" name="time_extension_seconds"
                           min="5" max="120" step="5"
                           value="{{ $settings['time_extension_seconds'] }}"
                           oninput="
                               document.getElementById('extension-val').textContent = this.value + 's';
                               document.getElementById('extension-preview').textContent = this.value;
                           "
                           class="w-full accent-emerald-500 h-2">
                    <div class="flex justify-between text-[0.6rem] text-slate-300 font-bold">
                        <span>5s</span><span>60s</span><span>120s</span>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50/50 rounded-2xl p-4 flex items-center gap-3 text-[0.7rem] text-slate-500 font-bold border border-purple-100">
                <i data-lucide="info" class="w-4 h-4 text-purple-400 shrink-0"></i>
                If a bid is placed with less than
                <span class="text-purple-700 font-black mx-1" id="threshold-preview">{{ $settings['time_extension_threshold'] }}</span>s remaining,
                <span class="text-emerald-700 font-black mx-1" id="extension-preview">{{ $settings['time_extension_seconds'] }}</span>s will be added automatically.
            </div>
        </div>

        {{-- ── Bidding Settings ── --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-6">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#ff6900] border border-orange-100 flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">{{ __('messages.bid_settings_title') }}</h2>
                    <p class="text-[0.6rem] text-slate-400 font-bold mt-0.5">{{ __('messages.bid_settings_subtitle') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">{{ __('messages.default_bid_increment') }}</label>
                    <input type="number" name="default_bid_increment"
                           value="{{ $settings['default_bid_increment'] }}"
                           min="1" step="50"
                           class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 font-black text-lg text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[0.65rem] font-black uppercase text-slate-400 tracking-widest">{{ __('messages.default_deposit') }}</label>
                    <input type="number" name="default_deposit"
                           value="{{ $settings['default_deposit'] }}"
                           min="0" step="50"
                           class="w-full h-14 bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 font-black text-lg text-slate-700 outline-none focus:border-[#ff6900] transition-all">
                </div>
            </div>
        </div>

        {{-- ── General Settings ── --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-xl border border-slate-50 space-y-5">
            <div class="flex items-center gap-4 mb-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 border border-blue-100 flex items-center justify-center">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-[#031629] uppercase tracking-widest">{{ __('messages.general_settings_title') }}</h2>
                </div>
            </div>

            {{-- Auto-Close --}}
            <div class="flex items-center justify-between py-4 border-b border-slate-50">
                <div>
                    <div class="text-sm font-black text-[#031629]">{{ __('messages.auction_auto_close_label') }}</div>
                    <div class="text-[0.6rem] text-slate-400 font-bold mt-0.5">{{ __('messages.auction_auto_close_desc') }}</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="auction_auto_close" value="0">
                    <div class="relative">
                        <input type="checkbox" name="auction_auto_close" class="sr-only peer"
                               {{ $settings['auction_auto_close'] == '1' ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-['']
                            after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500"></div>
                    </div>
                </label>
            </div>

            {{-- Bid Feed Admin Only --}}
            <div class="flex items-center justify-between py-4">
                <div>
                    <div class="text-sm font-black text-[#031629]">{{ __('messages.bid_feed_admin_only_label') }}</div>
                    <div class="text-[0.6rem] text-slate-400 font-bold mt-0.5">{{ __('messages.bid_feed_admin_only_desc') }}</div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="global_bid_feed_admin_only" value="0">
                    <div class="relative">
                        <input type="checkbox" name="global_bid_feed_admin_only" class="sr-only peer"
                               {{ $settings['global_bid_feed_admin_only'] == '1' ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 rounded-full peer
                            peer-checked:after:translate-x-full peer-checked:after:border-white after:content-['']
                            after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-500"></div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full h-16 bg-[#1d293d] text-white rounded-2xl font-black shadow-2xl hover:bg-black active:scale-95 transition-all flex items-center justify-center gap-4 text-[0.75rem] uppercase tracking-[0.2em]">
            <i data-lucide="save" class="w-5 h-5 text-[#ff6900]"></i>
            {{ __('messages.save_settings') }}
        </button>
    </form>
</div>
@endsection
