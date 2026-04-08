<?php $__env->startSection('title', 'Employees'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="employeeManager()" x-init="initComponent()">
    <?php if (isset($component)) { $__componentOriginal247ae89654097d25470c0e2135dc9b7d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal247ae89654097d25470c0e2135dc9b7d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-page-standard','data' => ['icon' => 'users','title' => 'Employee','highlight' => 'Manager','subtitle' => 'Manage end-to-end employee lifecycle and corporate access','dot' => 'orange']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-page-standard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'Employee','highlight' => 'Manager','subtitle' => 'Manage end-to-end employee lifecycle and corporate access','dot' => 'orange']); ?>
        
         <?php $__env->slot('actions', null, []); ?> 
            <button @click="openCreate()" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                <i data-lucide="user-plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                New Employee
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
                <input type="text" x-model="search" @input="fetchData()" placeholder="Search employees..." class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-lg pl-11 pr-4 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all shadow-sm">
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-data-view','data' => ['alpine' => true,'alpineCount' => 'employees.length','emptyTitle' => 'No Employees Found','emptySubtitle' => 'The workforce roster is currently dormant. Create your first employee record to begin operations.','emptyIcon' => 'users']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-data-view'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['alpine' => true,'alpineCount' => 'employees.length','emptyTitle' => 'No Employees Found','emptySubtitle' => 'The workforce roster is currently dormant. Create your first employee record to begin operations.','emptyIcon' => 'users']); ?>
            
            <!-- High-Density Table -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden" x-show="employees.length > 0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em] w-16 text-center">ID</th>
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em]">Employee Identity</th>
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em]">Job Placement</th>
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em]">System Access</th>
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em] text-center">Status</th>
                            <th class="py-4 px-6 text-[0.6rem] text-slate-400 font-black uppercase tracking-[0.2em] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="emp in employees" :key="emp.id">
                            <tr class="group hover:bg-slate-50/80 transition-all duration-300 border-l-4 border-l-transparent hover:border-l-[#ff6900]">
                                <td class="py-4 px-6 text-center">
                                    <span class="text-[0.65rem] font-mono font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded" x-text="'#'+emp.id"></span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[#1d293d] flex items-center justify-center text-white shadow-md">
                                            <span class="text-[0.7rem] font-black uppercase" x-text="(emp.first_name?.[0] || '') + (emp.last_name?.[0] || '')"></span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[0.85rem] font-black text-[#031629] tracking-tight" x-text="(emp.first_name || '') + ' ' + (emp.last_name || '')"></span>
                                            <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-wider" x-text="emp.phone || 'No Phone'"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-[#ff6900]"></i>
                                            <span class="text-[0.75rem] font-black text-slate-800 uppercase tracking-tighter" x-text="emp.department?.name || 'No Dept'"></span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-slate-400"></i>
                                            <span class="text-[0.6rem] font-bold uppercase tracking-widest text-slate-400" x-text="emp.position?.name || 'No Position'"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2" x-show="emp.has_system_access">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500">
                                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-[0.6rem] font-black text-emerald-600 uppercase tracking-widest">Authorized</span>
                                    </div>
                                    <span x-show="!emp.has_system_access" class="text-[0.6rem] font-black text-slate-300 uppercase tracking-widest">Manual Record</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-3 py-1.5 rounded-full text-[0.6rem] font-black uppercase tracking-wider border"
                                          :class="emp.is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-200'"
                                          x-text="emp.is_active ? 'Active' : 'Inactive'"></span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEdit(emp)" class="group px-4 py-2 rounded-lg bg-slate-900 hover:bg-[#ff6900] text-white text-[0.65rem] font-black uppercase tracking-wider transition-all flex items-center gap-2">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            Edit
                                        </button>
                                        <button @click="deleteTask(emp.id)" class="group w-9 h-9 rounded-lg bg-slate-100 hover:bg-red-500 hover:text-white text-slate-600 transition-all flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-modal','data' => ['xShow' => 'opened','title' => 'Employee Details','icon' => 'users','size' => 'max-w-6xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'opened','title' => 'Employee Details','icon' => 'users','size' => 'max-w-6xl']); ?>
        <div class="col-span-12 grid grid-cols-12 gap-0 overflow-visible rounded-2xl">
            <!-- Left Panel: Identity (4/12) -->
            <div class="col-span-12 lg:col-span-4 p-6 space-y-5 bg-slate-50/50 border-r border-slate-200">
                <!-- Profile Picture -->
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-24 h-24 rounded-full bg-slate-200 border-4 border-white shadow-lg flex items-center justify-center overflow-hidden relative group cursor-pointer">
                        <img x-show="form.photo" :src="form.photo" class="w-full h-full object-cover">
                        <div x-show="!form.photo" class="text-slate-400">
                            <i data-lucide="user" class="w-10 h-10"></i>
                        </div>
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <button type="button" class="text-[0.65rem] font-black text-[#ff6900] uppercase tracking-wider hover:underline">
                        Upload Photo
                    </button>
                </div>

                <div class="space-y-3">
                    <label class="text-[0.6rem] font-black uppercase text-slate-400 tracking-widest ml-1 block">Full Legal Name</label>
                    <div class="bg-white p-4 rounded-xl border-2 border-[#ff6900] shadow-xl shadow-orange-500/5 transition-all">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" x-model="form.first_name" required placeholder="First" 
                                   class="w-full bg-transparent text-lg font-black text-slate-800 placeholder:text-slate-200 outline-none uppercase tracking-tighter">
                            <input type="text" x-model="form.last_name" required placeholder="Last" 
                                   class="w-full bg-transparent text-lg font-black text-slate-800 placeholder:text-slate-200 outline-none uppercase tracking-tighter">
                        </div>
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                            <span class="px-2 py-0.5 bg-slate-900 text-[#ff6900] text-[0.5rem] font-black rounded uppercase tracking-widest">EMP</span>
                            <span class="text-[0.5rem] font-bold text-slate-400 uppercase italic">Identity</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <?php if (isset($component)) { $__componentOriginal89de66992d8154ee9c416a2df8be3fb0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal89de66992d8154ee9c416a2df8be3fb0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Department','icon' => 'building-2','xModel' => 'form.department_id','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Department','icon' => 'building-2','x-model' => 'form.department_id','required' => true]); ?>
                        <option value="">Select Department...</option>
                        <template x-for="d in departments" :key="d.id">
                            <option :value="d.id" x-text="d.name"></option>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-select','data' => ['label' => 'Position','icon' => 'briefcase','xModel' => 'form.position_id','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Position','icon' => 'briefcase','x-model' => 'form.position_id','required' => true]); ?>
                        <option value="">Select Position...</option>
                        <template x-for="p in positions" :key="p.id">
                            <option :value="p.id" x-text="p.name"></option>
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

                <!-- System Access Section -->
                <div class="p-4 bg-slate-900 rounded-xl shadow-lg space-y-3" x-show="!isEdit">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4 text-[#ff6900]"></i>
                        <span class="text-[0.6rem] font-black text-white uppercase tracking-widest">System Access</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" x-model="form.has_system_access" class="w-4 h-4 accent-[#ff6900]">
                        <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-wider">Create login credentials</span>
                    </div>
                    <div x-show="form.has_system_access" class="space-y-3 pt-2 border-t border-slate-700">
                        <div class="space-y-1">
                            <label class="text-[0.55rem] font-black uppercase text-slate-500 tracking-wider">Username</label>
                            <input type="text" x-model="form.username" placeholder="user.name" 
                                   class="w-full h-9 bg-slate-800 border border-slate-700 rounded-lg px-3 text-[0.8rem] font-bold text-white outline-none focus:border-[#ff6900]/50">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[0.55rem] font-black uppercase text-slate-500 tracking-wider">Password</label>
                            <input type="password" x-model="form.password" placeholder="••••••••" 
                                   class="w-full h-9 bg-slate-800 border border-slate-700 rounded-lg px-3 text-[0.8rem] font-bold text-white outline-none focus:border-[#ff6900]/50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Details (8/12) -->
            <div class="col-span-12 lg:col-span-8 p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Email','type' => 'email','xModel' => 'form.email','placeholder' => 'email@company.com','icon' => 'mail']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','type' => 'email','x-model' => 'form.email','placeholder' => 'email@company.com','icon' => 'mail']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Phone','type' => 'text','xModel' => 'form.phone','placeholder' => '+971...','icon' => 'phone']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','type' => 'text','x-model' => 'form.phone','placeholder' => '+971...','icon' => 'phone']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Employee ID','type' => 'text','xModel' => 'form.employee_id','placeholder' => 'EMP-001','icon' => 'id-card']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Employee ID','type' => 'text','x-model' => 'form.employee_id','placeholder' => 'EMP-001','icon' => 'id-card']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <?php if (isset($component)) { $__componentOriginal331272c83d3eb364d8d2da5f5c946e62 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-input','data' => ['label' => 'Salary','type' => 'number','xModel' => 'form.salary','placeholder' => '0.00','icon' => 'wallet']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Salary','type' => 'number','x-model' => 'form.salary','placeholder' => '0.00','icon' => 'wallet']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $attributes = $__attributesOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__attributesOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62)): ?>
<?php $component = $__componentOriginal331272c83d3eb364d8d2da5f5c946e62; ?>
<?php unset($__componentOriginal331272c83d3eb364d8d2da5f5c946e62); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5ec76c329367b94a81bc06aea48f6863 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ec76c329367b94a81bc06aea48f6863 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-date','data' => ['dateName' => 'hire_date','label' => 'Hire Date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateName' => 'hire_date','label' => 'Hire Date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $attributes = $__attributesOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $component = $__componentOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__componentOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal5ec76c329367b94a81bc06aea48f6863 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ec76c329367b94a81bc06aea48f6863 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.elite-date','data' => ['dateName' => 'birth_date','label' => 'Birth Date']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('elite-date'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['dateName' => 'birth_date','label' => 'Birth Date']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $attributes = $__attributesOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__attributesOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ec76c329367b94a81bc06aea48f6863)): ?>
<?php $component = $__componentOriginal5ec76c329367b94a81bc06aea48f6863; ?>
<?php unset($__componentOriginal5ec76c329367b94a81bc06aea48f6863); ?>
<?php endif; ?>
                </div>

                <div class="space-y-1">
                    <label class="text-[0.6rem] font-black uppercase text-slate-500 tracking-widest ml-1">Address</label>
                    <textarea x-model="form.address" placeholder="Full address..." rows="2"
                           class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg p-3 text-[0.8rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all resize-none"></textarea>
                </div>

                <div class="grid grid-cols-4 gap-4">
                    <div class="space-y-1">
                        <label class="text-[0.6rem] font-black uppercase text-slate-500 tracking-widest ml-1">City</label>
                        <input type="text" x-model="form.city" placeholder="Dubai" 
                               class="w-full h-11 bg-slate-50 border-2 border-slate-100 rounded-lg px-3 text-[0.8rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[0.6rem] font-black uppercase text-slate-500 tracking-widest ml-1">Country</label>
                        <input type="text" x-model="form.country" placeholder="UAE" 
                               class="w-full h-11 bg-slate-50 border-2 border-slate-100 rounded-lg px-3 text-[0.8rem] font-bold text-slate-700 outline-none focus:border-[#ff6900]/30 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[0.6rem] font-black uppercase text-slate-500 tracking-widest ml-1">Gender</label>
                        <div class="flex items-center gap-2 h-11">
                            <button type="button" 
                                    @click="form.gender = 'male'"
                                    :class="form.gender === 'male' ? 'bg-[#ff6900] text-white' : 'bg-slate-100 text-slate-400'"
                                    class="flex-1 h-full rounded-lg text-[0.7rem] font-black uppercase transition-all">
                                M
                            </button>
                            <button type="button"
                                    @click="form.gender = 'female'"
                                    :class="form.gender === 'female' ? 'bg-[#ff6900] text-white' : 'bg-slate-100 text-slate-400'"
                                    class="flex-1 h-full rounded-lg text-[0.7rem] font-black uppercase transition-all">
                                F
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[0.6rem] font-black uppercase text-slate-500 tracking-widest ml-1">Status</label>
                        <div class="flex items-center gap-2 h-11 bg-slate-50 border-2 border-slate-100 rounded-lg px-3">
                            <span class="text-[0.7rem] font-bold" :class="form.is_active ? 'text-emerald-600' : 'text-slate-400'" x-text="form.is_active ? 'Active' : 'Inactive'"></span>
                            <label class="relative flex-shrink-0 cursor-pointer ml-auto">
                                <input type="checkbox" x-model="form.is_active" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-all
                                            after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                            peer-checked:after:translate-x-4"></div>
                            </label>
                        </div>
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
    function employeeManager() {
        return {
            opened: false, isEdit: false, loading: false,
            employees: [], search: '', departmentFilter: '', statusFilter: '',
            departments: <?php echo json_encode($departments ?? [], 15, 512) ?>,
            positions: <?php echo json_encode($positions ?? [], 15, 512) ?>,
            form: { id: '', first_name: '', last_name: '', email: '', phone: '', department_id: '', position_id: '', salary: '', employee_id: '', hire_date: '', birth_date: '', address: '', city: '', country: '', gender: '', photo: '', has_system_access: false, username: '', password: '', is_active: true },

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
                let url = `<?php echo e(route('admin.hr.employees.index')); ?>?search=${this.search}`;
                if (this.departmentFilter) url += `&department_id=${this.departmentFilter}`;
                if (this.statusFilter !== '') url += `&is_active=${this.statusFilter}`;
                
                const r = await fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const d = await r.json();
                this.employees = Array.isArray(d) ? d : (d.data?.data || d.data || []);
                await this.$nextTick();
                this.refreshIcons();
            },

            openCreate() {
                this.isEdit = false;
                this.form = { id: '', first_name: '', last_name: '', email: '', phone: '', department_id: '', position_id: '', salary: '', employee_id: '', hire_date: '', birth_date: '', address: '', city: '', country: '', gender: '', photo: '', has_system_access: false, username: '', password: '', is_active: true };
                this.opened = true;
                this.$nextTick(() => { this.refreshIcons(); });
            },

            openEdit(emp) {
                this.isEdit = true;
                this.form = { ...emp, is_active: !!emp.is_active };
                this.opened = true;
                this.$nextTick(() => { this.refreshIcons(); });
            },

            async submit() {
                const url = this.isEdit ? `/admin/hr/employees/${this.form.id}` : '/admin/hr/employees';
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
                } catch(e) { window.showToast('Profile sync failed', 'error'); }
                finally { this.loading = false; }
            },

            async deleteTask(id) {
                const confirmed = await window.Swal.fire({
                    title: 'Delete Employee?',
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
                    const r = await fetch(`/admin/hr/employees/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
                    });
                    const d = await r.json();
                    if(r.ok && d.success) {
                        window.showToast(d.message, 'success');
                        this.fetchData();
                    } else {
                        window.showToast(d.message || 'Cannot delete employee.', 'error');
                    }
                } catch(e) { window.showToast('Delete failed', 'error'); }
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\auction_app\resources\views/admin/hr/employees/index.blade.php ENDPATH**/ ?>