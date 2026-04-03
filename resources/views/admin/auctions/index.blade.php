@extends('admin.layout')

@section('title', 'Manage Auctions')

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">

    <x-admin-header icon="gavel" title="Auctions"
        subtitle="Live & upcoming auction sessions">
        <x-slot name="actions">
            <div class="flex items-center gap-2 bg-white px-5 py-2.5 rounded-lg border border-slate-100 shadow-sm">
                <span class="text-[0.55rem] font-black uppercase text-slate-400 tracking-widest">Active</span>
                <span id="totalCounter" class="text-xl font-black text-[#031629] tabular-nums">{{ \App\Models\Auction::where('status', 'active')->count() }}</span>
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
            </div>
            <a href="{{ route('admin.auctions.create') }}" class="px-6 h-11 bg-[#ff4605] text-white rounded-lg font-black shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all text-[0.7rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> New Auction
            </a>
        </x-slot>
    </x-admin-header>

    {{-- Filter Toolbar --}}
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-900/5 mb-8">
        <form id="filterForm" action="{{ route('admin.auctions.index') }}" method="GET" class="flex flex-wrap items-end gap-6">
            <div class="flex-1 min-w-[300px]">
                <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Search</label>
                <div class="relative group">
                    <i data-lucide="search" class="w-4 h-4 text-slate-300 absolute left-4 top-1/2 -translate-y-1/2 group-focus-within:text-[#ff6900] transition-colors"></i>
                    <input type="text" name="search" id="searchInput" placeholder="Identify VIN, Model or Brand..." class="w-full h-14 bg-slate-50 border border-slate-100 rounded-lg pl-12 pr-4 py-2 text-sm font-bold text-slate-700 outline-none focus:bg-white focus:border-orange-500/30 transition-all shadow-inner">
                </div>
            </div>
            
            <div class="w-64">
                <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2 block ml-1">Lifecycle State</label>
                <div class="relative">
                    <select name="status" id="statusFilter" class="w-full h-14 bg-slate-50 border border-slate-100 rounded-lg px-5 py-2 text-sm font-bold text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/30 transition-all shadow-inner">
                        <option value="">All Statuses</option>
                        <option value="coming_soon">Coming Soon (Pending)</option>
                        <option value="active">Active Session</option>
                        <option value="paused">Paused Nodes</option>
                        <option value="closed">Closed</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-300 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            
            <button type="button" id="resetBtn" class="bg-white h-14 border border-slate-100 text-slate-500 rounded-lg px-6 font-black text-[0.65rem] uppercase tracking-widest hover:text-[#ff6900] hover:border-orange-200 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset
            </button>
        </form>
    </div>

    <!-- Table -->
    <div id="tableContainer" class="relative">
        @include('admin.auctions._table', ['auctions' => $auctions])
    </div>
</div>

