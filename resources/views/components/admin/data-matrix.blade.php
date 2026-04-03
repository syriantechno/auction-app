@props([
    'id', 
    'title', 
    'subtitle', 
    'apiRoute', 
    'columns', 
    'createButtonText' => null, 
    'createAction' => null,
    'stats' => []
])

<div class="space-y-6 animate-in slide-in-from-bottom duration-700">
    <!-- Header Context -->
    <div class="flex items-center justify-between px-1">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tighter italic">{{ $title }}</h2>
            <p class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-[0.2em]">{{ $subtitle }}</p>
        </div>
        <div class="flex items-center gap-2 text-[0.6rem] font-bold text-[#ff6900] bg-orange-50 px-3 py-1 rounded-full border border-orange-100">
            <span class="relative flex h-1.5 w-1.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-[#ff6900]"></span>
            </span>
            Real-time Pipeline
        </div>
    </div>

    <!-- Unified Stats Matrix -->
    @if(!empty($stats))
    <div class="grid grid-cols-1 md:grid-cols-{{ count($stats) }} gap-4">
        @foreach($stats as $stat)
        <div class="group relative bg-white/60 backdrop-blur-md p-5 rounded-lg border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300">
            <span class="text-[0.55rem] text-slate-400 font-extrabold uppercase tracking-[0.15em] block mb-2">{{ $stat['label'] }}</span>
            <div class="flex items-end justify-between relative z-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stat['value'] }}</h3>
                    <p class="text-[0.6rem] font-bold text-slate-500 mt-0.5 uppercase tracking-tight">{{ $stat['sub'] ?? '' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Command Center -->
    <div class="bg-white p-5 rounded-lg shadow-[0_1px_3px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        <div class="relative flex-1 max-w-lg group">
            <input id="{{ $id }}-search" type="text" 
                class="w-full bg-slate-50 border border-slate-100 rounded-lg pl-11 pr-4 py-2.5 text-sm font-semibold outline-none focus:bg-white focus:border-orange-500/20 transition-all duration-300" 
                placeholder="Search matrix assets...">
            <i data-lucide="zap" class="w-4 h-4 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-[#ff6900] transition-colors"></i>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="{{ $id }}Table.download('csv', '{{ $id }}_export.csv')" 
                class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-lg text-[0.65rem] font-bold uppercase tracking-widest transition-all hover:bg-slate-50 hover:border-slate-300 active:scale-95 flex items-center gap-2">
                <i data-lucide="hard-drive" class="w-3.5 h-3.5"></i> Export CSV
            </button>
            @if($createButtonText)
            <button onclick="{{ $createAction }}" style="background: var(--primary-orange);" 
                class="text-white px-6 py-2.5 rounded-lg text-[0.65rem] font-black uppercase tracking-widest shadow-lg shadow-orange-500/10 transition-all hover:opacity-90 active:scale-95 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> {{ $createButtonText }}
            </button>
            @endif
        </div>
    </div>

    <!-- Datagrid -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden min-h-[500px]">
        <div id="{{ $id }}-table" class="matrix-enterprise-table"></div>
    </div>
</div>

<style>
    .matrix-enterprise-table {
        border: none !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background: transparent !important;
    }

    .tabulator-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        color: #ffffff !important;
        border-bottom: 3px solid var(--primary-orange) !important;
        padding: 5px 0 !important;
    }

    .tabulator-col {
        background-color: transparent !important;
        padding: 14px 10px !important;
        border: none !important;
    }

    .tabulator-col-title {
        color: #ffffff !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
    }

    .tabulator-row {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        min-height: 56px !important;
        transition: all 0.2s ease;
    }

    .tabulator-row:hover {
        background-color: #f8fafc !important;
        box-shadow: inset 4px 0 0 var(--primary-orange);
    }

    .tabulator-cell {
        padding: 14px 10px !important;
        font-size: 0.8rem !important;
        color: #111827 !important;
        font-weight: 600 !important;
        border: none !important;
        display: flex;
        align-items: center;
    }

    @keyframes loading-bar {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = @json($columns);
        
        // Resolve String Formatters to Global Functions (Elite Matrix Scaling)
        const resolvedColumns = columns.map(col => {
            if (typeof col.formatter === 'string' && window[col.formatter]) {
                col.formatter = window[col.formatter];
            }
            return col;
        });

        var table = new Tabulator("#{{ $id }}-table", {
            ajaxURL: "{{ $apiRoute }}",
            layout: "fitColumns",
            pagination: "remote", 
            paginationSize: 50,
            paginationSizeSelector: [15, 30, 50, 100],
            ajaxConfig: "GET",
            // Tabulator handles page/size automatically with remote pagination
            ajaxResponse: function(url, params, response) {
                return {
                    last_page: response.last_page || 1,
                    data: response.data || []
                };
            },
            placeholder: `
                <div class="flex flex-col items-center justify-center p-24 gap-6">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-full border-4 border-slate-50 border-t-[#ff4605] animate-spin shadow-inner"></div>
                        <div class="absolute inset-2 w-10 h-10 bg-slate-50/20 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-300">
                            <i data-lucide="database" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-[0.6rem] font-black uppercase tracking-[0.5em] text-slate-300 animate-pulse">Syncing Matrix Node</span>
                        <div class="h-1 w-32 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-[#ff4605] animate-[loading-bar_2s_infinite]"></div>
                        </div>
                    </div>
                </div>
            `,
            columns: resolvedColumns,
            renderComplete: function() {
                if (typeof lucide !== 'undefined') { lucide.createIcons(); }
            },
            ajaxError: function(error) {
                console.error("Matrix Sync Error:", error);
            }
        });

        window.{{ $id }}Table = table;

        document.getElementById("{{ $id }}-search").addEventListener("keyup", function(e) {
            const val = e.target.value;
            const filters = [];
            
            // Get all visible column fields for global search
            table.getColumns().forEach(col => {
                const field = col.getField();
                if (field && field !== 'actions') {
                    filters.push({field: field, type: "like", value: val});
                }
            });

            // Apply OR Filter logic
            table.setFilter([filters]); 
        });
    });
</script>

