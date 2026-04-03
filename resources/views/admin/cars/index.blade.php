@extends('admin.layout')

@section('title', 'Vehicle Asset Inventory')

@section('content')
<div class="px-1 space-y-5 animate-in fade-in duration-500">
    <!-- Header: Refined Lean Typography -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-medium text-slate-800 tracking-tighter italic">Vehicles</h1>
            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
            <p class="text-[0.65rem] text-slate-500 font-medium uppercase tracking-[0.2em] hidden md:block">Vehicle Catalog</p>
        </div>
        <div class="flex items-center gap-3">
             <div class="flex items-center gap-3 bg-white px-5 py-2.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="text-[0.6rem] font-medium uppercase text-slate-400 tracking-widest">Total Vehicles:</span>
                <span id="totalCounter" class="text-sm font-medium text-slate-800 tabular-nums">{{ number_format(\App\Models\Car::count()) }}</span>
            </div>
            <button onclick="openCreateModal()" style="background: var(--primary-orange);" class="px-6 h-[44px] text-white rounded-lg font-medium shadow-lg shadow-orange-500/10 hover:scale-[1.02] active:scale-95 transition-all text-[0.65rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-white/80"></i> Add Vehicle
            </button>
        </div>
    </div>

    <!-- Unified Lean Hub (Normal Thickness) -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <form id="filterForm" action="{{ route('admin.cars.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="text-[0.65rem] font-medium text-slate-600 uppercase tracking-widest mb-1.5 block ml-1">Search</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by make or model..." class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md pl-11 pr-4 py-2 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all shadow-sm">
                </div>
            </div>
            <div class="w-48">
                <label class="text-[0.65rem] font-medium text-slate-600 uppercase tracking-widest mb-1.5 block ml-1">Manufacturer</label>
                <div class="relative">
                    <select name="make" id="makeFilter" class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.9rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                        <option value="">All Brands</option>
                        @foreach(($makeOptions ?? []) as $make)
                            <option value="{{ $make }}" {{ request('make') == $make ? 'selected' : '' }}>{{ $make }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            <div class="w-32">
                <label class="text-[0.65rem] font-medium text-slate-600 uppercase tracking-widest mb-1.5 block ml-1">Year</label>
                <div class="relative">
                    <select name="year" id="yearFilter" class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.9rem] font-normal text-slate-700 appearance-none outline-none focus:bg-white focus:border-orange-500/40 transition-all">
                        <option value="">All Years</option>
                        @foreach(\App\Models\Car::distinct()->orderBy('year', 'desc')->pluck('year') as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
            </div>
            
            <button type="button" id="resetBtn" class="bg-slate-100 h-[44px] border border-slate-300 text-slate-700 rounded-md px-5 py-2 text-[0.65rem] font-medium uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2 shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-400"></i> Reset
            </button>
            
            <div class="relative flex items-center justify-center bg-slate-800 h-[44px] border border-slate-700 rounded-md px-4 py-2 overflow-hidden min-w-[5rem]">
                <div id="loadingIndicator" class="hidden flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 bg-[#ff6900] rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-[#ff6900] rounded-full animate-bounce delay-75"></div>
                </div>
                <span id="readyText" class="text-[0.55rem] font-medium uppercase tracking-[0.2em] text-slate-400">Ready</span>
            </div>
        </form>
    </div>

    <!-- Lean Density Matrix: Perfect Scale & Thin Typography -->
    <div id="tableContainer" class="relative">
        <div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest w-24 text-center">Logo</th>
                        <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest">Vehicle</th>
                        <th class="py-3 px-6 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-center">Year</th>
                        <th class="py-3 px-8 text-[0.65rem] text-slate-500 font-medium uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    @forelse($cars as $car)
                    <tr class="group hover:bg-slate-50/50 transition-all duration-200 border-l-4 border-l-transparent hover:border-l-orange-500">
                        <td class="py-3 px-8">
                            <div class="w-11 h-11 rounded-md overflow-hidden bg-white border border-slate-200 shadow-sm flex items-center justify-center mx-auto transition-all group-hover:scale-110 duration-300 p-2">
                                <img src="{{ $car->logo_url }}" class="w-full h-auto max-h-full object-contain group-hover:drop-shadow-sm transition-all" alt="{{ $car->make }}">
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-4">
                                <span class="text-[0.95rem] font-normal text-slate-700 tracking-tight leading-none group-hover:text-orange-600 transition-colors">{{ $car->brand?->name ?? $car->make }} {{ $car->carModel?->name ?? $car->model }}</span>
                                <span class="bg-slate-50 text-slate-400 px-2.5 py-1 rounded-lg text-[0.55rem] font-mono font-medium uppercase border border-slate-200">#{{ $car->id }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="inline-block px-3.5 py-1 bg-white border border-slate-200 rounded-full text-[0.7rem] font-medium text-slate-600">{{ $car->year }}</span>
                        </td>
                        <td class="py-3 px-8 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                <button onclick="viewCarDetail({{ $car->id }}, @json($car->brand?->name ?? $car->make), @json($car->carModel?->name ?? $car->model), {{ $car->year }}, @json($car->image_url))" title="Inspector" class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                    <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                                </button>
                                <button onclick="editCar({{ $car->id }}, @json($car->brand?->name ?? $car->make), @json($car->carModel?->name ?? $car->model), {{ $car->year }})" title="Edit Row" class="w-9 h-9 rounded-md bg-white text-slate-400 flex items-center justify-center hover:bg-slate-800 hover:text-white transition-all shadow-md border border-slate-200 active:scale-95">
                                    <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                </button>
                                <button onclick="deleteCar({{ $car->id }})" title="Purge" class="w-9 h-9 rounded-md bg-white text-red-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-md border border-red-50 active:scale-95">
                                    <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-20 text-center bg-slate-50">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                                    <i data-lucide="database" class="w-8 text-slate-200"></i>
                                </div>
                                <h3 class="text-xs font-medium text-slate-400 uppercase tracking-widest">No vehicles found</h3>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Professional Paging Navigator (Normal Weight) -->
            <div id="paginationContainer">
                @if($cars->hasPages())
                <div class="bg-slate-50 px-10 py-8 border-t border-slate-200 flex items-center justify-center">
                    {{ $cars->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal: Lean Logic -->
<div id="carModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#1d293d]/30 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-lg shadow-2xl border border-slate-200 overflow-hidden animate-in zoom-in-95 duration-200">
        <div class="bg-slate-800 px-8 py-6 text-white/90 flex items-center justify-between">
            <div>
                <h4 id="modalTitle" class="text-[0.65rem] font-medium uppercase tracking-[0.2em]">Add Vehicle</h4>
                <p class="text-[0.55rem] text-white/40 font-normal uppercase mt-1">Vehicle Catalog</p>
            </div>
            <button onclick="closeCarModal()" class="w-8 h-8 rounded-md bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all border border-white/10">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-8 space-y-6">
            <form id="ajaxCarForm" class="space-y-6">
                @csrf
                <input type="hidden" id="carId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Make</label>
                    <input type="text" id="inputMake" name="make" required class="w-full bg-slate-50 border border-slate-300 rounded-md px-5 py-3.5 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all shadow-inner">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Model</label>
                    <input type="text" id="inputModel" name="model" required class="w-full bg-slate-50 border border-slate-300 rounded-md px-5 py-3.5 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all shadow-inner">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Year</label>
                    <input type="number" id="inputYear" name="year" required min="1950" max="{{ now()->year + 1 }}" class="w-full bg-slate-50 border border-slate-300 rounded-md px-5 py-3.5 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all shadow-inner">
                </div>

                <div id="modal-error" class="hidden p-4 bg-red-50 border border-red-100 text-red-500 text-[0.7rem] font-medium rounded-md"></div>

                <button type="submit" id="submitBtn" style="background: var(--primary-orange);" class="w-full text-white py-4.5 rounded-lg text-[0.7rem] font-medium uppercase tracking-[0.2em] shadow-xl shadow-orange-500/10 flex items-center justify-center gap-2 transition-all hover:scale-[1.01] active:scale-98">
                    <i data-lucide="save" class="w-4 h-4 text-white/80"></i> <span id="submitBtnText">Save Vehicle</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Lean Vision Inspector -->
<div id="viewModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#1d293d]/20 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white w-full max-w-[320px] rounded-lg shadow-2xl border border-slate-200 overflow-hidden animate-in zoom-in-95 duration-200">
        <div class="relative h-44 bg-slate-100 flex items-center justify-center overflow-hidden">
             <img id="viewImg" src="" class="w-full h-full object-cover">
             <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent"></div>
             <button onclick="closeViewModal()" class="absolute top-3 right-3 w-8 h-8 rounded-lg bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="absolute bottom-4 left-6">
                <span id="viewId" class="bg-[#ff6900]/90 text-white px-2 py-0.5 rounded text-[0.5rem] font-medium uppercase tracking-widest">Vehicle</span>
                <h3 id="viewTitle" class="text-white text-lg font-normal italic mt-1 tracking-tighter">Vehicle Name</h3>
            </div>
        </div>
        <div class="p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex flex-col">
                    <span class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Status</span>
                    <span class="text-[0.75rem] font-normal text-slate-700 uppercase mt-1 italic">Active</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[0.55rem] font-medium text-slate-400 uppercase tracking-widest">Year</span>
                    <span id="viewYear" class="text-[0.75rem] font-normal text-slate-700 mt-1">2024</span>
                </div>
            </div>
            <button onclick="closeViewModal()" class="w-full py-3 bg-slate-800 text-white/90 rounded-md text-[0.65rem] font-medium uppercase tracking-widest hover:bg-slate-700 transition-all">Close</button>
        </div>
    </div>
</div>

<script>
    // MATRIX FRAGMENTATION ENGINE: NORMAL WEIGHT SYNC
    document.addEventListener('DOMContentLoaded', function() {
        const resetBtn = document.getElementById('resetBtn');
        const form = document.getElementById('filterForm');
        const searchInput = document.getElementById('searchInput');
        let searchTimeout = null;

        const filters = ['searchInput', 'makeFilter', 'yearFilter'];
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
            document.getElementById('makeFilter').selectedIndex = 0;
            document.getElementById('yearFilter').selectedIndex = 0;
            syncMatrix();
        });

        document.addEventListener('click', (e) => {
            const link = e.target.closest('#paginationContainer a');
            if (link) {
                e.preventDefault();
                syncMatrix(new URL(link.href));
            }
        });

        async function syncMatrix(targetUrl = null) {
            const rowBody = document.getElementById('tableBody');
            const pagBox = document.getElementById('paginationContainer');
            const loader = document.getElementById('loadingIndicator');
            const ready = document.getElementById('readyText');

            const url = targetUrl || new URL(form.action);
            if(!targetUrl) {
                const fd = new FormData(form);
                for (let [k,v] of fd) { if(v) url.searchParams.set(k,v); }
            }
            
            loader.classList.remove('hidden'); ready.classList.add('hidden');
            rowBody.style.opacity = '0.5';

            try {
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                
                rowBody.innerHTML = doc.getElementById('tableBody').innerHTML;
                pagBox.innerHTML = doc.getElementById('paginationContainer').innerHTML;
                
                if (typeof lucide !== 'undefined') lucide.createIcons();
                window.history.pushState({}, '', url.toString());
            } catch (err) { console.error("Sync Error", err); }
            finally {
                loader.classList.add('hidden'); ready.classList.remove('hidden');
                rowBody.style.opacity = '1';
            }
        }

        document.getElementById('ajaxCarForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('carId').value;
            const url = id ? `/admin/cars/catalog/${id}` : "{{ route('admin.car-catalog.store') }}";
            const btn = document.getElementById('submitBtn'); btn.disabled = true;

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: new FormData(this)
                });
                const data = await res.json();
                if (data.success) { closeCarModal(); syncMatrix(); } 
                else { throw new Error(data.message || "Protocol Failure"); }
            } catch (err) {
                const eb = document.getElementById('modal-error');
                eb.innerText = err.message; eb.classList.remove('hidden');
            } finally { btn.disabled = false; }
        });
    });

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = "Add Vehicle";
        document.getElementById('submitBtnText').innerText = "Save Vehicle";
        document.getElementById('carId').value = "";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('ajaxCarForm').reset();
        document.getElementById('carModal').classList.remove('hidden');
    }

    function editCar(id, make, model, year) {
        document.getElementById('modalTitle').innerText = "Edit Vehicle";
        document.getElementById('submitBtnText').innerText = "Update Vehicle";
        document.getElementById('carId').value = id;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('inputMake').value = make;
        document.getElementById('inputModel').value = model;
        document.getElementById('inputYear').value = year;
        document.getElementById('carModal').classList.remove('hidden');
    }

    function viewCarDetail(id, make, model, year, img) {
        document.getElementById('viewTitle').innerText = make + ' ' + model;
        document.getElementById('viewYear').innerText = year;
        document.getElementById('viewId').innerText = 'Vehicle #' + id;
        document.getElementById('viewImg').src = img || '';
        document.getElementById('viewModal').classList.remove('hidden');
    }

    async function deleteCar(id) {
        const result = await Swal.fire({
            title: 'Delete this vehicle?',
            text: "This action cannot be undone. Vehicle #" + id + " will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4605',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Yes, Delete',
            customClass: { popup: 'rounded-lg' }
        });

        if (result.isConfirmed) {
            try {
                const res = await fetch(`/admin/cars/catalog/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if(data.success) {
                    window.notify.success(data.message || 'Vehicle deleted');
                    syncMatrix();
                }
            } catch (err) { window.notify.error("Failed to delete vehicle"); }
        }
    }

    function closeCarModal() { document.getElementById('carModal').classList.add('hidden'); }
    function closeViewModal() { document.getElementById('viewModal').classList.add('hidden'); }
</script>

<style>
    /* Lean Pagination Navigator */
    .pagination { @apply flex items-center gap-1.5 mt-0 MB-0; }
    .page-item .page-link { 
        @apply w-10 h-10 rounded-md flex items-center justify-center border-none bg-white text-slate-400 font-medium text-[0.7rem] transition-all shadow-sm; 
    }
    .page-item.active .page-link { @apply bg-slate-800 text-white shadow-lg; }
    .page-item .page-link:hover { @apply bg-[#ff6900] text-white; }
</style>
@endsection
