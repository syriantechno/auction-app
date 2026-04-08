<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Shift;
use App\Models\HR\Department;
use App\Models\HR\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::withCount('employees');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Apply status filter
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $shifts = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($shifts);
        }

        return view('admin.hr.shifts.index', compact('shifts'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $employees = Employee::active()->get();

        return view('admin.hr.shifts.create', compact('departments', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'working_hours' => 'required|numeric|min:0|max:24',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'is_active' => 'boolean'
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateShiftCode();
        }
        $validated['is_active'] = $request->boolean('is_active', true);

        Shift::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift created successfully.'
            ]);
        }

        return redirect()->route('admin.hr.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        $departments = Department::active()->get();
        $employees = Employee::active()->get();

        return view('admin.hr.shifts.edit', compact('shift', 'departments', 'employees'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'working_hours' => 'required|numeric|min:0|max:24',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $shift->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift updated successfully.'
            ]);
        }

        return redirect()->route('admin.hr.shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->employees()->count() > 0) {
            return redirect()->route('admin.hr.shifts.index')
                ->with('error', 'Cannot delete shift assigned to employees.');
        }

        $shift->delete();

        return redirect()->route('admin.hr.shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }

    private function generateShiftCode(): string
    {
        $prefix = 'SHIFT';
        $count = Shift::count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
