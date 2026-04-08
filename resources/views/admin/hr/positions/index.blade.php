@extends('admin.layout')

@section('title', 'Positions')

@section('content')
<div x-data="positionManager()" x-init="initComponent()">
    <x-admin-page-standard 
        icon="briefcase" 
        title="Position" 
        highlight="Manager" 
        subtitle="Manage corporate roles and operational responsibilities"
        dot="orange">
        
        <x-slot name="actions">
            <button @click="openCreate()" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                Add Position
            </button>
        </x-slot>

        <!-- Tool & Search Hub -->
        <x-admin-filter-bar>
            <div class="relative flex-1 max-w-xs group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#ff6900] transition-colors z-10"></i>
                <input type="text" x-model="search" @input="fetchData()" placeholder="Search positions..." class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg pl-11 pr-4 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
            </div>
            
            <div class="w-44 relative z-0">
                <select x-model="departmentFilter" @change="fetchData()" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg px-3 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm cursor-pointer">
                    <option value="">All Departments</option>
                    <template x-for="d in departments" :key="d.id">
                        <option :value="d.id" x-text="d.name"></option>
                    </template>
                </select>
            </div>
            
            <div class="w-36 relative z-0">
                <select x-model="statusFilter" @change="fetchData()" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg px-3 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm cursor-pointer">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            
            <button @click="resetFilters()" class="h-10 px-4 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 transition-colors flex items-center gap-2 text-[0.75rem] font-bold">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset
            </button>
        </x-admin-filter-bar>

        <!-- Intelligent Data View -->
        <x-admin-data-view 
            alpine 
            alpineCount="positions.length" 
            emptyTitle="No Positions Defined" 
            emptySubtitle="The position hierarchy is currently empty. Create your first position to begin workforce mapping." 
            emptyIcon="briefcase">
            
             <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-32">
                <template x-for="pos in positions" :key="pos.id">
                    <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row shadow-sm hover:translate-y-[-2px]">
                        
                        <!-- Left: Visual Identity -->
                        <div class="w-full md:w-[160px] h-[150px] md:h-auto relative overflow-hidden shrink-0 bg-[#1d293d]">
                            <img src="{{ asset('images/section/department.jpg') }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">
                            
                            <div class="absolute top-4 left-4 z-30">
                                <span class="bg-[#ff6900] text-white text-[0.6rem] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-orange-500/30">
                                    Position
                                </span>
                            </div>
                            
                            <div class="absolute bottom-4 left-4 right-4 z-30">
                                <div class="bg-[#1d293d]/60 backdrop-blur-md p-3 rounded-md border border-white/10 uppercase">
                                    <div class="text-[0.55rem] text-white/50 font-bold uppercase tracking-widest mb-1">Ref Code</div>
                                    <div class="text-xs font-black text-white font-mono" x-text="pos.code || ('POS-'+String(pos.id).padStart(5, '0'))"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actionable Intelligence -->
                        <div class="flex-1 p-5 flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-black text-[#031629] leading-none uppercase italic" x-text="pos.name"></h3>
                                        <p class="text-[0.7rem] font-bold text-slate-400 mt-2 uppercase tracking-wide italic" x-text="(pos.department ? pos.department.name : 'Unassigned') + ' Department'"></p>
                                    </div>
                                    <div class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-[#ff6900] transition-colors">
                                        <i data-lucide="briefcase" class="w-6 h-6"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Headcount</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="users" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-slate-700 truncate block" x-text="(pos.employees_count || 0) + ' Members'"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Status</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="activity" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-[#031629] uppercase italic tracking-tighter" x-text="pos.is_active ? 'ACTIVE' : 'INACTIVE'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <button @click="openEdit(pos)" class="group flex-1 px-6 py-3.5 rounded-lg font-black text-[0.65rem] uppercase tracking-[0.15em] transition-all duration-300 flex items-center justify-center gap-2.5 bg-slate-900 hover:bg-[#ff6900] text-white shadow-lg shadow-slate-200/50">
                                    <i data-lucide="pencil" class="w-4 h-4 transition-transform duration-500 group-hover:scale-110"></i>
                                    <span>Edit Position</span>
                                </button>
                                
                                <button @click="deleteTask(pos.id)" class="group w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-xl active:scale-90 bg-slate-900 hover:bg-red-500 text-white">
                                    <i data-lucide="trash-2" class="w-5 h-5 transition-transform duration-500 group-hover:scale-110"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

        </x-admin-data-view>
    </x-admin-page-standard>

    <!-- OFFICIAL MODAL CONFIG -->
    <x-admin-modal x-show="opened" title="Position Details" icon="briefcase" size="max-w-4xl">
        <div class="col-span-12 grid grid-cols-12 gap-0 overflow-hidden rounded-2xl">
            <!-- Left Panel: Main Info (5/12) -->
            <div class="col-span-12 lg:col-span-5 p-10 space-y-8 bg-slate-50/50 border-r border-slate-200">
                <div class="space-y-4">
                    <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest ml-1 block">Position Name</label>
                    <div class="bg-white p-6 rounded-2xl border-2 border-[#ff6900] shadow-xl shadow-orange-500/5 transition-all group-focus-within:border-[#ff6900]/60">
                        <input type="text" x-model="form.name" required placeholder="e.g. Manager, Developer..." 
                               class="w-full bg-transparent text-2xl font-black text-slate-800 placeholder:text-slate-200 outline-none italic truncate uppercase tracking-tighter">
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                            <span class="px-2.5 py-1 bg-slate-900 text-[#ff6900] text-[0.55rem] font-black rounded uppercase tracking-widest" x-text="form.code || 'POS-00'"></span>
                            <span class="text-[0.55rem] font-bold text-slate-400 uppercase italic">Position Code</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-4">
                    <x-elite-select label="Parent Department" icon="layers" x-model="form.department_id">
                        <option value="">Select Department...</option>
                        <template x-for="d in departments" :key="d.id">
                            <option :value="d.id" x-text="d.name"></option>
                        </template>
                    </x-elite-select>

                    <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-100 shadow-sm transition-all hover:border-[#ff6900]/20">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-[#ff6900]">
                            <i data-lucide="activity" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-[0.75rem] font-bold text-slate-800 italic block leading-none" x-text="form.is_active ? 'Active' : 'Inactive'"></span>
                            <span class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-1 block">Current Status</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Position Details (7/12) -->
            <div class="col-span-12 lg:col-span-7 p-10 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2 col-span-2">
                        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Benchmark Salary</label>
                        <div class="relative group">
                            <i data-lucide="dollar-sign" class="w-5 h-5 absolute left-5 top-1/2 -translate-y-1/2 text-[#ff6900] transition-transform group-hover:scale-110"></i>
                            <input type="number" x-model="form.default_salary" class="w-full h-[58px] bg-slate-50 border-2 border-slate-100 rounded-xl pl-14 pr-5 text-[1.1rem] font-black text-slate-900 outline-none focus:border-orange-500/40 transition-all shadow-sm" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block">Responsibilities</label>
                    <textarea x-model="form.description" rows="4" placeholder="Enter position responsibilities and duties..." 
                        class="w-full bg-slate-50 border-2 border-slate-100/50 rounded-xl px-6 py-5 text-[0.85rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 focus:bg-white focus:ring-[8px] focus:ring-orange-500/5 transition-all shadow-sm placeholder:text-slate-300 placeholder:italic"></textarea>
                </div>

                <div class="pt-8 border-t border-slate-100 flex items-center justify-between group">
                    <div class="space-y-1">
                        <span class="text-[0.6rem] font-black uppercase text-slate-900 tracking-widest block italic">Position Status</span>
                        <span class="text-[0.5rem] text-slate-400 font-bold uppercase tracking-[0.1em]">Toggle to activate/deactivate the position</span>
                    </div>
                    <div class="flex items-center gap-6">
                         <span class="text-[0.65rem] font-black uppercase text-[#ff6900] italic" x-text="form.is_active ? 'ACTIVE' : 'STANDBY'"></span>
                         <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-all after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                         </label>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-modal>

</div>

<script>
    function positionManager() {
        return {
            opened: false, isEdit: false, loading: false,
            positions: [], search: '', departmentFilter: '', statusFilter: '',
            departments: @json($departments ?? []),
            form: { id: '', name: '', code: '', description: '', department_id: '', default_salary: '', is_active: true },

            initComponent() {
                this.fetchData();
                this.$nextTick(() => { this.refreshIcons(); });
            },

            refreshIcons() { if (typeof lucide !== 'undefined') { lucide.createIcons(); } },

            resetFilters() {
                this.search = '';
                this.departmentFilter = '';
                this.statusFilter = '';
                this.fetchData();
            },

            async fetchData() {
                let url = `{{ route('admin.hr.positions.index') }}?search=${this.search}`;
                if (this.departmentFilter) url += `&department_id=${this.departmentFilter}`;
                if (this.statusFilter !== '') url += `&is_active=${this.statusFilter}`;
                
                const r = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d = await r.json();
                this.positions = Array.isArray(d) ? d : (d.data?.data || d.data || []);
                await this.$nextTick();
                this.refreshIcons();
            },

            openCreate() {
                this.isEdit = false;
                this.form = { id: '', name: '', code: '', description: '', department_id: '', default_salary: '', is_active: true };
                this.opened = true;
            },

            openEdit(pos) {
                this.isEdit = true;
                this.form = { ...pos, is_active: !!pos.is_active };
                this.opened = true;
            },

            async submit() {
                const url = this.isEdit ? `/admin/hr/positions/${this.form.id}` : '/admin/hr/positions';
                const method = this.isEdit ? 'PUT' : 'POST';
                this.loading = true;
                try {
                    const r = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ ...this.form, _method: method })
                    });
                    const d = await r.json();
                    if(d.success) {
                        window.showToast(d.message, 'success');
                        this.opened = false;
                        this.fetchData();
                    } else {
                        window.showToast(d.message || 'Validation Error', 'error');
                    }
                } catch(e) { window.showToast('Server precision failed', 'error'); }
                finally { this.loading = false; }
            },

            async deleteTask(id) {
                const confirmed = await window.Swal.fire({
                    title: 'Delete Position?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                });
                
                if (!confirmed.isConfirmed) return;
                
                try {
                    const r = await fetch(`/admin/hr/positions/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    const d = await r.json();
                    if(r.ok && d.success) {
                        window.showToast(d.message, 'success');
                        this.fetchData();
                    } else {
                        window.showToast(d.message || 'Cannot delete position with active employees.', 'error');
                    }
                } catch(e) { window.showToast('Decommission failed', 'error'); }
            }
        }
    }
</script>
@endsection
