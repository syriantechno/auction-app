<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Advance;
use App\Models\HR\Employee;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Advance::with(['employee', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $advances = $query->latest()->paginate(20);
        $employees = Employee::active()->get();

        // Statistics
        $stats = [
            'total_pending' => Advance::where('status', 'pending')->sum('amount'),
            'total_paid' => Advance::where('status', 'paid')->sum('amount'),
            'total_remaining' => Advance::sum('remaining_amount')
        ];

        return view('admin.hr.advances.index', compact('advances', 'employees', 'stats'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('admin.hr.advances.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'reason' => 'nullable|string',
            'repayment_method' => 'required|in:salary_deduction,cash',
            'installments_count' => 'required|integer|min:1'
        ]);

        $validated['code'] = $this->generateAdvanceCode();
        $validated['status'] = 'pending';
        $validated['remaining_amount'] = $validated['amount'];

        if ($validated['installments_count'] > 1) {
            $validated['installment_amount'] = $validated['amount'] / $validated['installments_count'];
        } else {
            $validated['installment_amount'] = $validated['amount'];
        }

        Advance::create($validated);

        return redirect()->route('admin.hr.advances.index')
            ->with('success', 'Advance request created successfully.');
    }

    public function show(Advance $advance)
    {
        $advance->load(['employee', 'approvedBy']);

        return view('admin.hr.advances.show', compact('advance'));
    }

    public function approve(Request $request, Advance $advance)
    {
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'Advance request is already processed.');
        }

        $advance->update([
            'status' => 'approved',
            'approved_date' => now(),
            'approved_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Advance request approved successfully.');
    }

    public function reject(Advance $advance)
    {
        if ($advance->status !== 'pending') {
            return redirect()->back()->with('error', 'Advance request is already processed.');
        }

        $advance->update([
            'status' => 'rejected',
            'approved_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Advance request rejected successfully.');
    }

    public function pay(Advance $advance)
    {
        if ($advance->status !== 'approved') {
            return redirect()->back()->with('error', 'Advance must be approved before payment.');
        }

        $advance->update([
            'status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Advance marked as paid successfully.');
    }

    public function destroy(Advance $advance)
    {
        if ($advance->status === 'paid') {
            return redirect()->back()->with('error', 'Cannot delete paid advances.');
        }

        $advance->delete();

        return redirect()->route('admin.hr.advances.index')
            ->with('success', 'Advance request deleted successfully.');
    }

    private function generateAdvanceCode(): string
    {
        $prefix = 'ADV';
        $year = date('Y');
        $count = Advance::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
