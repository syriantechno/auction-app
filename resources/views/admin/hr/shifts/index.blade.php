@extends('admin.layout')

@section('title', 'Shift Manager')

@section('content')
<div x-data="shiftManager()" x-init="initComponent()">
    <x-admin-page-standard 
        icon="clock" 
        title="Shift" 
        highlight="Manager" 
        subtitle="Manage workforce schedules and operational shifts"
        dot="orange">
        
        <x-slot name="actions">
            <button @click="openCreate()" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                Add Shift
            </button>
        </x-slot>

        <!-- Tool & Search Hub -->
        <x-admin-filter-bar>
            <div class="relative flex-1 max-w-xs group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#ff6900] transition-colors z-10"></i>
                <input type="text" x-model="search" @input="fetchData()" placeholder="Search shifts..." class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg pl-11 pr-4 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
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
            alpineCount="shifts.length" 
            emptyTitle="No Shifts Defined" 
            emptySubtitle="The shift schedule is currently empty. Create your first shift to begin workforce scheduling." 
            emptyIcon="clock">
            
             <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-32">
                <template x-for="shift in shifts" :key="shift.id">
                    <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row shadow-sm hover:translate-y-[-2px]">
                        
                        <!-- Left: Visual Identity -->
                        <div class="w-full md:w-[160px] h-[150px] md:h-auto relative overflow-hidden shrink-0 bg-[#1d293d]">
                            <img src="{{ asset('images/section/department.jpg') }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">
                            
                            <div class="absolute top-4 left-4 z-30">
                                <span class="bg-[#ff6900] text-white text-[0.6rem] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-orange-500/30">
                                    Shift
                                </span>
                            </div>
                            
                            <div class="absolute bottom-4 left-4 right-4 z-30">
                                <div class="bg-[#1d293d]/60 backdrop-blur-md p-3 rounded-md border border-white/10 uppercase">
                                    <div class="text-[0.55rem] text-white/50 font-bold uppercase tracking-widest mb-1">Shift Code</div>
                                    <div class="text-xs font-black text-white font-mono" x-text="shift.code || ('SHIFT-'+String(shift.id).padStart(5, '0'))"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actionable Intelligence -->
                        <div class="flex-1 p-5 flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-black text-[#031629] leading-none uppercase italic" x-text="shift.name"></h3>
                                        <p class="text-[0.7rem] font-bold text-slate-400 mt-2 uppercase tracking-wide italic" x-text="shift.working_hours + ' Hours Shift'"></p>
                                    </div>
                                    <div class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-[#ff6900] transition-colors">
                                        <i data-lucide="clock" class="w-6 h-6"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Operating Window</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-slate-700 truncate block font-mono" x-text="shift.start_time + ' - ' + shift.end_time"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Status</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="activity" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-[#031629] uppercase italic tracking-tighter" x-text="shift.is_active ? 'ONLINE' : 'OFFLINE'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <button @click="openEdit(shift)" class="group flex-1 px-6 py-3.5 rounded-lg font-black text-[0.65rem] uppercase tracking-[0.15em] transition-all duration-300 flex items-center justify-center gap-2.5 bg-slate-900 hover:bg-[#ff6900] text-white shadow-lg shadow-slate-200/50">
                                    <i data-lucide="pencil" class="w-4 h-4 transition-transform duration-500 group-hover:scale-110"></i>
                                    <span>Edit Shift</span>
                                </button>
                                
                                <button @click="deleteTask(shift.id)" class="group w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-xl active:scale-90 bg-slate-900 hover:bg-red-500 text-white">
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
    <x-admin-modal x-show="opened" title="Shift Details" icon="clock" size="max-w-4xl">
        <div class="col-span-12 grid grid-cols-12 gap-0 overflow-hidden rounded-2xl">
            <!-- Left Panel: Main Info (5/12) - Times & Breaks -->
            <div class="col-span-12 lg:col-span-5 p-10 space-y-8 bg-slate-50/50 border-r border-slate-200">
                <div class="space-y-4">
                    <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest ml-1 block">Shift Name</label>
                    <div class="bg-white p-6 rounded-2xl border-2 border-[#ff6900] shadow-xl shadow-orange-500/5 transition-all group-focus-within:border-[#ff6900]/60">
                        <input type="text" x-model="form.name" required placeholder="e.g. Morning Shift..." 
                               class="w-full bg-transparent text-2xl font-black text-slate-800 placeholder:text-slate-200 outline-none italic truncate uppercase tracking-tighter">
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                            <span class="px-2.5 py-1 bg-slate-900 text-[#ff6900] text-[0.55rem] font-black rounded uppercase tracking-widest" x-text="form.code || 'SHIFT-00'"></span>
                            <span class="text-[0.55rem] font-bold text-slate-400 uppercase italic">Shift Code</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Start Time</label>
                            <input type="time" x-model="form.start_time" required class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl px-5 text-[0.9rem] font-black text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">End Time</label>
                            <input type="time" x-model="form.end_time" required class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl px-5 text-[0.9rem] font-black text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Break Start</label>
                            <input type="time" x-model="form.break_start" class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl px-5 text-[0.9rem] font-black text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Break End</label>
                            <input type="time" x-model="form.break_end" class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl px-5 text-[0.9rem] font-black text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Shift Details (7/12) - Working Hours & Status -->
            <div class="col-span-12 lg:col-span-7 p-10 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-widest ml-1">Working Hours (Auto)</label>
                        <input type="number" step="0.5" x-model="form.working_hours" 
                               class="w-full h-[52px] bg-white border-2 border-slate-100 rounded-xl px-5 text-[0.9rem] font-black text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm" placeholder="8">
                    </div>
                    <div class="space-y-2 flex flex-col justify-end">
                        <button type="button" @click="calculateHours()" class="h-[52px] bg-slate-100 hover:bg-[#ff6900] hover:text-white rounded-xl text-slate-600 font-bold text-[0.75rem] uppercase tracking-wider transition-all">
                            Calculate
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-[#ff6900]">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <span class="text-[0.75rem] font-bold text-slate-800 italic block leading-none" x-text="form.is_active ? 'Active' : 'Inactive'"></span>
                        <span class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-1 block">Current Status</span>
                    </div>
                    <label class="relative flex-shrink-0 cursor-pointer">
                        <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                        <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-all
                                    after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                    peer-checked:after:translate-x-5"></div>
                    </label>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="bg-slate-50 p-6 rounded-xl">
                        <h4 class="text-[0.7rem] font-black uppercase text-slate-400 tracking-widest mb-4">Shift Summary</h4>
                        <div class="space-y-3 text-[0.8rem]">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Operating Window:</span>
                                <span class="font-bold text-slate-800 font-mono" x-text="(form.start_time || '--:--') + ' - ' + (form.end_time || '--:--')"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Break Duration:</span>
                                <span class="font-bold text-slate-800 font-mono" x-text="(form.break_start && form.break_end) ? (form.break_start + ' - ' + form.break_end) : 'No Break'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Hours:</span>
                                <span class="font-bold text-[#ff6900]" x-text="form.working_hours + ' Hours'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-admin-modal>

</div>

<script>
    function shiftManager() {
        return {
            opened: false, isEdit: false, loading: false,
            shifts: [], search: '', statusFilter: '',
            form: { id: '', name: '', code: '', start_time: '', end_time: '', working_hours: 8, break_start: '', break_end: '', is_active: true },

            initComponent() {
                this.fetchData();
                this.$nextTick(() => { this.refreshIcons(); });
                this.$watch('form.start_time', () => this.calculateHours());
                this.$watch('form.end_time', () => this.calculateHours());
            },

            calculateHours() {
                if (this.form.start_time && this.form.end_time) {
                    const start = new Date('2000-01-01T' + this.form.start_time);
                    const end = new Date('2000-01-01T' + this.form.end_time);
                    if (end > start) {
                        const diffMs = end - start;
                        const diffHours = diffMs / (1000 * 60 * 60);
                        this.form.working_hours = Math.round(diffHours * 2) / 2; // Round to nearest 0.5
                    }
                }
            },

            refreshIcons() { if (typeof lucide !== 'undefined') { lucide.createIcons(); } },

            resetFilters() {
                this.search = '';
                this.statusFilter = '';
                this.fetchData();
            },

            async fetchData() {
                let url = `{{ route('admin.hr.shifts.index') }}?search=${this.search}`;
                if (this.statusFilter !== '') url += `&is_active=${this.statusFilter}`;
                
                const r = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d = await r.json();
                this.shifts = Array.isArray(d) ? d : (d.data?.data || d.data || []);
                await this.$nextTick();
                this.refreshIcons();
            },

            openCreate() {
                this.isEdit = false;
                this.form = { id: '', name: '', code: '', start_time: '', end_time: '', working_hours: 8, break_start: '', break_end: '', is_active: true };
                this.opened = true;
            },

            openEdit(shift) {
                this.isEdit = true;
                this.form = { ...shift, is_active: !!shift.is_active };
                this.opened = true;
            },

            async submit() {
                const url = this.isEdit ? `/admin/hr/shifts/${this.form.id}` : '/admin/hr/shifts';
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
                    title: 'Delete Shift?',
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
                    const r = await fetch(`/admin/hr/shifts/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    const d = await r.json();
                    if(r.ok && d.success) {
                        window.showToast(d.message, 'success');
                        this.fetchData();
                    } else {
                        window.showToast(d.message || 'Cannot delete shift with assigned employees.', 'error');
                    }
                } catch(e) { window.showToast('Decommission failed', 'error'); }
            }
        }
    }
</script>
@endsection
