@extends('admin.layout')

@section('title', 'System Telemetry (V2 Test)')

@section('content')

    <div class="max-w-[1600px] mx-auto px-4 py-6 space-y-8">

        <!-- Main Analytics View -->
        <div class="grid grid-cols-12 gap-8">

            <!-- Left: High-End Telemetry Section -->
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <div class="glass-card p-10 relative overflow-hidden bg-white">
                    <div class="relative z-10 flex flex-col items-center">
                        <!-- Vehicle Title -->
                        <div class="w-full flex items-baseline gap-4 mb-4">
                            <h2 class="text-2xl font-black text-slate-900 tracking-tighter">Tesla Model X</h2>
                            <span class="text-xs font-bold text-slate-300 tracking-widest uppercase">MPARGFU0945KNGF</span>
                        </div>

                        <!-- Top 3 Gauges -->
                        <div class="flex items-center gap-24 mb-12">
                            <div class="text-center relative">
                                <div class="stat-gauge mx-auto" style="--percent: 32;">
                                    <svg viewBox="0 0 100 100" class="w-24 h-24">
                                        <circle cx="50" cy="50" r="44" stroke="#f1f5f9" stroke-width="2" fill="none">
                                        </circle>
                                        <circle cx="50" cy="50" r="44" stroke="#ff4605" stroke-width="4" fill="none"
                                            class="progress" stroke-dasharray="276"
                                            style="stroke-dashoffset: calc(276 - (276 * 32) / 100);"></circle>
                                    </svg>
                                    <div class="stat-gauge-content">
                                        <i data-lucide="droplet" class="w-4 h-4 text-slate-400 mb-1"></i>
                                        <div class="text-lg font-black text-slate-900 leading-none">32%</div>
                                        <div class="text-[0.45rem] font-bold text-slate-400 uppercase mt-1">Fuel</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center relative">
                                <div class="stat-gauge mx-auto" style="--percent: 65;">
                                    <svg viewBox="0 0 100 100" class="w-28 h-28">
                                        <circle cx="50" cy="50" r="44" stroke="#f1f5f9" stroke-width="2" fill="none">
                                        </circle>
                                        <circle cx="50" cy="50" r="44" stroke="#0052ff" stroke-width="5" fill="none"
                                            class="progress" stroke-dasharray="276"
                                            style="stroke-dashoffset: calc(276 - (276 * 65) / 100);"></circle>
                                    </svg>
                                    <div class="stat-gauge-content">
                                        <i data-lucide="zap" class="w-4 h-4 text-slate-400 mb-1"></i>
                                        <div class="text-xl font-black text-slate-900 leading-none">157<span
                                                class="text-xs">km</span></div>
                                        <div class="text-[0.45rem] font-bold text-slate-400 uppercase mt-1">Range</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center relative">
                                <div class="stat-gauge mx-auto" style="--percent: 8;">
                                    <svg viewBox="0 0 100 100" class="w-24 h-24">
                                        <circle cx="50" cy="50" r="44" stroke="#f1f5f9" stroke-width="2" fill="none">
                                        </circle>
                                        <circle cx="50" cy="50" r="44" stroke="#ff4605" stroke-width="4" fill="none"
                                            class="progress" stroke-dasharray="276"
                                            style="stroke-dashoffset: calc(276 - (276 * 8) / 100);"></circle>
                                    </svg>
                                    <div class="stat-gauge-content">
                                        <i data-lucide="sparkles" class="w-4 h-4 text-slate-400 mb-1"></i>
                                        <div class="text-lg font-black text-[#ff4605] leading-none">8%</div>
                                        <div class="text-[0.45rem] font-bold text-slate-400 uppercase mt-1">Brake Fluid
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Vehicle Image with Pressure Lines -->
                        <div class="relative w-full max-w-3xl px-12 pb-32">
                            <img src="{{ asset('images/cars/home-car.png') }}" class="w-full h-auto object-contain">

                            <!-- Pressure Lines & Text (Custom Positioning) -->
                            <div class="absolute inset-0">
                                <!-- Front Wheel Indicators (Now back on the Left) -->
                                <div class="absolute bottom-[20%] left-[22%] flex flex-col items-center">
                                    <div class="w-[2px] h-12 bg-blue-500/30 rotate-[30deg] origin-top mb-2 relative">
                                        <div
                                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-4 mt-6">
                                        <div class="text-center">
                                            <div class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">
                                                Front Left</div>
                                            <div class="text-sm font-black text-slate-900">25 <span
                                                    class="text-[0.6rem] text-slate-400">psi</span></div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">
                                                Front Right</div>
                                            <div class="text-sm font-black text-slate-900">26 <span
                                                    class="text-[0.6rem] text-slate-400">psi</span></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rear Wheel Indicators (Now back on the Right) -->
                                <div class="absolute bottom-[20%] right-[22%] flex flex-col items-center">
                                    <div class="w-[2px] h-12 bg-blue-500/30 -rotate-[30deg] origin-top mb-2 relative">
                                        <div
                                            class="absolute bottom-0 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full bg-blue-500/20 flex items-center justify-center">
                                            <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-4 mt-6">
                                        <div class="text-center">
                                            <div class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">
                                                Rear Left</div>
                                            <div class="text-sm font-black text-slate-900">25 <span
                                                    class="text-[0.6rem] text-slate-400">psi</span></div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-[0.6rem] font-bold text-pink-500 uppercase tracking-widest">
                                                Rear Right</div>
                                            <div class="text-sm font-black text-pink-600">13 <span
                                                    class="text-[0.6rem] text-pink-400">psi</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="absolute bottom-8 right-10">
                            <button
                                class="bg-[#1d293d] text-white px-6 py-3 rounded-md text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-black/10 flex items-center gap-2">
                                MANAGE INVENTORY <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-span-12 lg:col-span-4 space-y-8">
                <!-- Activity Graph Card -->
                <div class="glass-card p-8 bg-white relative">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Market Activity</h4>
                            <p class="text-[0.6rem] text-slate-400 font-bold uppercase mt-1">Growth: <span
                                    class="text-[#ff4605]">+13.4%</span></p>
                        </div>
                        <button
                            class="text-[0.6rem] font-black text-slate-400 uppercase border-b border-transparent hover:border-slate-400">View
                            All</button>
                    </div>
                    <div class="relative w-full h-[160px]">
                        <canvas id="bidChart"></canvas>
                    </div>
                    <div class="mt-8 pt-8 border-t border-slate-50 flex items-center justify-between">
                        <div>
                            <div class="text-[0.6rem] text-slate-400 font-bold uppercase mb-1">Avg Engagement</div>
                            <div class="text-xl font-black text-slate-900">42 Bids/Asset</div>
                        </div>
                        <div class="w-12 h-12 rounded-md bg-orange-50 flex items-center justify-center text-[#ff4605]">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>

                <!-- Reminders Card -->
                <div class="glass-card p-8 bg-white relative">
                    <div class="flex justify-between items-center mb-8">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Global Status
                            ({{ count($recent_bids) }})</h4>
                        <button class="text-[0.6rem] font-black text-slate-400 uppercase">View Log</button>
                    </div>
                    <div class="space-y-6 max-h-[300px] overflow-y-auto pr-2 sidebar-scroll">
                        @foreach($recent_bids->take(5) as $bid)
                            <div class="flex items-start gap-4">
                                <div
                                    class="mt-1.5 shrink-0 w-1.5 h-1.5 bg-[#ff4605] rounded-full shadow-[0_0_8px_rgba(255,70,5,0.4)]">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-1 truncate">
                                        {{ optional($bid->auction)->car->make ?? 'Auction' }} Update
                                    </div>
                                    <p class="text-xs font-bold text-slate-900 leading-tight">{{ optional($bid->user)->name }}
                                        bid ${{ number_format($bid->amount) }}</p>
                                    <div class="text-[0.55rem] text-slate-400 mt-1 uppercase">
                                        {{ $bid->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button
                        class="w-full mt-8 border-2 border-slate-100 hover:border-[#ff4605] hover:text-[#ff4605] p-3 rounded-md text-[0.65rem] font-black uppercase tracking-widest transition-all">
                        Refresh Log
                    </button>
                </div>
            </div>
        </div>

        <!-- Lower Section (Profile & Tabs) -->
        <div class="glass-card overflow-hidden">
            <!-- Navigation Tabs -->
            <div class="flex px-10 border-b border-slate-100">
                <button
                    class="px-8 py-5 text-[0.7rem] font-black uppercase tracking-widest text-slate-400 tab-active">Dashboard</button>
                <button
                    class="px-8 py-5 text-[0.7rem] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Auctions</button>
                <button
                    class="px-8 py-5 text-[0.7rem] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Bids</button>
                <button
                    class="px-8 py-5 text-[0.7rem] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Users</button>
                <button
                    class="px-8 py-5 text-[0.7rem] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Logs</button>
            </div>

            <!-- Tab Content (User Profile Detail Style) -->
            <div class="p-10 flex flex-col xl:flex-row items-center gap-10 bg-slate-50/20">
                <!-- User Basic Info -->
                <div class="flex items-center gap-6 w-full xl:w-auto xl:min-w-[400px]">
                    <div class="relative shrink-0">
                        <div class="w-24 h-24 rounded-[1.5rem] bg-white border border-slate-200 overflow-hidden shadow-sm">
                            <img src="https://i.pravatar.cc/200?u=current_user" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="absolute -bottom-1 -right-1 bg-emerald-500 text-white p-1 rounded-lg text-[0.5rem] font-bold uppercase px-2 shadow-lg border-2 border-white">
                            Active
                        </div>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight truncate">{{ Auth::user()->name }}</h3>
                        <div class="flex flex-wrap items-center gap-3 mt-2">
                            <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">Group: <span
                                    class="text-slate-900">Administrator</span></span>
                            <span class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">Role: <span
                                    class="text-[#ff4605]">System Root</span></span>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <span
                                class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[0.6rem] font-bold text-slate-600">Enterprise
                                Access</span>
                            <span
                                class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-[0.6rem] font-bold text-slate-600">Sync
                                Master</span>
                        </div>
                    </div>
                </div>

                <!-- System Details Grid -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-2 gap-x-10 gap-y-6 flex-1 w-full xl:border-l xl:border-slate-100 xl:pl-10">
                    <div>
                        <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Session ID
                        </div>
                        <div class="text-xs font-bold text-slate-900 tracking-tight font-mono">{{ Str::random(12) }}</div>
                    </div>
                    <div>
                        <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Login Time
                        </div>
                        <div class="text-xs font-bold text-slate-900 tracking-tight">{{ now()->format('Y.m.d H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Access Level
                        </div>
                        <div class="text-xs font-bold text-slate-900 tracking-tight">System Root</div>
                    </div>
                    <div>
                        <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">IP Protocol
                        </div>
                        <div class="text-xs font-bold text-slate-900 tracking-tight">192.168.1.1</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-row xl:flex-col gap-3 w-full xl:w-auto shrink-0 border-t xl:border-t-0 xl:border-l border-slate-100 pt-6 xl:pt-0 xl:pl-10">
                    <button class="flex-1 btn-action bg-white !text-slate-900 !border-slate-200 border">
                        <i data-lucide="settings" class="w-4 h-4"></i> Profile Settings
                    </button>
                    <button class="flex-1 btn-action bg-[#ff4605] text-white shadow-lg shadow-orange-500/10">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Security Hub
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.initializeAdminCharts === 'function') {
                window.initializeAdminCharts('bidChart', {
                    labels: ['Dec 1', 'Dec 8', 'Dec 15', 'Dec 22', 'Dec 29'],
                    datasets: [{
                        data: [65, 59, 80, 81, 95],
                        borderColor: '#ff4605',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#ff4605',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(255, 70, 5, 0.05)'
                    }]
                });
            }
        });
    </script>
@endsection
