@extends('admin.layout')

@section('title', 'Manage Auctions Matrix')

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">
    <!-- Header: Operational Hub -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-[2rem] bg-[#1d293d] flex items-center justify-center shadow-xl shadow-slate-200">
                <i data-lucide="gavel" class="w-8 h-8 text-[#ff6900]"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter">Auction LifeCycle</h1>
                <p class="text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.25em] mt-1">Matrix Monitoring & Approval Hub</p>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
             <div class="flex items-center gap-4 bg-white px-6 py-3 rounded-lg border border-slate-100 shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[0.55rem] font-black uppercase text-slate-400 tracking-widest leading-none">Global Pulse</span>
                    <span id="totalCounter" class="text-xl font-black text-[#031629] tabular-nums mt-1">{{ number_format(\App\Models\Auction::where('status', 'active')->count()) }}</span>
                </div>
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
            
            <a href="{{ route('admin.auctions.create') }}" class="px-8 h-12 bg-[#ff4605] text-white rounded-lg font-black shadow-xl shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all text-[0.7rem] uppercase tracking-widest flex items-center gap-3">
                <i data-lucide="plus" class="w-4 h-4"></i> Launch New Segment
            </a>
        </div>
    </div>

    <!-- Auctions Matrix Toolbar -->
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 mb-8">
        <form id="filterForm" action="{{ route('admin.auctions.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
            <div class="flex-1 min-w-[300px]">
                <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Asset Neural Search</label>
                <div class="relative group">
                    <i data-lucide="search" class="w-4 h-4 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-[#ff6900] transition-colors"></i>
                    <input type="text" name="search" id="searchInput" placeholder="Identify VIN, Model or Brand..." class="w-full h-14 bg-slate-50 border border-slate-100 rounded-lg pl-12 pr-4 py-2 text-sm font-bold text-slate-700 outline-none focus:bg-white focus:border-orange-500/30 transition-all shadow-inner">
                </div>
            </div>
            
            <div class="w-64">
                <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Lifecycle State</label>
                <div class="relative">
                    <select name="status" id="statusFilter" class="w-full h-14 bg-slate-50 border border-slate-100 rounded-lg px-5 py-2 text-sm font-bold text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/30 transition-all shadow-inner">
                        <option value="">All Matrix Realms</option>
                        <option value="coming_soon">Coming Soon (Pending)</option>
                        <option value="active">Active Session</option>
                        <option value="paused">Paused Nodes</option>
                        <option value="closed">Historical Archive</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-300 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            
            <button type="button" id="resetBtn" class="bg-white h-14 border border-slate-100 text-slate-500 rounded-lg px-6 font-black text-[0.65rem] uppercase tracking-widest hover:text-[#ff6900] hover:border-orange-200 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset Sync
            </button>
        </form>
    </div>

    <!-- High-Performance Matrix Table Container -->
    <div id="tableContainer" class="relative">
        @include('admin.auctions._table', ['auctions' => $auctions])
    </div>
</div>

<script>
    // AUCTIONS MATRIX ENGINE: FRAGMENT SYNC (Lean Typography Standard)
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetBtn');
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        let searchTimeout = null;

        const filters = ['searchInput', 'statusFilter'];
        filters.forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                const ev = id === 'searchInput' ? 'keyup' : 'change';
                el.addEventListener(ev, () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(syncMatrix, id === 'searchInput' ? 400 : 0);
                });
            }
        });

        resetBtn.addEventListener('click', () => {
            searchInput.value = '';
            document.getElementById('statusFilter').selectedIndex = 0;
            syncMatrix();
        });

        document.addEventListener('click', (e) => {
            const link = e.target.closest('#paginationContainer a');
            if (link) {
                e.preventDefault();
                syncMatrix(new URL(link.href));
            }
        });

        // Export syncMatrix to window so other functions can call it
        window.syncMatrix = async function(targetUrl = null) {
            const container = document.getElementById('tableContainer');
            const url = targetUrl || new URL(form.action);
            if(!targetUrl) {
                const fd = new FormData(form);
                for (let [k,v] of fd) { if(v) url.searchParams.set(k,v); }
            }
            
            container.style.opacity = '0.5';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                container.innerHTML = html;
                if (typeof lucide !== 'undefined') lucide.createIcons();
                window.history.pushState({}, '', url.toString());
            } catch (err) { console.error("Matrix Disruption", err); }
            finally {
                container.style.opacity = '1';
            }
        }
    });

    async function purgeAuction(id) {
        const result = await Swal.fire({
            title: 'Authorize Cycle Purge?',
            text: "Permanent removal of auction node #" + id,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4605',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Yes, Purge Cycle',
            customClass: { popup: 'rounded-lg' }
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/auctions/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if(data.success) {
                    if(window.notify) window.notify.success(data.message || 'Cycle Purged');
                    window.syncMatrix();
                }
            } catch (err) { if(window.notify) window.notify.error("Purge Protocol Error"); }
        }
    }

    async function approveAuction(id) {
        const { value: minutes } = await Swal.fire({
            title: 'Set Live Duration',
            text: "Define how many minutes this auction will remain active.",
            input: 'number',
            inputValue: 20,
            inputAttributes: { min: 1, step: 1 },
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Launch Live Session',
            customClass: { popup: 'rounded-[2rem]', input: 'text-center font-black text-2xl border-orange-100 bg-orange-50 rounded-lg' }
        });

        if (minutes) {
            try {
                const res = await fetch(`/admin/auctions/${id}/approve`, {
                    method: 'POST',
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ duration: parseInt(minutes) })
                });
                const data = await res.json();
                if(data.success) {
                    if(window.notify) window.notify.success(data.message || 'Auction Live');
                    window.syncMatrix();
                }
            } catch (err) { if(window.notify) window.notify.error("Approval Sequence Failed"); }
        }
    }
</script>

<style>
    /* Professional Pagination Navigator (Auctions Matrix Standard) */
    .pagination { @apply flex items-center gap-1.5 mt-0 MB-0; }
    .page-item .page-link { 
        @apply w-10 h-10 rounded-md flex items-center justify-center border-none bg-white text-slate-400 font-medium text-[0.7rem] transition-all shadow-sm; 
    }
    .page-item.active .page-link { @apply bg-slate-800 text-white shadow-lg; }
    .page-item .page-link:hover { @apply bg-[#ff6900] text-white; }
</style>
@endsection

