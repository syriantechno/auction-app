<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Leave;
use App\Models\HR\Employee;
use App\Models\HR\Department;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['employee', 'department', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->latest()->paginate(20);
        $employees = Employee::active()->get();

        return view('admin.hr.leaves.index', compact('leaves', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $departments = Department::active()->get();

        return view('admin.hr.leaves.create', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'department_id' => 'nullable|exists:departments,id',
            'leave_type' => 'required|in:annual,sick,unpaid,emergency,maternity',
            'reason_category' => 'nullable|in:personal,family,medical,work,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_paid' => 'boolean',
            'reason_details' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $validated['code'] = $this->generateLeaveCode();
        $validated['is_paid'] = $request->boolean('is_paid', true);
        $validated['status'] = 'pending';
        
        // Calculate days count
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;

        Leave::create($validated);

        return redirect()->route('admin.hr.leaves.index')
            ->with('success', 'Leave request created successfully.');
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee', 'department', 'approver']);
        
        return view('admin.hr.leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->route('admin.hr.leaves.index')
                ->with('error', 'Cannot edit approved or rejected leave requests.');
        }

        $employees = Employee::active()->get();
        $departments = Department::active()->get();

        return view('admin.hr.leaves.edit', compact('leave', 'employees', 'departments'));
    }

    public function update(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->route('admin.hr.leaves.index')
                ->with('error', 'Cannot edit approved or rejected leave requests.');
        }

        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'leave_type' => 'required|in:annual,sick,unpaid,emergency,maternity',
            'reason_category' => 'nullable|in:personal,family,medical,work,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_paid' => 'boolean',
            'reason_details' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $validated['is_paid'] = $request->boolean('is_paid', true);
        
        // Recalculate days count
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = \Carbon\Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;

        $leave->update($validated);

        return redirect()->route('admin.hr.leaves.index')
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->route('admin.hr.leaves.index')
                ->with('error', 'Cannot delete approved or rejected leave requests.');
        }

        $leave->delete();

        return redirect()->route('admin.hr.leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    public function approve(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->back()->with('error', 'Leave request is already processed.');
        }

        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return redirect()->back()->with('error', 'Leave request is already processed.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Leave request rejected successfully.');
    }

    private function generateLeaveCode(): string
    {
        $prefix = 'LEV';
        $year = date('Y');
        $count = Leave::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
