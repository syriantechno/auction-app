@php
    $departments = \App\Models\HR\Department::where('is_active', true)->get();
    $positions = \App\Models\HR\Position::where('is_active', true)->get();
    $shifts = \App\Models\HR\Shift::where('is_active', true)->get();
    $salaryStructures = \App\Models\HR\SalaryStructure::where('is_active', true)->get();
    $generatedCode = 'EMP-' . date('Ym') . str_pad(\App\Models\HR\Employee::count() + 1, 3, '0', STR_PAD_LEFT);
@endphp



<div class="modal-overlay" id="employee-modal-overlay" onclick="if(event.target === this) closeModal()">
    <div class="modal-container">
        <div class="modal-header">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Add New Employee</h2>
                <p class="text-xs text-slate-500 font-medium">Create a new team member profile</p>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form id="create-employee-form" action="{{ route('admin.hr.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Photo Column -->
                    <div class="md:col-span-3 flex flex-col items-center">
                        <div class="relative group">
                            <img id="profile-preview" src="/images/default-avatar.png" 
                                 class="w-32 h-32 rounded-3xl object-cover border-4 border-slate-50 shadow-sm transition-all group-hover:border-orange-100">
                            <label for="profile_picture" class="absolute -bottom-2 -right-2 w-10 h-10 bg-white border border-slate-100 rounded-xl shadow-lg flex items-center justify-center cursor-pointer text-slate-600 hover:text-orange-500 transition-all">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                                <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        <div class="mt-6 w-full">
                            <label class="form-label">Employee ID</label>
                            <input type="text" name="code" class="form-control bg-slate-50 font-bold text-slate-800 text-center" value="{{ $generatedCode }}" readonly>
                        </div>
                    </div>

                    <!-- Info Column -->
                    <div class="md:col-span-9">
                        <div class="section-title">Personal Details</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required placeholder="John">
                            </div>
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" placeholder="Quincy">
                            </div>
                            <div>
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required placeholder="Doe">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com">
                            </div>
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+971 50 ...">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="form-label">Birth Date</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>
                            <div>
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Blood Type</label>
                                <select name="blood_type" class="form-control">
                                    <option value="">N/A</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                        <option value="{{ $bt }}">{{ $bt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="section-title">Employment</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-control" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Position</label>
                                <select name="position_id" class="form-control" required>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="form-label">Joining Date</label>
                                <input type="date" name="hire_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Monthly Salary</label>
                                <div class="relative">
                                    <input type="number" name="salary" class="form-control pl-12" placeholder="0.00">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">AED</span>
                                </div>
                            </div>
                        </div>

                        <div class="section-title">System Settings</div>
                        <div class="flex items-center gap-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative w-12 h-6 flex items-center">
                                    <input type="checkbox" name="has_system_access" class="peer hidden" id="sys-access" onchange="togglePassword(this)">
                                    <div class="absolute inset-0 bg-slate-200 rounded-full transition-all peer-checked:bg-emerald-500"></div>
                                    <div class="absolute left-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:left-7"></div>
                                </div>
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">System Access</span>
                            </label>

                            <div id="password-field" class="hidden flex-1 animate-in slide-in-from-left-2 duration-300">
                                <input type="password" name="system_password" class="form-control" placeholder="Specify password or leave blank for auto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Save Employee
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    if(window.lucide) lucide.createIcons();
    
    function closeModal() {
        const modal = document.getElementById('employee-modal-overlay');
        modal.classList.add('animate-out', 'fade-out', 'duration-300');
        setTimeout(() => modal.remove(), 250);
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassword(cb) {
        document.getElementById('password-field').classList.toggle('hidden', !cb.checked);
    }
</script>
