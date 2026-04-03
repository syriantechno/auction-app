@extends('admin.layout')

@section('title', 'Technical Report - ' . ($report->car->make ?? 'Asset'))
@section('page_title', 'Report Analysis')

@section('content')
<div class="px-2 space-y-8 pb-24">
    <!-- Header: Identity & Status -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-[#ff6900]/5 rounded-full blur-[100px]"></div>
        
        <div class="flex items-center gap-8 relative z-10">
            <!-- Brand Centric Identity -->
            <div class="relative shrink-0">
                <div class="w-24 h-24 rounded-[2rem] bg-[#1d293d] overflow-hidden shadow-2xl p-4 flex items-center justify-center transform -rotate-3 border border-white/10">
                    @php
                        $makeSlug = \Illuminate\Support\Str::slug(strtolower($report->car->make ?? 'generic'));
                        $logoPath = "images/brands/{$makeSlug}.svg";
                        if(!file_exists(public_path($logoPath))) {
                            $logoPath = "images/brands/{$makeSlug}.png";
                        }
                    @endphp
                    @if(file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}" class="w-full h-full object-contain filter brightness-0 invert opacity-90 drop-shadow-lg">
                    @else
                        <i data-lucide="car-front" class="w-10 h-10 text-white opacity-40"></i>
                    @endif
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 rounded-full bg-emerald-500 border-4 border-white flex items-center justify-center shadow-lg">
                    <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
                </div>
            </div>

            <div>
                <span class="text-[0.65rem] font-bold text-[#ff6900] uppercase tracking-[0.3em] italic mb-2 block">Technical Audit Artifact</span>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    {{ $report->car->year ?? '' }} {{ $report->car->make ?? 'Unknown' }} <span class="text-[#ff6900]">{{ $report->car->model ?? 'Asset' }}</span>
                </h1>
                <div class="flex items-center gap-4 mt-5">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-md border border-slate-100">
                        <i data-lucide="fingerprint" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span class="text-[0.7rem] font-mono font-bold text-slate-500 uppercase tracking-tighter leading-none">{{ $report->car->vin ?? 'UNSPECIFIED' }}</span>
                    </div>
                    <span class="text-slate-200">|</span>
                    <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest italic opacity-60">ID: #RP-{{ str_pad($report->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 relative z-10">
            <div class="w-28 h-28 rounded-full border-8 border-slate-50 flex flex-col items-center justify-center p-2 text-center shadow-inner relative">
                @php
                    $scoreColor = $report->overall_score >= 80 ? '#10b981' : ($report->overall_score >= 60 ? '#f59e0b' : '#ef4444');
                @endphp
                <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                    <circle cx="56" cy="56" r="50" fill="transparent" stroke="#f1f5f9" stroke-width="8"/>
                    <circle cx="56" cy="56" r="50" fill="transparent" stroke="{{ $scoreColor }}" stroke-width="8" stroke-dasharray="314.15" stroke-dashoffset="{{ 314.15 * (1 - $report->overall_score / 100) }}" stroke-linecap="round"/>
                </svg>
                <span class="text-3xl font-black text-[#031629] tabular-nums leading-none">{{ $report->overall_score }}</span>
                <span class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest mt-1">Audit Score</span>
            </div>
            
            <div class="flex flex-col gap-2">
                 <button onclick="window.print()" class="h-12 w-12 rounded-md bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#ff6900] hover:bg-white transition-all">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                 </button>
                 <a href="{{ route('admin.inspections.index') }}" class="h-12 w-12 rounded-md bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#031629] hover:bg-white transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                 </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Analysis -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Systems Performance Matrix -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-50">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center border border-orange-100 shadow-sm">
                        <i data-lucide="activity" class="w-6 h-6 text-[#ff6900]"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-[#031629] uppercase italic tracking-tight">Systems Performance</h2>
                        <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Weighted Quality Metrics</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    @foreach([
                        ['label' => 'Propulsion & Power', 'score' => $report->engine_score, 'icon' => 'zap', 'notes' => $report->engine_notes],
                        ['label' => 'Structural Integrity', 'score' => $report->paint_score, 'icon' => 'maximize', 'notes' => $report->body_notes],
                        ['label' => 'Transmission Dynamics', 'score' => $report->transmission_score, 'icon' => 'cog', 'notes' => $report->transmission_notes],
                        ['label' => 'Interior Architecture', 'score' => $report->interior_score, 'icon' => 'armchair', 'notes' => $report->interior_notes],
                        ['label' => 'Contact Dynamics (Tires)', 'score' => $report->tires_score, 'icon' => 'circle-dot', 'notes' => $report->tires_notes],
                    ] as $item)
                        <div class="space-y-4 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 transition-colors group-hover:text-[#ff6900] group-hover:bg-white shadow-sm">
                                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[0.7rem] font-black text-[#031629] uppercase tracking-widest italic">{{ $item['label'] }}</span>
                                </div>
                                <span class="text-sm font-black italic tracking-tighter text-[#031629]">{{ $item['score'] }}%</span>
                            </div>
                            <div class="relative h-1.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                <div class="absolute inset-y-0 left-0 bg-[#ff6900] transition-all duration-1000 shadow-[0_0_12px_rgba(255,70,5,0.4)]" style="width: {{ $item['score'] }}%"></div>
                            </div>
                            @if($item['notes'])
                                <p class="text-[0.75rem] font-medium text-slate-400 italic bg-slate-50 p-3 rounded-md border border-slate-100/50 leading-relaxed">{{ $item['notes'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Detailed Checklists if any -->
            @if(!empty($report->detailed_checklists))
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
                <h2 class="text-xl font-black text-[#031629] mb-8 uppercase italic tracking-tight flex items-center gap-4">
                    <i data-lucide="list-checks" class="w-6 h-6 text-[#ff6900]"></i>
                    Component Verification
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($report->detailed_checklists as $key => $passed)
                        <div class="flex items-center gap-3 bg-slate-50 px-4 py-3 rounded-lg border border-slate-100 shadow-sm">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center {{ $passed ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-red-500 text-white shadow-lg shadow-red-500/20' }}">
                                <i data-lucide="{{ $passed ? 'check' : 'x' }}" class="w-3 h-3"></i>
                            </div>
                            <span class="text-[0.65rem] font-bold text-slate-600 uppercase tracking-tighter leading-none">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions & Summary -->
        <div class="space-y-8">
            <!-- Expert Authority Block -->
            <div class="bg-[#1d293d] p-10 rounded-[3rem] shadow-2xl shadow-[#031629]/20 relative overflow-hidden text-white">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-[#ff6900]/10 rounded-full blur-[60px]"></div>
                
                <h2 class="text-xl font-black uppercase italic tracking-tight mb-8 flex items-center gap-3">
                    <i data-lucide="award" class="w-6 h-6 text-[#ff6900]"></i>
                    Executive Verdict
                </h2>

                <div class="space-y-6 relative z-10">
                    <div class="bg-white/5 p-6 rounded-lg border border-white/10 italic text-[0.85rem] leading-[1.8] text-white/90">
                        "{{ $report->expert_summary ?? 'No summary provided by the field officer.' }}"
                    </div>

                    <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                        <div class="w-14 h-14 rounded-lg bg-white/10 flex items-center justify-center text-white/40 border border-white/5 shadow-inner">
                            <i data-lucide="user" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <div class="text-[0.55rem] font-black text-white/30 uppercase tracking-[0.2em] mb-1">Authenticated By</div>
                            <div class="font-black text-white italic tracking-tight">{{ optional($report->expert)->name ?? 'Authorized Agent' }}</div>
                            <div class="text-[0.6rem] font-bold text-[#ff6900] uppercase tracking-widest mt-1 opacity-80">Inspection Authority</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auction Status Link -->
            @php
                $auction = \App\Models\Auction::where('car_id', $report->car_id)->latest()->first();
            @endphp
            @if($auction)
            <div class="bg-[#f8fafc] p-8 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-900/5 group">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-[0.65rem] font-black text-slate-400 uppercase tracking-widest italic opacity-80 leading-none">Auction Pipeline Status</span>
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-lg border border-slate-50 text-[#ff6900]">
                        <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 mb-8">
                    @if($auction->status === 'coming_soon')
                        <div class="w-4 h-4 rounded-full bg-[#ff6900] animate-pulse border-4 border-orange-100"></div>
                        <span class="text-xl font-black text-[#031629] uppercase italic tracking-tighter">Coming Soon</span>
                    @elseif($auction->status === 'active')
                        <div class="w-4 h-4 rounded-full bg-emerald-500 animate-pulse border-4 border-emerald-100"></div>
                        <span class="text-xl font-black text-[#031629] uppercase italic tracking-tighter">Live Session</span>
                    @else
                        <div class="w-4 h-4 rounded-full bg-slate-300 border-4 border-slate-100"></div>
                        <span class="text-xl font-black text-[#031629] uppercase italic tracking-tighter">{{ strtoupper($auction->status) }}</span>
                    @endif
                </div>

                <a href="{{ route('admin.auctions.index') }}" class="block w-full h-14 bg-white hover:bg-[#ff6900] hover:text-white border border-slate-200 rounded-lg flex items-center justify-center gap-3 text-[0.65rem] font-black text-[#031629] uppercase tracking-widest transition-all shadow-sm">
                    Manage Clearance
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

