@extends('admin.layout')

@section('title', 'Inspection Report · ' . ($report->car?->make ?? 'Asset') . ' ' . ($report->car?->model ?? ''))

@section('content')
<div class="pb-24 space-y-5">

    {{-- ══════════════════════════
         HEADER
    ══════════════════════════ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">

        {{-- Left: Brand icon + Title --}}
        <div class="flex items-center gap-5">
            {{-- Brand Logo Box --}}
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] overflow-hidden shadow-xl p-3 flex items-center justify-center transform rotate-3">
                    @php
                        $makeSlug = \Illuminate\Support\Str::slug(strtolower($report->car?->make ?? 'generic'));
                        $logoPath = "images/brands/{$makeSlug}.svg";
                        if(!file_exists(public_path($logoPath))) $logoPath = "images/brands/{$makeSlug}.png";
                    @endphp
                    @if(file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}" class="w-full h-full object-contain filter brightness-0 invert opacity-90">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v5"/><circle cx="16" cy="17" r="3"/><circle cx="7" cy="17" r="3"/></svg>
                    @endif
                </div>
                {{-- Score dot --}}
                @php $scoreColor = $report->overall_score >= 80 ? 'bg-emerald-500' : ($report->overall_score >= 60 ? 'bg-amber-400' : 'bg-red-500'); @endphp
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg {{ $scoreColor }} border-[3px] border-[#f8fafc] animate-pulse"></div>
            </div>

            {{-- Title --}}
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    {{ $report->car?->year ?? '' }} {{ $report->car?->make ?? 'Unknown' }}
                    <span class="text-[#ff6900]">{{ $report->car?->model ?? 'Asset' }}</span>
                </h1>
                <div class="flex items-center gap-3 mt-2.5">
                    <span class="text-[0.58rem] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                        Technical Audit Report
                    </span>
                    <span class="text-slate-200">·</span>
                    <span class="text-[0.58rem] font-mono font-bold text-slate-400 uppercase tracking-tighter">
                        #RP-{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    @if($report->car?->vin)
                    <span class="text-slate-200">·</span>
                    <span class="text-[0.58rem] font-mono font-bold text-[#ff6900] uppercase">{{ $report->car->vin }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#1d293d] hover:border-slate-300 flex items-center gap-2 text-[0.58rem] font-black uppercase tracking-widest transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print
            </button>
            <a href="{{ route('admin.inspections.index') }}"
               class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#1d293d] hover:border-slate-300 flex items-center gap-2 text-[0.58rem] font-black uppercase tracking-widest transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back
            </a>
        </div>
    </div>

    {{-- ══════════════════════════
         MAIN GRID
    ══════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ══ LEFT: Main Analysis (2 cols) ══ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Score Banner --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="text-[0.6rem] font-black uppercase tracking-[0.18em] text-slate-400">Overall Rating</div>
                    <div class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest">Engine · Body · Transmission · Interior · Tires</div>
                </div>
                <div class="flex items-center gap-6 p-5 bg-[#f0f2f5]">

                    {{-- Score Ring --}}
                    <div class="relative w-20 h-20 flex-shrink-0">
                        @php
                            $scoreHex = $report->overall_score >= 80 ? '#10b981' : ($report->overall_score >= 60 ? '#f59e0b' : '#ef4444');
                            $circumference = 2 * M_PI * 32;
                            $offset = $circumference * (1 - $report->overall_score / 100);
                        @endphp
                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="32" fill="transparent" stroke="#e2e8f0" stroke-width="7"/>
                            <circle cx="40" cy="40" r="32" fill="transparent" stroke="{{ $scoreHex }}" stroke-width="7"
                                    stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
                                    stroke-linecap="round"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-xl font-black text-[#1d293d] leading-none tabular-nums">{{ $report->overall_score }}</span>
                            <span class="text-[0.42rem] font-black text-slate-400 uppercase tracking-widest">Score</span>
                        </div>
                    </div>

                    {{-- Quick stats --}}
                    <div class="grid grid-cols-5 gap-3 flex-1">
                        @foreach([
                            ['Engine', $report->engine_score],
                            ['Body', $report->paint_score],
                            ['Trans.', $report->transmission_score],
                            ['Interior', $report->interior_score],
                            ['Tires', $report->tires_score],
                        ] as [$label, $score])
                        <div class="bg-white rounded-lg border border-slate-200 px-2 py-2 text-center">
                            <div class="text-[0.48rem] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</div>
                            <div class="text-sm font-black text-[#1d293d]">{{ $score }}<span class="text-[0.5rem] text-slate-400">%</span></div>
                            <div class="mt-1.5 h-1 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $score >= 80 ? 'bg-emerald-500' : ($score >= 60 ? 'bg-amber-400' : 'bg-red-400') }}"
                                     style="width: {{ $score }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Systems Performance --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Systems Performance</div>
                    <div class="ml-auto text-[0.52rem] text-slate-400 font-bold uppercase tracking-widest">Weighted Quality Metrics</div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach([
                        ['Propulsion & Power', $report->engine_score, $report->engine_notes, '#ff6900'],
                        ['Structural Integrity', $report->paint_score, $report->body_notes, '#3b82f6'],
                        ['Transmission Dynamics', $report->transmission_score, $report->transmission_notes, '#8b5cf6'],
                        ['Interior Architecture', $report->interior_score, $report->interior_notes, '#10b981'],
                        ['Contact Dynamics (Tires)', $report->tires_score, $report->tires_notes, '#f59e0b'],
                    ] as [$label, $score, $notes, $color])
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[0.65rem] font-black text-[#1d293d] uppercase tracking-widest">{{ $label }}</span>
                            <span class="text-[0.65rem] font-black tabular-nums" style="color: {{ $color }}">{{ $score }}%</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: {{ $score }}%; background: {{ $color }}"></div>
                        </div>
                        @if($notes)
                        <p class="mt-2 text-[0.62rem] text-slate-500 font-medium italic bg-[#f0f2f5] px-3 py-2 rounded-lg leading-relaxed">
                            {{ $notes }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Component Verification Checklist --}}
            @if(!empty($report->detailed_checklists))
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1d293d" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </div>
                    <div class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Component Verification</div>
                    <div class="ml-auto">
                        @php
                            $passed = collect($report->detailed_checklists)->filter()->count();
                            $total  = count($report->detailed_checklists);
                        @endphp
                        <span class="text-[0.52rem] font-black px-2 py-0.5 rounded-md {{ $passed === $total ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $passed }}/{{ $total }} Passed
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-[#f0f2f5]">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($report->detailed_checklists as $key => $pass)
                        <div class="flex items-center gap-2.5 bg-white border border-slate-200 px-3 py-2.5 rounded-lg">
                            <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0 {{ $pass ? 'bg-emerald-50 border border-emerald-200 text-emerald-600' : 'bg-red-50 border border-red-100 text-red-500' }}">
                                @if($pass)
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                @endif
                            </div>
                            <span class="text-[0.58rem] font-bold text-slate-600 uppercase tracking-tighter leading-tight">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- ══ RIGHT: Sidebar ══ --}}
        <div class="space-y-4 sticky top-6">

            {{-- Expert Verdict --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-3 px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="w-7 h-7 rounded-lg bg-[#1d293d] border border-[#1d293d]/20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                    </div>
                    <div class="text-[0.62rem] font-black text-[#1d293d] uppercase tracking-widest">Executive Verdict</div>
                </div>
                <div class="p-5 bg-[#f0f2f5]">
                    <blockquote class="text-[0.7rem] text-slate-600 italic leading-relaxed bg-white border border-slate-200 rounded-lg px-4 py-3">
                        "{{ $report->expert_summary ?? 'No summary provided by the field officer.' }}"
                    </blockquote>

                    <div class="flex items-center gap-3 mt-4 bg-white border border-slate-200 rounded-lg px-4 py-3">
                        <div class="w-9 h-9 rounded-lg bg-[#1d293d] flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                            {{ strtoupper(substr(optional($report->expert)->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-[0.65rem] font-black text-[#1d293d]">{{ optional($report->expert)->name ?? 'Authorized Agent' }}</div>
                            <div class="text-[0.52rem] font-black text-[#ff6900] uppercase tracking-widest mt-0.5">Inspection Authority</div>
                        </div>
                        <div class="ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Report Meta --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="text-[0.6rem] font-black uppercase tracking-[0.18em] text-slate-400">Report Details</div>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach([
                        ['Report ID', '#RP-' . str_pad($report->id, 6, '0', STR_PAD_LEFT)],
                        ['Car', ($report->car?->year ?? '') . ' ' . ($report->car?->make ?? '') . ' ' . ($report->car?->model ?? '')],
                        ['VIN', $report->car?->vin ?? 'N/A'],
                        ['Inspector', optional($report->expert)->name ?? 'N/A'],
                        ['Date', $report->created_at?->format('d M Y') ?? 'N/A'],
                    ] as [$key, $value])
                    <div class="flex items-center justify-between px-5 py-3">
                        <span class="text-[0.58rem] font-black uppercase tracking-widest text-slate-400">{{ $key }}</span>
                        <span class="text-[0.65rem] font-bold text-[#1d293d]">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Auction Pipeline --}}
            @php $auction = \App\Models\Auction::where('car_id', $report->car_id)->latest()->first(); @endphp
            @if($auction)
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="text-[0.6rem] font-black uppercase tracking-[0.18em] text-slate-400">Auction Pipeline</div>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        @if($auction->status === 'active')
                        <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-sm font-black text-[#1d293d] uppercase italic tracking-tighter">Live Session</span>
                        @elseif($auction->status === 'coming_soon')
                        <div class="w-3 h-3 rounded-full bg-[#ff6900] animate-pulse"></div>
                        <span class="text-sm font-black text-[#1d293d] uppercase italic tracking-tighter">Coming Soon</span>
                        @else
                        <div class="w-3 h-3 rounded-full bg-slate-300"></div>
                        <span class="text-sm font-black text-slate-400 uppercase italic tracking-tighter">{{ strtoupper($auction->status) }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.auctions.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-2.5 bg-[#1d293d] text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 3h18l-2 13H5L3 3z"/><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/></svg>
                        Manage Auction
                    </a>
                </div>
            </div>
            @endif

            {{-- Print CTA --}}
            <button onclick="window.print()"
                    class="w-full py-3 bg-[#ff6900] text-white rounded-xl font-black text-[0.62rem] uppercase tracking-widest hover:bg-[#e55e00] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Export / Print Report
            </button>

        </div>
    </div>
</div>
@endsection
