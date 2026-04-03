@extends('admin.layout')

@section('title', 'Enterprise Overview')

@section('head')
<style>
    /* Global Lean Style Overrides */
    main.bg-\[\#fcfdfe\], main {
        background-color: #f8fafc !important;
    }
    
    .enterprise-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }
    
    .enterprise-card:hover {
        border-color: #ff4605 !important;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .dark-panel {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        border-radius: 1rem !important;
    }
</style>
@endsection

@section('content')
    <div class="space-y-8 animate-in fade-in duration-500">
        @if(session('catalog_success'))
            <div class="enterprise-card px-5 py-4 bg-emerald-50 border-emerald-100 text-emerald-600 font-medium text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('catalog_success') }}</span>
            </div>
        @endif

        <!-- Dashboard Header: Normal Weight -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-medium text-slate-800 tracking-tight italic">System Dashboard</h2>
                <p class="text-[0.65rem] text-slate-400 font-medium tracking-[0.2em] uppercase mt-1">Global Monitoring Hub</p>
            </div>
            <div class="flex gap-4">
                <div class="enterprise-card px-4 py-2 flex items-center gap-3 bg-white">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[0.6rem] font-medium text-slate-500 uppercase tracking-widest">Server Stable</span>
                </div>
                <button class="bg-slate-800 text-white px-8 py-2.5 rounded-lg text-[0.65rem] font-medium uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg">
                    Global Export
                </button>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <!-- Left: Stat Cards & Weekly Interest -->
            <div class="col-span-12 lg:col-span-4 space-y-8">
                <!-- Stat Grid -->
                <div class="grid grid-cols-2 gap-6">
                    {{-- Auctions --}}
                    <div class="enterprise-card p-6 flex flex-col">
                        <div class="w-12 h-12 rounded-md bg-slate-800 flex items-center justify-center text-white/90 mb-4 border border-slate-700">
                            <i data-lucide="gavel" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mb-1">Live Sales</span>
                        <span class="text-3xl font-medium text-slate-800 tabular-nums tracking-tighter">{{ number_format($stats['active_auctions'] ?? 0) }}</span>
                    </div>

                    {{-- Inventory --}}
                    <div class="enterprise-card p-6 flex flex-col">
                        <div class="w-12 h-12 rounded-md bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 border border-emerald-100">
                            <i data-lucide="car-front" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mb-1">Fleet Total</span>
                        <span class="text-3xl font-medium text-slate-800 tabular-nums tracking-tighter">{{ number_format($stats['available_cars'] ?? 0) }}</span>
                    </div>

                    {{-- Bids --}}
                    <div class="enterprise-card p-6 flex flex-col border-l-4 border-l-orange-500">
                        <div class="w-12 h-12 rounded-md bg-orange-50 flex items-center justify-center text-orange-600 mb-4 border border-orange-100">
                            <i data-lucide="trending-up" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mb-1">Activity Log</span>
                        <span class="text-3xl font-medium text-slate-800 tabular-nums tracking-tighter">{{ number_format($stats['total_bids'] ?? 0) }}</span>
                    </div>

                    {{-- Leads --}}
                    <div class="enterprise-card p-6 flex flex-col">
                        <div class="w-12 h-12 rounded-md bg-blue-50 flex items-center justify-center text-blue-600 mb-4 border border-blue-100">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest mb-1">New Leads</span>
                        <span class="text-3xl font-medium text-slate-800 tabular-nums tracking-tighter">{{ number_format($stats['pending_negotiations'] ?? 0) }}</span>
                    </div>
                </div>

                <!-- Market Interest -->
                <div class="enterprise-card p-8 bg-white">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h4 class="text-[0.6rem] font-medium text-slate-800 uppercase tracking-[0.2em]">Market Velocity</h4>
                            <p class="text-[0.55rem] text-slate-400 font-normal uppercase mt-1 tracking-widest">Growth Forecast: Stable</p>
                        </div>
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-300"></i>
                    </div>
                    <div class="h-[250px] w-full">
                        <canvas id="bidChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right: Revenue & Global Activity -->
            <div class="col-span-12 lg:col-span-8 space-y-8">
                <!-- Revenue Stream (Slate-800 Dark) -->
                <div class="dark-panel p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#ff6900] blur-[150px] opacity-10"></div>
                    <div class="flex justify-between items-center mb-10 relative z-10">
                        <div>
                            <span class="text-[0.6rem] font-medium text-[#ff6900]/80 uppercase tracking-[0.4em]">Matrix Analysis</span>
                            <h3 class="text-2xl font-medium text-white/90 tracking-tight mt-1 italic">Transaction Stream</h3>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-[0.55rem] text-slate-400 font-medium uppercase tracking-[0.2em]">
                            Real-time Sync
                        </div>
                    </div>
                    <div class="h-[320px] w-full relative z-10">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Live Log: Lean Table -->
                <div class="enterprise-card overflow-hidden bg-white">
                    <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                        <h4 class="text-[0.6rem] font-medium text-slate-500 uppercase tracking-[0.2em]">Global Matrix Telemetry</h4>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Live Node</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/30">
                                    <th class="py-4 px-8 text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100">Participant</th>
                                    <th class="py-4 px-8 text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100">Asset Node</th>
                                    <th class="py-4 px-8 text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100">Status</th>
                                    <th class="py-4 px-8 text-right text-[0.6rem] font-medium text-slate-400 uppercase tracking-widest border-b border-slate-100">Valuation</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recent_bids ?? [] as $bid)
                                    @php
                                        $bidUser = $bid->user ?? null;
                                        $bidAuction = $bid->auction ?? null;
                                        $bidCar = $bidAuction->car ?? null;
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-9 h-9 rounded-full border border-slate-100 overflow-hidden shadow-sm">
                                                    <img src="https://i.pravatar.cc/100?u={{ $bid->user_id ?? 'default' }}" class="w-full h-full object-cover grayscale-[30%] hover:grayscale-0 transition-all">
                                                </div>
                                                <div class="font-medium text-slate-700 text-sm">{{ $bidUser->name ?? 'Operator' }}</div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-8 text-[0.85rem] font-normal text-slate-600">
                                            @if($bidCar)
                                                {{ $bidCar->make }} {{ $bidCar->model }}
                                            @else
                                                <span class="text-slate-400 italic">Unknown Asset</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-8">
                                            <span class="text-[0.55rem] font-medium text-emerald-600 bg-emerald-50/50 px-2.5 py-1 rounded-full border border-emerald-100/50 uppercase tracking-[0.15em] italic">Active</span>
                                        </td>
                                        <td class="py-4 px-8 text-right text-lg font-medium text-slate-800 tracking-tighter">
                                            ${{ number_format($bid->amount ?? 0) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-20 text-center">
                                            <div class="flex flex-col items-center gap-4 opacity-40">
                                                <i data-lucide="layers" class="w-10 h-10 text-slate-300"></i>
                                                <span class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-[0.4em]">Awaiting Uplink</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart Protocol: Normal Weight Calibration --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.font.weight = '400';
            Chart.defaults.color = '#94a3b8';

            // Revenue Stream Chart
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            const revGradient = ctxRev.createLinearGradient(0, 0, 0, 300);
            revGradient.addColorStop(0, 'rgba(255, 70, 5, 0.25)');
            revGradient.addColorStop(1, 'rgba(255, 70, 5, 0)');

            new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    datasets: [{ 
                        data: [420, 380, 560, 480, 720, 640, 890, 810, 950, 1100], 
                        borderColor: '#ff4605',
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ff4605',
                        pointBorderWidth: 1.5,
                        pointRadius: 3,
                        tension: 0.45,
                        fill: true,
                        backgroundColor: revGradient
                    }]
                },
                options: { 
                    maintainAspectRatio: false, 
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)', drawTicks: false }, border: { display: false }, ticks: { font: { size: 10 }, color: '#64748b' } }, 
                        x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 }, color: '#64748b' } } 
                    }
                }
            });

            // Velocity Chart
            const ctxBid = document.getElementById('bidChart').getContext('2d');
            new Chart(ctxBid, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: [65, 85, 75, 95, 125, 110, 140],
                        backgroundColor: '#ff4605',
                        borderRadius: 6,
                        barThickness: 16,
                    }]
                },
                options: { 
                    maintainAspectRatio: false, 
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { grid: { color: '#f1f5f9' }, border: { display: false }, ticks: { font: { size: 10 } } }, 
                        x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 } } } 
                    }
                }
            });
        });
    </script>
@endsection

