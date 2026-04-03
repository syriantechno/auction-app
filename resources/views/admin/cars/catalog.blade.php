@extends('admin.layout')

@section('title', 'Master Inventory Catalog')

@section('content')
<script>
    // DEFINE FORMATTERS FIRST (High Priority for Tabulator Matrix)
    window.idFormatter = function(cell) { return `<span class="font-mono text-slate-300 font-black">#${cell.getValue()}</span>`; };
    window.statusFormatter = function(cell) { return `<span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[0.55rem] font-black rounded-full uppercase tracking-widest">Active Seed</span>`; };
    window.actionFormatter = function(cell) {
        const data = cell.getData();
        return `
            <div class="flex items-center gap-4 text-slate-300">
                <i data-lucide="eye" class="w-4 h-4 cursor-pointer hover:text-slate-900 transition-colors" onclick="viewCar(${data.id}, '${data.make}', '${data.model}', ${data.year})"></i>
                <i data-lucide="edit-3" class="w-4 h-4 cursor-pointer hover:text-[#ff6900] transition-colors" onclick="editCar(${data.id}, '${data.make}', '${data.model}', ${data.year})"></i>
                <i data-lucide="trash-2" class="w-4 h-4 cursor-pointer hover:text-red-500 transition-colors" onclick="deleteCar(${data.id})"></i>
            </div>
        `;
    };
</script>

<x-admin.data-matrix 
    id="catalog"
    title="Vehicle Catalog"
    subtitle="Enterprise Fleet Core Distribution"
    :apiRoute="route('admin.cars.catalog.api')"
    createButtonText="Append Car Row"
    createAction="openCatalogModal()"
    :stats="[
        ['label' => 'Total Seed Rows', 'value' => number_format($catalog['total_rows'] ?? 0), 'sub' => 'Active DB Assets'],
        ['label' => 'Unique Brands', 'value' => number_format($catalog['unique_makes'] ?? 0), 'sub' => 'Manufacturer Diversity'],
        ['label' => 'Asset Range', 'value' => ($catalog['min_year'] ?? '—') . ' - ' . ($catalog['max_year'] ?? '—'), 'sub' => 'Production Span']
    ]"
    :columns="[
        ['title' => 'Database ID', 'field' => 'id', 'width' => 120, 'hozAlign' => 'center', 'formatter' => 'idFormatter'],
        ['title' => 'Manufacturer', 'field' => 'make', 'widthGrow' => 2],
        ['title' => 'Model Designation', 'field' => 'model', 'widthGrow' => 2],
        ['title' => 'Prod. Year', 'field' => 'year', 'width' => 150, 'hozAlign' => 'center'],
        ['title' => 'Node Status', 'field' => 'status', 'width' => 150, 'hozAlign' => 'center', 'formatter' => 'statusFormatter'],
        ['title' => 'Action', 'field' => 'actions', 'width' => 150, 'hozAlign' => 'center', 'headerSort' => false, 'formatter' => 'actionFormatter']
    ]"
/>

<!-- Modal: CREATE/EDIT Car -->
<div id="catalogModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm animate-in fade-in duration-300">
    <div class="bg-white w-full max-w-md rounded-lg shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300">
        <div class="bg-[#1d293d] px-8 py-6 text-white flex items-center justify-between">
            <div>
                <h4 id="modalTitle" class="text-xs font-black uppercase tracking-[0.2em] italic">Seed Master Append</h4>
                <p class="text-[0.6rem] text-white/50 font-bold uppercase mt-1">Direct Database Row Management</p>
            </div>
            <button onclick="closeCatalogModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="p-8">
            <form id="ajaxCatalogForm" class="space-y-5">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" id="carId" name="id">
                
                <div>
                    <label class="block text-[0.6rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Manufacturer</label>
                    <input type="text" id="inputMake" name="make" required class="w-full bg-slate-50 border border-slate-100 rounded-lg px-4 py-3 text-sm font-bold outline-none focus:bg-white focus:border-orange-500 transition-all">
                </div>
                <div>
                    <label class="block text-[0.6rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Model Designation</label>
                    <input type="text" id="inputModel" name="model" required class="w-full bg-slate-50 border border-slate-100 rounded-lg px-4 py-3 text-sm font-bold outline-none focus:bg-white focus:border-orange-500 transition-all">
                </div>
                <div>
                    <label class="block text-[0.6rem] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">Production Year</label>
                    <input type="number" id="inputYear" name="year" required min="1950" max="{{ now()->year + 1 }}" class="w-full bg-slate-50 border border-slate-100 rounded-lg px-4 py-3 text-sm font-bold outline-none focus:bg-white focus:border-orange-500 transition-all">
                </div>

                <div id="modal-error" class="hidden p-4 bg-red-50 border border-red-100 text-red-600 text-xs font-bold rounded-lg animate-in shake duration-300"></div>

                <button type="submit" id="submitBtn" style="background: var(--primary-orange);" class="w-full text-white py-4 rounded-lg text-xs font-black uppercase tracking-widest shadow-xl shadow-orange-500/20 flex items-center justify-center gap-3 transition-all hover:scale-[1.02] active:scale-95">
                    <i data-lucide="database" class="w-4 h-4"></i> <span id="submitBtnText">Commit to Master</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal: VIEW Car -->
