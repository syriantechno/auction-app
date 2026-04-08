<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Penalty;
use App\Models\HR\Employee;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index(Request $request)
    {
        $query = Penalty::with(['employee', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('penalty_type')) {
            $query->where('penalty_type', $request->penalty_type);
        }

        $penalties = $query->latest()->paginate(20);
        $employees = Employee::active()->get();

        return view('admin.hr.penalties.index', compact('penalties', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('admin.hr.penalties.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'penalty_type' => 'required|in:late,absence,misconduct,other',
            'penalty_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $validated['code'] = $this->generatePenaltyCode();
        $validated['status'] = 'pending';

        Penalty::create($validated);

        return redirect()->route('admin.hr.penalties.index')
            ->with('success', 'Penalty created successfully.');
    }

    public function edit(Penalty $penalty)
    {
        if ($penalty->status !== 'pending') {
            return redirect()->route('admin.hr.penalties.index')
                ->with('error', 'Cannot edit approved or deducted penalties.');
        }

        $employees = Employee::active()->get();

        return view('admin.hr.penalties.edit', compact('penalty', 'employees'));
    }

    public function update(Request $request, Penalty $penalty)
    {
        if ($penalty->status !== 'pending') {
            return redirect()->route('admin.hr.penalties.index')
                ->with('error', 'Cannot edit approved or deducted penalties.');
        }

        $validated = $request->validate([
            'penalty_type' => 'required|in:late,absence,misconduct,other',
            'penalty_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $penalty->update($validated);

        return redirect()->route('admin.hr.penalties.index')
            ->with('success', 'Penalty updated successfully.');
    }

    public function approve(Penalty $penalty)
    {
        if ($penalty->status !== 'pending') {
            return redirect()->back()->with('error', 'Penalty is already processed.');
        }

        $penalty->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Penalty approved successfully.');
    }

    public function deduct(Penalty $penalty)
    {
        if ($penalty->status !== 'approved') {
            return redirect()->back()->with('error', 'Penalty must be approved before deduction.');
        }

        $penalty->update([
            'status' => 'deducted'
        ]);

        return redirect()->back()->with('success', 'Penalty marked as deducted successfully.');
    }

    public function destroy(Penalty $penalty)
    {
        if ($penalty->status === 'deducted') {
            return redirect()->back()->with('error', 'Cannot delete deducted penalties.');
        }

        $penalty->delete();

        return redirect()->route('admin.hr.penalties.index')
            ->with('success', 'Penalty deleted successfully.');
    }

    private function generatePenaltyCode(): string
    {
        $prefix = 'PEN';
        $year = date('Y');
        $count = Penalty::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
