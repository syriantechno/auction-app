<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['manager', 'company'])
            ->withCount('employees');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Apply company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Apply status filter
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $departments = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($departments);
        }

        $companies = Company::active()->get();
        $managers = Employee::active()->get();
        $parentDepartments = Department::active()->whereNull('parent_id')->get();

        return view('admin.hr.departments.index', compact('departments', 'companies', 'managers', 'parentDepartments'));
    }

    public function create()
    {
        $managers = Employee::active()->get();
        $parentDepartments = Department::active()->whereNull('parent_id')->get();
        $companies = Company::active()->get();

        return view('admin.hr.departments.create', compact('managers', 'parentDepartments', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = $this->generateDepartmentCode();
        $validated['is_active'] = $request->boolean('is_active', true);

        $department = Department::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Department initialized successfully!',
                'data' => $department->load(['company', 'manager'])
            ]);
        }

        return redirect()->route('admin.hr.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        $managers = Employee::active()->get();
        $parentDepartments = Department::active()
            ->whereNull('parent_id')
            ->where('id', '!=', $department->id)
            ->get();
        $companies = Company::active()->get();

        return view('admin.hr.departments.edit', compact('department', 'managers', 'parentDepartments', 'companies'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id',
            'company_id' => 'required|exists:companies,id',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $department->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Department synchronized successfully!',
                'data' => $department->load(['company', 'manager'])
            ]);
        }

        return redirect()->route('admin.hr.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            $msg = 'Cannot delete department with active employees.';
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->route('admin.hr.departments.index')->with('error', $msg);
        }

        $department->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Department successfully decommissioned.']);
        }

        return redirect()->route('admin.hr.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    private function generateDepartmentCode(): string
    {
        $prefix = 'DEPT';
        $count = Department::count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
