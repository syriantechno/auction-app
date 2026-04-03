@extends('admin.layout')

@section('title', 'Stock Management')
@section('page_title', 'Stock Management')

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-[1.5rem] bg-[#1d293d] flex items-center justify-center shadow-xl">
                <i data-lucide="warehouse" class="w-7 h-7 text-[#ff6900]"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter">Stock Management</h1>
                <p class="text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.25em] mt-1">
                    Active: {{ \App\Models\StockEntry::whereIn('status', ['in_stock','qc_in_progress','qc_approved','payment_pending'])->count() }}
                    &nbsp;|&nbsp;
                    Sold: {{ \App\Models\StockEntry::where('status', 'sold')->count() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <form id="filterForm" action="{{ route('admin.stock.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[220px]">
                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Search (Ref / Make / Model)</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="searchInput" placeholder="MB-2026-0001..."
                        class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md pl-11 pr-4 text-sm text-slate-700 outline-none focus:border-orange-400 transition-all">
                </div>
            </div>
            <div class="w-52">
                <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-1.5 block ml-1">Status</label>
                <select name="status" id="statusFilter"
                    class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 text-sm text-slate-700 appearance-none outline-none focus:border-orange-400 transition-all">
                    <option value="">All</option>
                    <option value="in_stock">In Stock</option>
                    <option value="qc_in_progress">QC In Progress</option>
                    <option value="qc_approved">QC Approved</option>
                    <option value="payment_pending">Payment Pending</option>
                    <option value="delivered">Delivered</option>
                    <option value="sold">Sold</option>
                </select>
            </div>
            <button type="button" id="resetBtn"
                class="bg-slate-100 h-[44px] border border-slate-200 text-slate-600 rounded-md px-5 text-[0.65rem] font-medium uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div id="tableContainer">
        @include('admin.stock._table', compact('entries'))
    </div>
</div>

{{-- ===================== QC MODAL ===================== --}}
<div id="qcModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md p-4">
    <div class="bg-[#e7e7e7] w-full max-w-4xl max-h-[90vh] rounded-lg shadow-2xl border border-slate-200 overflow-hidden flex flex-col">

        <div class="bg-[#1d293d] px-8 py-6 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-white/10 rounded-lg flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-6 h-6 text-[#ff6900]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white">Quality Control Check</h3>
                    <p id="qcRefCode" class="text-[0.6rem] text-slate-400 font-black uppercase tracking-widest mt-0.5">REF: —</p>
                </div>
            </div>
            <button onclick="closeQcModal()" class="w-10 h-10 rounded-md bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-5 h-5 text-white"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-8">
            <form id="qcForm" class="space-y-4">
                @php
                $checks = [
                    'paint'        => 'Paint & Exterior',
                    'engine'       => 'Engine',
                    'transmission' => 'Transmission',
                    'interior'     => 'Interior',
                    'tires'        => 'Tires & Wheels',
                    'body'         => 'Body Structure',
                    'documents'    => 'Documents',
                    'keys_count'   => 'Keys Count',
                ];
                @endphp
                @foreach($checks as $key => $label)
                <div class="bg-white rounded-lg border border-slate-200 p-5 flex items-start gap-5">
                    <div class="flex items-center gap-3 shrink-0 pt-1">
                        <input type="checkbox" id="qc_{{ $key }}" name="{{ $key }}_verified" value="1"
                            class="w-5 h-5 accent-emerald-500 cursor-pointer">
                        <label for="qc_{{ $key }}" class="text-[0.7rem] font-black text-slate-700 uppercase tracking-widest cursor-pointer w-32">
                            {{ $label }}
                        </label>
                    </div>
                    <input type="text" name="{{ $key }}_notes" placeholder="Notes (optional)..."
                        class="flex-1 bg-slate-50 border border-slate-200 rounded-md px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-orange-400">
                </div>
                @endforeach

                <div class="bg-white rounded-lg border border-slate-200 p-5">
                    <label class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest block mb-2">Additional Notes</label>
                    <textarea name="additional_notes" rows="3" placeholder="General observations..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-md px-4 py-3 text-sm text-slate-700 outline-none focus:border-orange-400 resize-none"></textarea>
                </div>
            </form>
        </div>

        <div class="shrink-0 px-8 py-6 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-4">
            <p id="qcProgress" class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">— checks verified</p>
            <div class="flex gap-3">
                <button onclick="saveQcReport()" class="px-6 py-3 bg-slate-700 text-white rounded-lg text-[0.65rem] font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Progress
                </button>
                <button id="approveQcBtn" onclick="approveQcReport()" class="px-6 py-3 bg-emerald-500 text-white rounded-lg text-[0.65rem] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i> Approve QC & Send Dealer Email
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== COMPLETE DEAL MODAL ===================== --}}
<div id="completeDealModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md p-4">
    <div class="bg-[#e7e7e7] w-full max-w-lg rounded-lg shadow-2xl border border-slate-200 overflow-hidden">
        <div class="bg-[#1d293d] px-8 py-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <i data-lucide="flag-checkered" class="w-6 h-6 text-emerald-400"></i>
                <h3 class="text-lg font-black text-white">Complete Deal — Stock Exit</h3>
            </div>
            <button onclick="closeCompleteDeal()" class="w-9 h-9 rounded-md bg-white/10 hover:bg-white/20 flex items-center justify-center">
                <i data-lucide="x" class="w-4 h-4 text-white"></i>
            </button>
        </div>
        <div class="p-8 space-y-5">
            <div class="space-y-1.5">
                <label class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">Amount Received ($)</label>
                <input type="number" id="cdAmount" step="100" placeholder="Enter amount..."
                    class="w-full h-14 bg-slate-50 border border-slate-300 rounded-lg px-5 text-xl font-black text-slate-900 outline-none focus:border-emerald-400">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">Delivery Date</label>
                    <input type="date" id="cdDelivery" class="w-full h-11 bg-slate-50 border border-slate-300 rounded-lg px-4 text-sm text-slate-700 outline-none focus:border-emerald-400">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">Transfer Date</label>
                    <input type="date" id="cdTransfer" class="w-full h-11 bg-slate-50 border border-slate-300 rounded-lg px-4 text-sm text-slate-700 outline-none focus:border-emerald-400">
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="text-[0.65rem] font-black text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea id="cdNotes" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-sm text-slate-700 outline-none focus:border-emerald-400 resize-none"></textarea>
            </div>
            <button onclick="submitCompleteDeal()"
                class="w-full h-14 bg-emerald-500 text-white rounded-lg font-black text-[0.7rem] uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center justify-center gap-2">
                <i data-lucide="check" class="w-5 h-5"></i> Confirm — Mark as Sold
            </button>
        </div>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';
let currentStockId = null;

// ─── FILTER SYNC ───
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filterForm');
    let timer;
    ['searchInput','statusFilter'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.addEventListener(id === 'searchInput' ? 'keyup' : 'change', () => {
            clearTimeout(timer);
            timer = setTimeout(syncStock, id === 'searchInput' ? 400 : 0);
        });
    });
    document.getElementById('resetBtn').addEventListener('click', () => {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').selectedIndex = 0;
        syncStock();
    });
    document.addEventListener('click', e => {
        const link = e.target.closest('#tableContainer a[data-page]');
        if(link) { e.preventDefault(); syncStock(new URL(link.href)); }
    });
});