<div id="viewModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#1d293d]/40 backdrop-blur-sm animate-in fade-in duration-300">
    <div class="bg-white w-full max-w-sm rounded-lg shadow-2xl border border-slate-100 overflow-hidden animate-in zoom-in-95 duration-300">
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="info" class="w-8 h-8 text-slate-300"></i>
            </div>
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Asset Identity</h4>
            <h2 id="viewTitle" class="text-2xl font-black text-slate-900 tracking-tighter mb-6">Car Detail</h2>
            
            <div class="space-y-3 mb-8">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-md border border-slate-100">
                    <span class="text-[0.6rem] font-black uppercase text-slate-400">Database ID</span>
                    <span id="viewId" class="text-xs font-mono font-black text-slate-900"></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-md border border-slate-100">
                    <span class="text-[0.6rem] font-black uppercase text-slate-400">Production Year</span>
                    <span id="viewYear" class="text-xs font-black text-slate-900"></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-md border border-slate-100">
                    <span class="text-[0.6rem] font-black uppercase text-slate-400">System Status</span>
                    <span class="text-[0.6rem] font-black bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full uppercase">Live Seed</span>
                </div>
            </div>

            <button onclick="document.getElementById('viewModal').classList.add('hidden')" class="w-full bg-[#1d293d] text-white py-3.5 rounded-md text-[0.65rem] font-black uppercase tracking-widest hover:bg-black transition-all">
                Dismiss Inspector
            </button>
        </div>
    </div>
</div>

<script>
    function openCatalogModal() {
        document.getElementById('modalTitle').innerText = "Seed Master Append";
        document.getElementById('submitBtnText').innerText = "Commit to Master";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('carId').value = "";
        document.getElementById('ajaxCatalogForm').reset();
        document.getElementById('catalogModal').classList.remove('hidden');
    }

    function closeCatalogModal() { document.getElementById('catalogModal').classList.add('hidden'); }

    function viewCar(id, make, model, year) {
        document.getElementById('viewTitle').innerText = `${make} ${model}`;
        document.getElementById('viewId').innerText = `#${id}`;
        document.getElementById('viewYear').innerText = year;
        document.getElementById('viewModal').classList.remove('hidden');
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    }

    function editCar(id, make, model, year) {
        document.getElementById('modalTitle').innerText = "Update Seed Row";
        document.getElementById('submitBtnText').innerText = "Save Changes";
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('carId').value = id;
        document.getElementById('inputMake').value = make;
        document.getElementById('inputModel').value = model;
        document.getElementById('inputYear').value = year;
        document.getElementById('catalogModal').classList.remove('hidden');
    }

    async function deleteCar(id) {
        if (!confirm('Are you sure you want to purge this record from master catalog?')) return;
        
        try {
            const res = await fetch(`/admin/cars/catalog/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.success) {
                catalogTable.setData();
            } else { alert(data.message); }
        } catch (e) { alert('Purge failed execution.'); }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('ajaxCatalogForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('carId').value;
            const url = id ? `/admin/cars/catalog/${id}` : "{{ route('admin.car-catalog.store') }}";
            const btn = document.getElementById('submitBtn');
            const errorBox = document.getElementById('modal-error');

            btn.disabled = true;
            errorBox.classList.add('hidden');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    closeCatalogModal();
                    catalogTable.setData();
                } else { throw new Error(data.message || "Operation failed."); }
            } catch (err) {
                errorBox.innerText = err.message;
                errorBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
            }
        });
    });
</script>
@endsection