{{-- ===================== NEGOTIATION PANEL MODAL ===================== --}}
<div id="negotiationModal" class="hidden fixed inset-0 z-[120] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-md p-4">
    <div class="bg-[#e7e7e7] w-full max-w-3xl rounded-lg shadow-2xl border border-slate-200 overflow-hidden animate-in zoom-in-95 duration-200">

        {{-- Header --}}
        <div class="bg-[#1d293d] px-10 py-7 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 bg-white/10 rounded-lg flex items-center justify-center">
                    <i data-lucide="handshake" class="w-6 h-6 text-[#ff6900]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight">Negotiation Panel</h3>
                    <p id="negRefCode" class="text-[0.6rem] text-slate-400 font-black uppercase tracking-widest mt-0.5">REF: —</p>
                </div>
            </div>
            <button onclick="closeNegotiationModal()" class="w-10 h-10 rounded-md bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-5 h-5 text-white"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-10 space-y-8">

            {{-- Bid Summary --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded-lg border border-slate-200 p-6 text-center shadow-sm">
                    <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2">Highest Bid (Dealer)</p>
                    <p id="negTopBid" class="text-2xl font-black text-slate-900 tabular-nums">—</p>
                    <p id="negBidderName" class="text-[0.6rem] text-slate-400 mt-1">—</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-6 text-center shadow-sm">
                    <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2">Offer to Lead Owner</p>
                    <p id="negOfferDisplay" class="text-2xl font-black text-purple-600 tabular-nums">—</p>
                    <p class="text-[0.6rem] text-slate-400 mt-1">What we pay for the car</p>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-6 text-center shadow-sm">
                    <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest mb-2">Profit Margin</p>
                    <p id="negProfitDisplay" class="text-2xl font-black text-emerald-600 tabular-nums">—</p>
                    <p class="text-[0.6rem] text-slate-400 mt-1">Dealer ↑ minus Owner ↓</p>
                </div>
            </div>

            {{-- Negotiation Status Badge --}}
            <div class="flex items-center gap-3">
                <span class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Status:</span>
                <span id="negStatusBadge" class="px-3 py-1 rounded-full text-[0.6rem] font-black uppercase tracking-widest border">—</span>
            </div>

            {{-- Offer Form --}}
            <div id="negOfferForm" class="bg-white rounded-lg border border-slate-200 p-8 space-y-6">
                <h4 class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest">Set Offer to Lead Owner</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Offer Amount ($)</label>
                        <input type="number" id="negOfferInput" placeholder="45000" min="0" step="100"
                            class="w-full h-14 bg-slate-50 border border-slate-200 rounded-lg px-5 text-xl font-black text-slate-900 outline-none focus:border-purple-400 transition-all tabular-nums">
                        <p id="negProfitCalc" class="text-[0.65rem] text-emerald-600 font-black"></p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Notes</label>
                        <textarea id="negNotes" rows="3" placeholder="Optional notes..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-5 py-3 text-sm text-slate-700 outline-none focus:border-purple-400 transition-all resize-none"></textarea>
                    </div>
                </div>
                <button onclick="sendNegotiationOffer()" class="px-8 py-4 bg-purple-600 text-white rounded-lg text-[0.7rem] font-black uppercase tracking-widest hover:bg-purple-700 transition-all flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> Send Offer to Lead Owner
                </button>
            </div>

            {{-- Accept / Reject / Counter --}}
            <div id="negActionsPanel" class="hidden flex flex-wrap gap-4">
                <button onclick="acceptDeal()" class="px-8 py-4 bg-emerald-500 text-white rounded-lg text-[0.7rem] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i> Lead Accepted — Approve Deal
                </button>
                <button onclick="rejectDeal()" class="px-8 py-4 bg-red-500 text-white rounded-lg text-[0.7rem] font-black uppercase tracking-widest hover:bg-red-600 transition-all flex items-center gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Lead Rejected
                </button>
                <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                    <input type="number" id="counterOfferInput" placeholder="Counter offer..." min="0" step="100"
                        class="h-11 w-44 bg-slate-50 border border-slate-200 rounded-lg px-4 text-sm font-black text-slate-900 outline-none">
                    <button onclick="recordCounterOffer()" class="px-5 py-3 bg-slate-700 text-white rounded-lg text-[0.6rem] font-black uppercase tracking-widest hover:bg-slate-900 transition-all">
                        Record Counter
                    </button>
                </div>
            </div>
        </div>
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
            } catch (err) { console.error("Sync Error", err); }
            finally {
                container.style.opacity = '1';
            }
        }
    });

    async function purgeAuction(id) {
        const result = await Swal.fire({
            title: 'Remove this auction?',
            text: "Auction #" + id + " will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4605',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Yes, Delete',
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

    // ============ NEGOTIATION ENGINE ============
    let currentNegotiationId = null;
    let currentTopBid = 0;
    const CSRF = '{{ csrf_token() }}';

    async function startNegotiation(auctionId) {
        // Start or fetch existing negotiation
        const res = await fetch(`/admin/negotiations/auction/${auctionId}/start`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();

        // Now load the full negotiation state
        await loadNegotiation(auctionId);
        document.getElementById('negotiationModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    async function loadNegotiation(auctionId) {
        const res = await fetch(`/admin/negotiations/auction/${auctionId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        const neg    = data.negotiation;
        currentTopBid = parseFloat(data.top_bid) || 0;
        currentNegotiationId = neg ? neg.id : null;

        document.getElementById('negRefCode').innerText   = 'REF: ' + (data.ref_code || '—');
        document.getElementById('negTopBid').innerText     = '$' + currentTopBid.toLocaleString();
        document.getElementById('negBidderName').innerText = data.bidder_name || '—';

        if (neg) {
            const offer  = parseFloat(neg.offer_to_lead) || 0;
            const profit = parseFloat(neg.profit_margin) || 0;
            document.getElementById('negOfferDisplay').innerText  = offer  ? '$' + offer.toLocaleString()  : '—';
            document.getElementById('negProfitDisplay').innerText = profit ? '$' + profit.toLocaleString() : '—';

            const statusColors = {
                pending:         'bg-amber-50 text-amber-600 border-amber-200',
                offer_sent:      'bg-blue-50 text-blue-600 border-blue-200',
                accepted:        'bg-emerald-50 text-emerald-600 border-emerald-200',
                rejected:        'bg-red-50 text-red-600 border-red-200',
                counter_offered: 'bg-purple-50 text-purple-600 border-purple-200',
            };
            const badge = document.getElementById('negStatusBadge');
            badge.className = 'px-3 py-1 rounded-full text-[0.6rem] font-black uppercase tracking-widest border ' + (statusColors[neg.status] || '');
            badge.innerText = neg.status.replace('_', ' ');

            // Show actions panel if offer was already sent
            if (['offer_sent', 'counter_offered'].includes(neg.status)) {
                document.getElementById('negActionsPanel').classList.remove('hidden');
                document.getElementById('negOfferForm').classList.add('hidden');
            } else if (neg.status === 'accepted' || neg.status === 'rejected') {
                document.getElementById('negActionsPanel').classList.add('hidden');
                document.getElementById('negOfferForm').classList.add('hidden');
            }
        }
    }

    document.getElementById('negOfferInput').addEventListener('input', function() {
        const offer  = parseFloat(this.value) || 0;
        const profit = currentTopBid - offer;
        document.getElementById('negProfitCalc').innerText = profit > 0
            ? `Profit: $${profit.toLocaleString()}`
            : (profit < 0 ? '⚠️ Offer exceeds bid!' : '');
    });

    async function sendNegotiationOffer() {
        const offer = document.getElementById('negOfferInput').value;
        const notes = document.getElementById('negNotes').value;
        if (!offer || !currentNegotiationId) return;
        const res = await fetch(`/admin/negotiations/${currentNegotiationId}/send-offer`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ offer_to_lead: offer, notes })
        });
        const data = await res.json();
        if (data.success) {
            window.notify?.success('Offer sent!');
            document.getElementById('negOfferForm').classList.add('hidden');
            document.getElementById('negActionsPanel').classList.remove('hidden');
            document.getElementById('negOfferDisplay').innerText = '$' + parseFloat(offer).toLocaleString();
            document.getElementById('negProfitDisplay').innerText = '$' + parseFloat(data.profit_margin).toLocaleString();
        }
    }

    async function acceptDeal() {
        if (!currentNegotiationId) return;
        const res = await fetch(`/admin/negotiations/${currentNegotiationId}/accept`, {
            method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (data.success) {
            window.notify?.success('Deal approved! Vehicle entering stock.');
            closeNegotiationModal();
            window.syncMatrix();
        }
    }

    async function rejectDeal() {
        if (!currentNegotiationId) return;
        const res = await fetch(`/admin/negotiations/${currentNegotiationId}/reject`, {
            method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF }
        });
        const data = await res.json();
        if (data.success) { window.notify?.info('Offer rejected.'); closeNegotiationModal(); }
    }

    async function recordCounterOffer() {
        const counter = document.getElementById('counterOfferInput').value;
        if (!counter || !currentNegotiationId) return;
        const res = await fetch(`/admin/negotiations/${currentNegotiationId}/counter-offer`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ counter_offer: counter })
        });
        const data = await res.json();
        if (data.success) { window.notify?.success('Counter-offer recorded: $' + parseFloat(counter).toLocaleString()); }
    }

    function closeNegotiationModal() {
        document.getElementById('negotiationModal').classList.add('hidden');
        // Reset form state
        document.getElementById('negOfferForm').classList.remove('hidden');
        document.getElementById('negActionsPanel').classList.add('hidden');
        document.getElementById('negOfferInput').value = '';
        document.getElementById('negNotes').value = '';
        document.getElementById('counterOfferInput').value = '';
        currentNegotiationId = null;
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