async function syncStock(url = null) {
    const container = document.getElementById('tableContainer');
    const form = document.getElementById('filterForm');
    const target = url || new URL(form.action);
    if (!url) { const fd = new FormData(form); for(let [k,v] of fd) if(v) target.searchParams.set(k,v); }
    container.style.opacity = '0.5';
    try {
        const res = await fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        container.innerHTML = await res.text();
        if(typeof lucide !== 'undefined') lucide.createIcons();
    } finally { container.style.opacity = '1'; }
}

// ─── QC MODAL ───
async function openQcModal(stockId) {
    currentStockId = stockId;
    // Start QC if needed
    await fetch(`/admin/stock/${stockId}/start-qc`, {
        method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
    });
    // Load stock details for ref code
    const res = await fetch(`/admin/stock/${stockId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const data = await res.json();
    document.getElementById('qcRefCode').innerText = 'REF: ' + (data.ref_code || '—');
    // Pre-fill QC if already exists
    if (data.qc) {
        const qc = data.qc;
        ['paint','engine','transmission','interior','tires','body','documents','keys_count'].forEach(key => {
            const cb = document.getElementById('qc_' + key);
            if(cb) cb.checked = qc[key + '_verified'];
            const note = document.querySelector('[name="' + key + '_notes"]');
            if(note) note.value = qc[key + '_notes'] || '';
        });
        const addNotes = document.querySelector('[name="additional_notes"]');
        if(addNotes) addNotes.value = qc.additional_notes || '';
    }
    updateQcProgress();
    document.getElementById('qcModal').classList.remove('hidden');
    if(typeof lucide !== 'undefined') lucide.createIcons();
}

function updateQcProgress() {
    const checkboxes = document.querySelectorAll('#qcForm input[type="checkbox"]');
    const checked = [...checkboxes].filter(c => c.checked).length;
    document.getElementById('qcProgress').innerText = `${checked} / ${checkboxes.length} checks verified`;
    document.querySelectorAll('#qcForm input[type="checkbox"]').forEach(cb =>
        cb.addEventListener('change', updateQcProgress));
}

document.querySelectorAll('#qcForm input[type="checkbox"]').forEach(cb =>
    cb.addEventListener('change', updateQcProgress));

async function saveQcReport() {
    const form = document.getElementById('qcForm');
    const fd = new FormData(form);
    await fetch(`/admin/stock/${currentStockId}/save-qc`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
        body: fd
    });
    window.notify?.success('QC progress saved.');
}

async function approveQcReport() {
    const res = await fetch(`/admin/stock/${currentStockId}/approve-qc`, {
        method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
    });
    const data = await res.json();
    if(data.success) {
        window.notify?.success(data.message);
        closeQcModal();
        syncStock();
    }
}

function closeQcModal() { document.getElementById('qcModal').classList.add('hidden'); currentStockId = null; }

// ─── COMPLETE DEAL ───
function openCompleteDeal(stockId) {
    currentStockId = stockId;
    document.getElementById('cdDelivery').value = new Date().toISOString().split('T')[0];
    document.getElementById('completeDealModal').classList.remove('hidden');
    if(typeof lucide !== 'undefined') lucide.createIcons();
}

async function submitCompleteDeal() {
    const payload = {
        amount_received: document.getElementById('cdAmount').value,
        delivery_date: document.getElementById('cdDelivery').value,
        ownership_transfer_date: document.getElementById('cdTransfer').value,
        notes: document.getElementById('cdNotes').value,
    };
    const res = await fetch(`/admin/stock/${currentStockId}/complete-deal`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload)
    });
    const data = await res.json();
    if(data.success) { window.notify?.success(data.message); closeCompleteDeal(); syncStock(); }
}

function closeCompleteDeal() { document.getElementById('completeDealModal').classList.add('hidden'); currentStockId = null; }
</script>

<style>
.pagination { @apply flex items-center gap-1.5; }
.page-item .page-link { @apply w-10 h-10 rounded-md flex items-center justify-center border-none bg-white text-slate-400 font-medium text-[0.7rem] shadow-sm transition-all; }
.page-item.active .page-link { @apply bg-slate-800 text-white; }
.page-item .page-link:hover { @apply bg-[#ff6900] text-white; }
</style>
@endsection
