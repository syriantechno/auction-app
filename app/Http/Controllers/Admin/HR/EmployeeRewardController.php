<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeReward;
use App\Models\HR\Employee;
use Illuminate\Http\Request;

class EmployeeRewardController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeReward::with(['employee', 'approvedBy']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('reward_type')) {
            $query->where('reward_type', $request->reward_type);
        }

        if ($request->filled('is_paid')) {
            $query->where('is_paid', $request->boolean('is_paid'));
        }

        $rewards = $query->latest()->paginate(20);
        $employees = Employee::active()->get();

        // Statistics
        $stats = [
            'total_rewards' => EmployeeReward::count(),
            'total_paid' => EmployeeReward::where('is_paid', true)->sum('amount'),
            'total_unpaid' => EmployeeReward::where('is_paid', false)->sum('amount')
        ];

        return view('admin.hr.rewards.index', compact('rewards', 'employees', 'stats'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('admin.hr.rewards.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reward_type' => 'required|in:bonus,allowance,gift,certificate,promotion',
            'title' => 'required|string|max:255',
            'reward_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $validated['code'] = $this->generateRewardCode();
        $validated['is_paid'] = false;

        EmployeeReward::create($validated);

        return redirect()->route('admin.hr.rewards.index')
            ->with('success', 'Reward created successfully.');
    }

    public function edit(EmployeeReward $reward)
    {
        if ($reward->is_paid) {
            return redirect()->route('admin.hr.rewards.index')
                ->with('error', 'Cannot edit paid rewards.');
        }

        $employees = Employee::active()->get();

        return view('admin.hr.rewards.edit', compact('reward', 'employees'));
    }

    public function update(Request $request, EmployeeReward $reward)
    {
        if ($reward->is_paid) {
            return redirect()->route('admin.hr.rewards.index')
                ->with('error', 'Cannot edit paid rewards.');
        }

        $validated = $request->validate([
            'reward_type' => 'required|in:bonus,allowance,gift,certificate,promotion',
            'title' => 'required|string|max:255',
            'reward_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $reward->update($validated);

        return redirect()->route('admin.hr.rewards.index')
            ->with('success', 'Reward updated successfully.');
    }

    public function pay(Request $request, EmployeeReward $reward)
    {
        if ($reward->is_paid) {
            return redirect()->back()->with('error', 'Reward is already paid.');
        }

        $reward->update([
            'is_paid' => true,
            'paid_date' => now()
        ]);

        return redirect()->back()->with('success', 'Reward marked as paid successfully.');
    }

    public function destroy(EmployeeReward $reward)
    {
        if ($reward->is_paid) {
            return redirect()->back()->with('error', 'Cannot delete paid rewards.');
        }

        $reward->delete();

        return redirect()->route('admin.hr.rewards.index')
            ->with('success', 'Reward deleted successfully.');
    }

    private function generateRewardCode(): string
    {
        $prefix = 'RWD';
        $year = date('Y');
        $count = EmployeeReward::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
