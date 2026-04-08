<?php $__env->startSection('title', 'Departments'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="departmentManager()" x-init="initComponent()">
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'layers','title' => 'Department','highlight' => 'Manager','subtitle' => 'Organize and manage your company departments efficiently','dot' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'layers','title' => 'Department','highlight' => 'Manager','subtitle' => 'Organize and manage your company departments efficiently','dot' => 'emerald']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <button @click="openCreate()" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                Initialize Unit
            </button>
         <?php $__env->endSlot(); ?>

        
        <!-- Tool & Search Hub -->
        <?php if (isset($component)) { $__componentOriginal6b29dae6f7a16af6949451a73d5dcc44 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-filter-bar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-filter-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="relative flex-1 max-w-xs group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-[#ff6900] transition-colors z-10"></i>
                <input type="text" x-model="search" @input="fetchData()" placeholder="Search departments..." class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg pl-11 pr-4 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
            </div>
            
            <div class="w-40 relative z-0">
                <select x-model="companyFilter" @change="fetchData()" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg px-3 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm cursor-pointer">
                    <option value="">All Companies</option>
                    <template x-for="c in companies" :key="c.id">
                        <option :value="c.id" x-text="c.name"></option>
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
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44)): ?>
<?php $attributes = $__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44; ?>
<?php unset($__attributesOriginal6b29dae6f7a16af6949451a73d5dcc44); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6b29dae6f7a16af6949451a73d5dcc44)): ?>
<?php $component = $__componentOriginal6b29dae6f7a16af6949451a73d5dcc44; ?>
<?php unset($__componentOriginal6b29dae6f7a16af6949451a73d5dcc44); ?>
<?php endif; ?>

        <!-- Intelligent Data View -->
        <?php if (isset($component)) { $__componentOriginalf5a744a70ee982199708c37a824ad023 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf5a744a70ee982199708c37a824ad023 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-view','data' => ['alpine' => true,'alpineCount' => 'departments.length','emptyTitle' => 'No Units Defined','emptySubtitle' => 'The organizational map is currently empty. Initialize your first node to begin hierarchy mapping.','emptyIcon' => 'layers']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['alpine' => true,'alpineCount' => 'departments.length','emptyTitle' => 'No Units Defined','emptySubtitle' => 'The organizational map is currently empty. Initialize your first node to begin hierarchy mapping.','emptyIcon' => 'layers']); ?>
            
             <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8 pb-32">
                <template x-for="dept in departments" :key="dept.id">
                    <div class="group bg-white border border-slate-100 rounded-2xl overflow-hidden hover:shadow-2xl hover:shadow-orange-500/5 transition-all duration-500 flex flex-col md:flex-row shadow-sm hover:translate-y-[-2px]">
                        
                        <!-- Left: Visual Identity -->
                        <div class="w-full md:w-[160px] h-[150px] md:h-auto relative overflow-hidden shrink-0 bg-[#1d293d]">
                            <img src="<?php echo e(asset('images/section/department.jpg')); ?>" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 brightness-110 saturate-110 opacity-70">
                            
                            <div class="absolute top-4 left-4 z-30">
                                <span class="bg-[#ff6900] text-white text-[0.6rem] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-orange-500/30">
                                    Dept Node
                                </span>
                            </div>
                            
                            <div class="absolute bottom-4 left-4 right-4 z-30">
                                <div class="bg-[#1d293d]/60 backdrop-blur-md p-3 rounded-md border border-white/10 uppercase">
                                    <div class="text-[0.55rem] text-white/50 font-bold uppercase tracking-widest mb-1">Asset Reference</div>
                                    <div class="text-xs font-black text-white font-mono" x-text="'#' + (dept.code ? dept.code.replace('DEPT-', '') : String(dept.id).padStart(5, '0'))"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Actionable Intelligence -->
                        <div class="flex-1 p-5 flex flex-col justify-between gap-4">
                            <div>
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-2xl font-black text-[#031629] leading-none uppercase italic">
                                            <span x-text="dept.name.split(' ').slice(0, -1).join(' ')"></span>
                                            <span class="text-[#ff6900]" x-text="dept.name.split(' ').pop()"></span>
                                        </h3>
                                        <p class="text-[0.7rem] font-bold text-slate-400 mt-2 uppercase tracking-wide italic" x-text="(dept.company ? dept.company.name : 'System') + ' Strategic Portfolio'"></p>
                                    </div>
                                    <div class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center text-slate-300 group-hover:text-[#ff6900] transition-colors">
                                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Operations Lead</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="user-check" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-slate-700 truncate block" x-text="dept.manager ? dept.manager.name : 'Vacant Node'"></span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block">Staff Allocation</span>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="users" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.8rem] font-bold text-[#031629] uppercase italic tracking-tighter" x-text="(dept.employees_count || 0) + ' ACTIVE'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-4">
                                <button @click="openEdit(dept)" class="group flex-1 px-6 py-3.5 rounded-lg font-black text-[0.65rem] uppercase tracking-[0.15em] transition-all duration-300 flex items-center justify-center gap-2.5 bg-slate-900 hover:bg-[#ff6900] text-white shadow-lg shadow-slate-200/50">
                                    <i data-lucide="pencil" class="w-4 h-4 transition-transform duration-500 group-hover:scale-110"></i>
                                    <span>Edit Department</span>
                                </button>
                                
                                <button @click="deleteTask(dept.id)" class="group w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-xl active:scale-90 bg-slate-900 hover:bg-red-500 text-white">
                                    <i data-lucide="trash-2" class="w-5 h-5 transition-transform duration-500 group-hover:scale-110"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $attributes = $__attributesOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__attributesOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf5a744a70ee982199708c37a824ad023)): ?>
<?php $component = $__componentOriginalf5a744a70ee982199708c37a824ad023; ?>
<?php unset($__componentOriginalf5a744a70ee982199708c37a824ad023); ?>
<?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $attributes = $__attributesOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__attributesOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal247ae89654097d25470c0e2135dc9b7d)): ?>
<?php $component = $__componentOriginal247ae89654097d25470c0e2135dc9b7d; ?>
<?php unset($__componentOriginal247ae89654097d25470c0e2135dc9b7d); ?>
<?php endif; ?>

    <!-- OFFICIAL MODAL CONFIG -->
    <?php if (isset($component)) { $__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-modal','data' => ['xShow' => 'opened','title' => 'Department Details','icon' => 'layers','size' => 'max-w-4xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'opened','title' => 'Department Details','icon' => 'layers','size' => 'max-w-4xl']); ?>
        <div class="col-span-12 grid grid-cols-12 gap-0 overflow-hidden rounded-2xl">
            <!-- Left Panel: Main Info (5/12) -->
            <div class="col-span-12 lg:col-span-5 p-10 space-y-8 bg-slate-50/50 border-r border-slate-200">
                <div class="space-y-4">
                    <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest ml-1 block">Department Name</label>
                    <div class="bg-white p-6 rounded-2xl border-2 border-[#ff6900] shadow-xl shadow-orange-500/5 transition-all group-focus-within:border-[#ff6900]/60">
                        <input type="text" x-model="form.name" required placeholder="e.g. Marketing, Sales..." 
                               class="w-full bg-transparent text-2xl font-black text-slate-800 placeholder:text-slate-200 outline-none italic truncate uppercase tracking-tighter">
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                            <span class="px-2.5 py-1 bg-slate-900 text-[#ff6900] text-[0.55rem] font-black rounded uppercase tracking-widest" x-text="form.code || 'DEPT-00'"></span>
                            <span class="text-[0.55rem] font-bold text-slate-400 uppercase italic">Department Code</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 pt-4">
                    <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Department Manager','icon' => 'user-check','xModel' => 'form.manager_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Department Manager','icon' => 'user-check','x-model' => 'form.manager_id']); ?>
                        <option value="">No Manager Assigned</option>
                        <template x-for="m in managers" :key="m.id">
                            <option :value="m.id" x-text="m.name"></option>
                        </template>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $attributes = $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $component = $__componentOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>

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

            <!-- Right Panel: Department Details (7/12) -->
            <div class="col-span-12 lg:col-span-7 p-10 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Company','icon' => 'building','xModel' => 'form.company_id','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Company','icon' => 'building','x-model' => 'form.company_id','required' => true]); ?>
                        <option value="">Select Company...</option>
                        <template x-for="c in companies" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $attributes = $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $component = $__componentOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Parent Department','icon' => 'layers','xModel' => 'form.parent_id']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Parent Department','icon' => 'layers','x-model' => 'form.parent_id']); ?>
                        <option value="">None (Root Department)</option>
                        <template x-for="p in allDepartments" :key="p.id">
                            <option :value="p.id" x-text="p.name" :disabled="p.id == form.id"></option>
                        </template>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $attributes = $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__attributesOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0)): ?>
