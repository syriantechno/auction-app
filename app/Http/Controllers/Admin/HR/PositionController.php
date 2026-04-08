<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Position;
use App\Models\HR\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $query = Position::with(['department'])
            ->withCount('employees');

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // Apply department filter
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Apply status filter
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $positions = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($positions);
        }

        $departments = Department::active()->get();

        return view('admin.hr.positions.index', compact('positions', 'departments'));
    }

    public function create()
    {
        $departments = Department::active()->get();

        return view('admin.hr.positions.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'default_salary' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = $this->generatePositionCode();
        $validated['is_active'] = $request->boolean('is_active', true);

        $position = Position::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Position created successfully.',
                'data' => $position->load('department')->loadCount('employees')
            ]);
        }

        return redirect()->route('admin.hr.positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        $departments = Department::active()->get();

        return view('admin.hr.positions.edit', compact('position', 'departments'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'default_salary' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $position->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Position updated successfully.',
                'data' => $position->load('department')->loadCount('employees')
            ]);
        }

        return redirect()->route('admin.hr.positions.index')
            ->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        if ($position->employees()->count() > 0) {
            $msg = 'Cannot delete position with active personnel.';
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->route('admin.hr.positions.index')->with('error', $msg);
        }

        $position->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Position decommissioned successfully.']);
        }

        return redirect()->route('admin.hr.positions.index')
            ->with('success', 'Position deleted successfully.');
    }

    private function generatePositionCode(): string
    {
        $prefix = 'POS';
        $count = Position::count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
