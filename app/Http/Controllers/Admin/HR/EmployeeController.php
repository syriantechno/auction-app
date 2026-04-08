<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Employee;
use App\Models\HR\Department;
use App\Models\HR\Position;
use App\Models\HR\Shift;
use App\Models\HR\SalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position', 'shift']);

        // Modern High-Performance Filtering (Same as Cars Inventory)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $employees = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($employees);
        }
        
        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $shifts = Shift::where('is_active', true)->get();
        $salaryStructures = SalaryStructure::where('is_active', true)->get();

        return view('admin.hr.employees.index', compact(
            'departments', 
            'positions', 
            'shifts', 
            'salaryStructures',
            'employees'
        ));
    }

    public function createModal()
    {
        $departments = Department::active()->get();
        $positions = Position::active()->get();
        $shifts = Shift::active()->get();
        $salaryStructures = SalaryStructure::active()->get();
        $generatedCode = $this->generateEmployeeCode();

        return view('admin.hr.employees.create-modal', compact(
            'departments', 
            'positions', 
            'shifts', 
            'salaryStructures', 
            'generatedCode'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);

        $validated['code'] = $this->generateEmployeeCode();
        $validated['is_active'] = true;
        
        // System Access Handling
        if ($request->has('has_system_access') && $request->filled('email')) {
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($request->system_password ?: Str::random(10)),
            ]);
            $validated['user_id'] = $user->id;
            $validated['has_system_access'] = true;
        }

        $employee = Employee::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee profile initialized successfully!',
                'data' => $employee->load(['department', 'position', 'shift'])
            ]);
        }

        return redirect()->route('admin.hr.employees.index')->with('success', 'Employee initialized.');
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "nullable|email|unique:employees,email,{$employee->id}",
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'salary' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $employee->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee profile updated successfully!',
                'data' => $employee->load(['department', 'position', 'shift'])
            ]);
        }

        return redirect()->route('admin.hr.employees.index')->with('success', 'Profile updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['success' => true, 'message' => 'Profile purged from system.']);
    }

    private function generateEmployeeCode()
    {
        $prefix = 'EMP-' . date('Ym');
        $lastEmployee = Employee::where('code', 'like', $prefix . '%')->orderBy('code', 'desc')->first();
        $newNumber = $lastEmployee ? (intval(substr($lastEmployee->code, -3)) + 1) : 1;
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
