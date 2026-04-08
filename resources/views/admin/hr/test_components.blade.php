@extends('admin.layout')

@section('title', 'Component Showcase')

@section('content')
<div x-data="{ opened: false }">
    <x-admin-page-standard 
        icon="component" 
        title="Elite" 
        highlight="Showcase" 
        subtitle="Master component library and design system verification"
        dot="orange">
        
        <x-slot name="actions">
            <x-admin-button icon="plus" @click="opened = true">Open Test Modal</x-admin-button>
            <x-admin-button icon="external-link" variant="outline">Preview Live</x-admin-button>
        </x-slot>

        <!-- 1. Stats Grid Showcase -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <x-admin-stat-card label="Total Revenue" value="$128.4k" icon="dollar-sign" color="emerald" />
            <x-admin-stat-card label="Active Users" value="2,840" icon="users" color="emerald" />
            <x-admin-stat-card label="Pending Tasks" value="14" icon="clock" color="orange" />
            <x-admin-stat-card label="Critical Errors" value="0" icon="alert-circle" color="rose" />
        </div>

        <!-- Pages Processed Stats -->
        <div class="bg-gradient-to-r from-emerald-50 to-orange-50 rounded-xl border border-emerald-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#ff6900]"></i>
                Pages Successfully Processed
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <x-admin-stat-card label="HR Dashboard" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Departments" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Positions" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Employees" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Shifts" value="✓" icon="check-circle" color="emerald" />
                <x-admin-stat-card label="Attendance" value="✓" icon="check-circle" color="emerald" />
            </div>
            <div class="mt-4 p-3 bg-emerald-100 rounded-lg border border-emerald-200">
                <p class="text-sm font-medium text-emerald-800">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    <strong>6 pages</strong> successfully unified with the new design system
                </p>
            </div>
        </div>

        <!-- 2. Filter Bar Showcase -->
        <x-admin-filter-bar>
            <div class="flex-1 max-w-sm">
                <x-admin-input icon="search" placeholder="Search components..." />
            </div>
            <div class="w-48">
                <x-elite-select icon="tag" name="category" placeholder="All Categories">
                    <option>All Categories</option>
                    <option>Interface</option>
                    <option>Navigation</option>
                </x-elite-select>
            </div>
            <x-admin-button variant="secondary" icon="download">Export Report</x-admin-button>
        </x-admin-filter-bar>

        <!-- 3. Form & Elements Section -->
        <x-admin-card title="System Interface Core" icon="layers">
            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Standard Form Elements</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <x-admin-input label="Full Name" placeholder="John Doe" icon="user" />
                    <x-elite-select label="Department" icon="layers" name="dept">
                        <option>Engineering</option>
                        <option>HR & Culture</option>
                    </x-elite-select>
                    <div class="md:col-span-2">
                        <x-elite-picker dateName="test_date" timeName="test_time" dateId="showcaseDate" timeId="showcaseTime" />
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Table Action Components</h4>
                <div class="flex items-center gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                    <x-admin-action icon="eye" title="View Details" variant="slate" />
                    <x-admin-action icon="edit" title="Edit Record" variant="orange" />
                    <x-admin-action icon="trash" title="Delete Permanent" variant="red" />
                    <x-admin-action icon="plus" title="Add New" variant="emerald" />
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Button Variants</h4>
                <div class="flex flex-wrap gap-4">
                    <x-admin-button variant="primary">Primary Action</x-admin-button>
                    <x-admin-button variant="orange" icon="zap">Orange High-Click</x-admin-button>
                    <x-admin-button variant="secondary" icon="settings">Secondary Tools</x-admin-button>
                    <x-admin-button variant="red" icon="trash-2">Danger Zone</x-admin-button>
                    <x-admin-button variant="outline" icon="share-2">Outline Ghost</x-admin-button>
                </div>
            </div>

            <div>
                <h4 class="text-[0.65rem] font-black uppercase text-slate-400 tracking-[0.3em] mb-8 border-b pb-4">Status & Action Markers</h4>
                <div class="flex gap-4">
                    <x-admin-action icon="edit-3" title="Edit" variant="slate" />
                    <x-admin-action icon="check" title="Approve" variant="emerald" />
                    <x-admin-action icon="x" title="Reject" variant="red" />
                    <x-admin-action icon="download" title="Save" variant="orange" />
                </div>
            </div>
        </x-admin-card>

        <!-- 4. Data View & Empty State Showcase -->
        <div class="space-y-6">
             <h4 class="text-[0.7rem] font-black uppercase text-[#ff6900] tracking-[0.4em] text-center mb-10">— Intelligent Data View Simulation —</h4>
             
             <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Case A: With Data -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: With Active Data</p>
                    <x-admin-data-view :count="1">
                        <x-admin-data-item number="01" title="Sample Active Record" subtitle="Active System Instance">
                            <x-admin-action icon="edit-3" title="Edit" variant="slate" />
                            <x-admin-action icon="eye" title="View" variant="orange" />
                        </x-admin-data-item>
                    </x-admin-data-view>
                </div>

                @php
                    $mockTask = (object)[
                        'id' => 77,
                        'car_id' => 123,
                        'car_details' => [
                            'make' => 'Mercedes-Benz',
                            'model' => 'G-Class AMG',
                            'year' => '2024',
                            'location' => 'Downtown Dubai, UAE',
                            'inspection_date' => date('Y-m-d'),
                            'inspection_time' => '10:30 AM',
                            'phone' => '+971 50 123 4567',
                            'vin' => 'G63-AMG-TEST-2024'
                        ]
                    ];
                @endphp
                <!-- 5. Mission Card Showcase -->
                <div class="col-span-12 grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Original Operational Card</p>
                        <x-admin-mission-card :task="$mockTask" :isToday="true" />
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">Developed Inspect Elite Card</p>
                        <x-elite-mission-card :task="$mockTask" :isToday="true" status="Active" />
                    </div>
                </div>

                <!-- Case B: Empty State -->
                <div class="space-y-4">
                    <p class="text-center text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest italic mb-6">State: Null / Empty</p>
                    <x-admin-data-view :count="0" emptyTitle="No Records Found" emptySubtitle="The components gallery has no dynamic items to list at this moment." emptyIcon="box-select" />
                </div>
             </div>
        </div>

    {{-- ═══════════════════════════════════ PRICE vs MILEAGE ANALYTICS CHARTS ═══ --}}
    <div class="mt-10 space-y-3">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-1 h-8 bg-gradient-to-b from-[#ff6900] to-orange-300 rounded-full"></div>
            <div>
                <h2 class="text-xl font-black text-[#031629] tracking-tight">Price vs. Mileage Analytics</h2>
                <p class="text-[0.65rem] text-slate-400 font-medium uppercase tracking-widest">Pre-owned car market · UAE sample data · 2024</p>
            </div>
        </div>

        {{-- Row 1: Scatter + Bar --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Chart 1: Scatter Plot --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                <div class="flex items-start justify-between mb-1">
                    <div>
                        <h3 class="text-[0.85rem] font-black text-[#031629]">Price × Mileage Distribution</h3>
                        <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Per-vehicle scatter by make</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-500 text-[0.55rem] font-black uppercase tracking-widest rounded-full">Scatter</span>
                </div>
                <div id="chart-scatter" class="mt-2"></div>
            </div>

            {{-- Chart 2: Bar — Avg Price by Mileage Bracket --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                <div class="flex items-start justify-between mb-1">
                    <div>
                        <h3 class="text-[0.85rem] font-black text-[#031629]">Avg. Price by Mileage Range</h3>
                        <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Grouped by 20,000 km buckets</p>
                    </div>
                    <span class="px-3 py-1 bg-orange-50 text-orange-500 text-[0.55rem] font-black uppercase tracking-widest rounded-full">Bar</span>
                </div>
                <div id="chart-bar" class="mt-2"></div>
            </div>
        </div>

        {{-- Row 2: Area Line + Bubble --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Chart 3: Line — Depreciation Curve --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                <div class="flex items-start justify-between mb-1">
                    <div>
                        <h3 class="text-[0.85rem] font-black text-[#031629]">Price Depreciation Curve</h3>
                        <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Luxury vs. economy vs. SUV segments</p>
                    </div>
                    <span class="px-3 py-1 bg-violet-50 text-violet-500 text-[0.55rem] font-black uppercase tracking-widest rounded-full">Trend</span>
                </div>
                <div id="chart-line" class="mt-2"></div>
            </div>

            {{-- Chart 4: Bubble — Make × Mileage × Price × Volume --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6">
                <div class="flex items-start justify-between mb-1">
                    <div>
                        <h3 class="text-[0.85rem] font-black text-[#031629]">Market Bubble Map</h3>
                        <p class="text-[0.6rem] text-slate-400 font-medium uppercase tracking-widest mt-0.5">Make · avg mileage · avg price · volume</p>
                    </div>
                    <span class="px-3 py-1 bg-teal-50 text-teal-500 text-[0.55rem] font-black uppercase tracking-widest rounded-full">Bubble</span>
                </div>
                <div id="chart-bubble" class="mt-2"></div>
            </div>
        </div>
    </div>

    </x-admin-page-standard>

    <!-- Modal Showcase -->
    <x-admin-modal x-show="opened" title="Elite Modal Interface" icon="layout" size="max-w-2xl">
        <div class="col-span-12 grid grid-cols-2 gap-6">
            <x-admin-input label="Test Label One" placeholder="Entering data..." icon="hash" />
            <x-admin-input label="Test Label Two" placeholder="Optional value..." icon="edit" />
        </div>
        <div class="col-span-12 mt-4">
            <x-admin-select label="Priority Ranking" icon="flag">
                <option>Low Importance</option>
                <option>Medium Tier</option>
                <option>Critical / Strategic</option>
            </x-admin-select>
        </div>
        <div class="col-span-12 mt-4">
             <x-admin-input label="Additional Notes" placeholder="Write something professional..." />
        </div>
        
        <x-slot name="footer">
             <x-admin-button variant="secondary" @click="opened = false">Cancel Mission</x-admin-button>
             <x-admin-button icon="check" @click="opened = false">Execute Changes</x-admin-button>
        </x-slot>
    </x-admin-modal>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fmt = v => 'AED ' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v);
    const palette = {
        blue:   '#3b82f6',
        orange: '#ff6900',
        violet: '#8b5cf6',
        teal:   '#14b8a6',
        rose:   '#f43f5e',
        amber:  '#f59e0b',
        slate:  '#64748b',
    };

    /* ─────────────────────────────────────────────────────────────
       CHART 1 — SCATTER: Price × Mileage by Make
    ───────────────────────────────────────────────────────────── */
    new ApexCharts(document.getElementById('chart-scatter'), {
        chart: { type: 'scatter', height: 340, toolbar: { show: false },
                 zoom: { enabled: true, type: 'xy' },
                 fontFamily: 'Plus Jakarta Sans, sans-serif' },
        colors: [palette.blue, palette.orange, palette.violet, palette.teal, palette.rose],
        series: [
            { name: 'Toyota', data: [[12000,88000],[34000,72000],[55000,61000],[78000,52000],[102000,44000],[145000,35000],[188000,26000]] },
            { name: 'BMW',    data: [[8000,195000],[21000,172000],[44000,148000],[67000,126000],[95000,104000],[130000,88000],[162000,71000]] },
            { name: 'Mercedes', data: [[5000,245000],[18000,218000],[39000,192000],[60000,168000],[88000,145000],[115000,122000],[150000,98000]] },
            { name: 'Nissan',   data: [[15000,72000],[38000,62000],[61000,54000],[84000,47000],[110000,40000],[140000,33000],[175000,26000]] },
            { name: 'Tesla',    data: [[6000,162000],[22000,148000],[40000,136000],[58000,125000],[80000,115000],[105000,104000],[135000,94000]] },
        ],
        xaxis: { title: { text: 'Mileage (km)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
                 type: 'numeric', tickAmount: 6,
                 labels: { formatter: v => (v/1000).toFixed(0)+'k km', style: { fontSize: '10px' } } },
        yaxis: { title: { text: 'Price (AED)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
                 labels: { formatter: fmt, style: { fontSize: '10px' } } },
        tooltip: { x: { formatter: v => v.toLocaleString() + ' km' }, y: { formatter: fmt } },
        legend: { position: 'bottom', fontWeight: 700, fontSize: '11px' },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        markers: { size: 6, strokeWidth: 2, strokeColors: '#fff', hover: { size: 9 } },
    }).render();

    /* ─────────────────────────────────────────────────────────────
       CHART 2 — BAR: Avg Price by Mileage Bracket
    ───────────────────────────────────────────────────────────── */
    new ApexCharts(document.getElementById('chart-bar'), {
        chart: { type: 'bar', height: 340, toolbar: { show: false },
                 fontFamily: 'Plus Jakarta Sans, sans-serif' },
        plotOptions: { bar: { borderRadius: 8, columnWidth: '55%',
                              dataLabels: { position: 'top' } } },
        colors: ['#ff6900'],
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical',
                shadeIntensity: 0.4, gradientToColors: ['#fbbf24'], stops: [0, 100] } },
        series: [{
            name: 'Avg Price (AED)',
            data: [187000, 154000, 132000, 114000, 98000, 83000, 70000, 58000, 46000, 35000],
        }],
        xaxis: {
            categories: ['0–20k','20–40k','40–60k','60–80k','80–100k','100–120k','120–140k','140–160k','160–180k','180k+'],
            title: { text: 'Mileage Range (km)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
            labels: { style: { fontSize: '10px', fontWeight: 600 } },
        },
        yaxis: { title: { text: 'Avg Price (AED)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
                 labels: { formatter: fmt, style: { fontSize: '10px' } } },
        dataLabels: { enabled: true, formatter: fmt,
                      style: { fontSize: '9px', fontWeight: 700, colors: ['#031629'] },
                      offsetY: -6 },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } },
        tooltip: { y: { formatter: fmt } },
    }).render();

    /* ─────────────────────────────────────────────────────────────
       CHART 3 — AREA LINE: Depreciation Curves by Segment
    ───────────────────────────────────────────────────────────── */
    new ApexCharts(document.getElementById('chart-line'), {
        chart: { type: 'area', height: 340, toolbar: { show: false },
                 fontFamily: 'Plus Jakarta Sans, sans-serif',
                 animations: { enabled: true, easing: 'easeinout', speed: 800 } },
        colors: [palette.violet, palette.orange, palette.teal],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.25, opacityTo: 0.02 } },
        series: [
            { name: 'Luxury',  data: [320000, 290000, 258000, 228000, 200000, 175000, 152000, 132000, 114000, 98000] },
            { name: 'SUV',     data: [185000, 168000, 151000, 136000, 122000, 109000,  97000,  86000,  76000, 67000] },
            { name: 'Economy', data: [95000,   87000,  80000,  73000,  67000,  61000,  56000,  51000,  46000, 42000] },
        ],
        xaxis: {
            categories: ['0k','20k','40k','60k','80k','100k','120k','140k','160k','180k'],
            title: { text: 'Mileage (km)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
            labels: { style: { fontSize: '10px', fontWeight: 600 } },
        },
        yaxis: { labels: { formatter: fmt, style: { fontSize: '10px' } } },
        tooltip: { y: { formatter: fmt }, shared: true, intersect: false },
        legend: { position: 'bottom', fontWeight: 700, fontSize: '11px' },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        markers: { size: 4, strokeWidth: 2, strokeColors: '#fff', hover: { size: 7 } },
        annotations: {
            yaxis: [{ y: 100000, borderColor: '#94a3b8', borderWidth: 1,
                      strokeDashArray: 6,
                      label: { text: 'AED 100k threshold', style: { fontSize: '10px', fontWeight: 700, color: '#64748b', background: '#f8fafc' } } }]
        },
    }).render();

    /* ─────────────────────────────────────────────────────────────
       CHART 4 — BUBBLE: Make × Avg Mileage × Avg Price × Volume
    ───────────────────────────────────────────────────────────── */
    new ApexCharts(document.getElementById('chart-bubble'), {
        chart: { type: 'bubble', height: 340, toolbar: { show: false },
                 fontFamily: 'Plus Jakarta Sans, sans-serif' },
        colors: [palette.blue, palette.orange, palette.violet, palette.teal, palette.rose, palette.amber, palette.slate],
        series: [
            { name: 'Toyota',   data: [[72000, 68000,  42]] },
            { name: 'BMW',      data: [[58000, 138000, 28]] },
            { name: 'Mercedes', data: [[48000, 172000, 22]] },
            { name: 'Nissan',   data: [[85000, 55000,  38]] },
            { name: 'Tesla',    data: [[38000, 142000, 18]] },
            { name: 'Hyundai',  data: [[92000, 46000,  35]] },
            { name: 'Lexus',    data: [[54000, 118000, 24]] },
        ],
        xaxis: { type: 'numeric', tickAmount: 6,
                 title: { text: 'Avg Mileage (km)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
                 labels: { formatter: v => (v/1000).toFixed(0)+'k', style: { fontSize: '10px' } } },
        yaxis: { title: { text: 'Avg Price (AED)', style: { fontWeight: 700, fontSize: '11px', color: '#94a3b8' } },
                 labels: { formatter: fmt, style: { fontSize: '10px' } } },
        tooltip: {
            custom: ({ seriesIndex, dataPointIndex, w }) => {
                const s = w.config.series[seriesIndex];
                const d = s.data[dataPointIndex];
                return `<div class="px-4 py-3 text-xs font-bold">
                    <div class="text-[0.7rem] font-black text-slate-800 mb-1">${s.name}</div>
                    <div class="text-slate-500">Avg Mileage: <b>${d[0].toLocaleString()} km</b></div>
                    <div class="text-slate-500">Avg Price: <b>AED ${d[1].toLocaleString()}</b></div>
                    <div class="text-slate-500">Listings: <b>${d[2]}</b></div>
                </div>`;
            }
        },
        legend: { position: 'bottom', fontWeight: 700, fontSize: '11px' },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        fill: { opacity: 0.85 },
    }).render();

});
</script>
@endpush

@endsection