<?php $component = $__componentOriginal89de66992d8154ee9c416a2df8be3fb0; ?>
<?php unset($__componentOriginal89de66992d8154ee9c416a2df8be3fb0); ?>
<?php endif; ?>
                </div>

                <div class="space-y-3">
                    <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block">Description</label>
                    <textarea x-model="form.description" rows="4" placeholder="Enter department objectives and functions..." 
                        class="w-full bg-slate-50 border-2 border-slate-100/50 rounded-xl px-6 py-5 text-[0.85rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 focus:bg-white focus:ring-[8px] focus:ring-orange-500/5 transition-all shadow-sm placeholder:text-slate-300 placeholder:italic"></textarea>
                </div>

                <div class="pt-8 border-t border-slate-100 flex items-center justify-between group">
                    <div class="space-y-1">
                        <span class="text-[0.6rem] font-black uppercase text-slate-900 tracking-widest block italic">Department Status</span>
                        <span class="text-[0.5rem] text-slate-400 font-bold uppercase tracking-[0.1em]">Toggle to activate/deactivate the department</span>
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
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27)): ?>
<?php $attributes = $__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27; ?>
<?php unset($__attributesOriginal374a8b4f0d20c1f5f1a223240a48bd27); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27)): ?>
<?php $component = $__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27; ?>
<?php unset($__componentOriginal374a8b4f0d20c1f5f1a223240a48bd27); ?>
<?php endif; ?>

