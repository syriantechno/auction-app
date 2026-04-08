@extends('admin.layout')

@section('title', 'Add Employee')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add Employee</h1>
            <p class="text-slate-500 mt-1">Create a new employee record</p>
        </div>
        <a href="{{ route('admin.hr.employees.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
            Back to Employees
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl border border-slate-100">
        <form action="{{ route('admin.hr.employees.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2">Personal Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" required
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone</label>
                        <input type="tel" name="phone"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Birth Date</label>
                        <input type="date" name="birth_date"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                        <select name="gender" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <!-- Work Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-slate-900 border-b border-slate-100 pb-2">Work Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                        <select name="department_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Position</label>
                        <select name="position_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                            <option value="">Select Position</option>
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Default Shift</label>
                        <select name="default_shift_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                            <option value="">Select Shift</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Salary Structure</label>
                        <select name="salary_structure_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                            <option value="">Select Salary Structure</option>
                            @foreach($salaryStructures as $structure)
                                <option value="{{ $structure->id }}">{{ $structure->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Monthly Salary</label>
                        <input type="number" name="salary" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hire Date</label>
                        <input type="date" name="hire_date"
                               class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="has_system_access" value="1" class="mr-2">
                            <span class="text-sm font-medium text-slate-700">System Access</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                            <span class="text-sm font-medium text-slate-700">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.hr.employees.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
                    Create Employee
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