</div>

<script>
    function departmentManager() {
        return {
            opened: false, isEdit: false, loading: false,
            departments: [], search: '', companyFilter: '', statusFilter: '',
            companies: <?php echo json_encode($companies, 15, 512) ?>,
            managers: <?php echo json_encode($managers, 15, 512) ?>,
            allDepartments: <?php echo json_encode($parentDepartments, 15, 512) ?>,
            form: { id: '', name: '', code: '', description: '', manager_id: '', company_id: '', parent_id: '', is_active: true },

            initComponent() {
                this.fetchData();
                this.$nextTick(() => { this.refreshIcons(); });
            },

            refreshIcons() { if (typeof lucide !== 'undefined') { lucide.createIcons(); } },

            resetFilters() {
                this.search = '';
                this.companyFilter = '';
                this.statusFilter = '';
                this.fetchData();
            },

            async fetchData() {
                let url = `<?php echo e(route('admin.hr.departments.index')); ?>?search=${this.search}`;
                if (this.companyFilter) url += `&company_id=${this.companyFilter}`;
                if (this.statusFilter !== '') url += `&is_active=${this.statusFilter}`;
                
                const r = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d = await r.json();
                this.departments = Array.isArray(d) ? d : (d.data?.data || d.data || []);
                await this.$nextTick();
                this.refreshIcons();
            },

            openCreate() {
                this.isEdit = false;
                this.form = { id: '', name: '', code: '', description: '', manager_id: '', company_id: '', parent_id: '', is_active: true };
                this.opened = true;
            },

            openEdit(dept) {
                this.isEdit = true;
                this.form = { ...dept, is_active: !!dept.is_active };
                this.opened = true;
            },

            async submit() {
                const url = this.isEdit ? `/admin/hr/departments/${this.form.id}` : '/admin/hr/departments';
                const method = this.isEdit ? 'PUT' : 'POST';
                this.loading = true;
                try {
                    const r = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
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
                    title: 'Delete Department?',
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
                    const r = await fetch(`/admin/hr/departments/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
                    });
                    const d = await r.json();
                    if(r.ok && d.success) {
                        window.showToast(d.message, 'success');
                        this.fetchData();
                    } else {
                        window.showToast(d.message || 'Cannot delete department with active employees.', 'error');
                    }
                } catch(e) { window.showToast('Decommission failed', 'error'); }
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/departments/index.blade.php ENDPATH**/ ?>