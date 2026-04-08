<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Payroll;
use App\Models\HR\Employee;
use App\Models\HR\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $payrolls = Payroll::with('employee')
            ->where('year', $year)
            ->where('month', $month)
            ->latest()
            ->paginate(20);

        $employees = Employee::active()->get();

        // Summary statistics
        $summary = [
            'total_payrolls' => $payrolls->total(),
            'total_net_salary' => $payrolls->sum('net_salary'),
            'approved_count' => $payrolls->where('status', 'approved')->count(),
            'paid_count' => $payrolls->where('status', 'paid')->count()
        ];

        return view('admin.hr.payrolls.index', compact('payrolls', 'employees', 'year', 'month', 'summary'));
    }

    public function create()
    {
        $employees = Employee::active()->get();

        return view('admin.hr.payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100'
        ]);

        // Check if payroll already exists
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->first();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Payroll already exists for this employee for the selected month.');
        }

        // Get employee details
        $employee = Employee::findOrFail($validated['employee_id']);

        // Calculate payroll data
        $payrollData = $this->calculatePayroll($employee, $validated['month'], $validated['year']);
        $payrollData['code'] = $this->generatePayrollCode();
        $payrollData['generated_by'] = auth()->id();

        $payroll = Payroll::create($payrollData);

        return redirect()->route('admin.hr.payrolls.index')
            ->with('success', 'Payroll created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('admin.hr.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        if ($payroll->status !== 'draft' && $payroll->status !== 'calculated') {
            return redirect()->route('admin.hr.payrolls.index')
                ->with('error', 'Cannot edit approved or paid payrolls.');
        }

        return view('admin.hr.payrolls.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'draft' && $payroll->status !== 'calculated') {
            return redirect()->route('admin.hr.payrolls.index')
                ->with('error', 'Cannot edit approved or paid payrolls.');
        }

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_multiplier' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Recalculate totals
        $hourlyRate = $validated['basic_salary'] / (30 * 8); // Assuming 30 days, 8 hours
        $overtimeAmount = ($validated['overtime_hours'] ?? 0) * $hourlyRate * ($validated['overtime_multiplier'] ?? 1.5);
        $grossSalary = $validated['basic_salary'] + $overtimeAmount + ($validated['bonuses'] ?? 0);
        $netSalary = $grossSalary - ($validated['deductions'] ?? 0);

        $validated['hourly_rate'] = $hourlyRate;
        $validated['overtime_amount'] = $overtimeAmount;
        $validated['total_overtime_amount'] = $overtimeAmount;
        $validated['gross_salary'] = $grossSalary;
        $validated['net_salary'] = $netSalary;
        $validated['status'] = 'calculated';

        $payroll->update($validated);

        return redirect()->route('admin.hr.payrolls.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return redirect()->route('admin.hr.payrolls.index')
                ->with('error', 'Cannot delete paid payrolls.');
        }

        $payroll->delete();

        return redirect()->route('admin.hr.payrolls.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'calculated' && $payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Payroll cannot be approved.');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Payroll approved successfully.');
    }

    public function pay(Request $request, Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return redirect()->back()->with('error', 'Payroll must be approved before payment.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,check',
            'payment_date' => 'required|date'
        ]);

        $payroll->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date']
        ]);

        return redirect()->back()->with('success', 'Payroll marked as paid successfully.');
    }

    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($validated['employee_ids'] as $employeeId) {
            $exists = Payroll::where('employee_id', $employeeId)
                ->where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->exists();

            if ($exists) {
                $skippedCount++;
                continue;
            }

            $employee = Employee::findOrFail($employeeId);
            $payrollData = $this->calculatePayroll($employee, $validated['month'], $validated['year']);
            $payrollData['code'] = $this->generatePayrollCode();
            $payrollData['generated_by'] = auth()->id();

            Payroll::create($payrollData);
            $createdCount++;
        }

        return redirect()->route('admin.hr.payrolls.index')
            ->with('success', "{$createdCount} payrolls created, {$skippedCount} skipped (already exist).");
    }

    private function calculatePayroll(Employee $employee, int $month, int $year): array
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $basicSalary = $employee->salary ?? 0;
        $workingDays = $startDate->diffInDays($endDate) + 1;
        $workingHoursPerDay = 8;
        $hourlyRate = $basicSalary / ($workingDays * $workingHoursPerDay);

        // Get attendance data
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateMinutes = $attendances->sum('late_minutes');
        $overtimeHours = $attendances->sum('overtime_hours');
        $halfDays = $attendances->where('status', 'half_day')->count();

        // Calculate deductions
        $dailyRate = $basicSalary / $workingDays;
        $absentDeduction = $absentDays * $dailyRate;
        $halfDayDeduction = $halfDays * ($dailyRate / 2);
        $lateDeduction = ($lateMinutes / 60) * $hourlyRate;

        // Calculate overtime
        $overtimeMultiplier = 1.5;
        $overtimeAmount = $overtimeHours * $hourlyRate * $overtimeMultiplier;

        // Calculate earned salary
        $earnedSalary = ($presentDays / $workingDays) * $basicSalary;

        $grossSalary = $earnedSalary + $overtimeAmount;
        $totalDeductions = $absentDeduction + $halfDayDeduction + $lateDeduction;
        $netSalary = $grossSalary - $totalDeductions;

        return [
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            'basic_salary' => $basicSalary,
            'working_days' => $workingDays,
            'actual_working_days' => $presentDays,
            'working_hours_per_day' => $workingHoursPerDay,
            'hourly_rate' => round($hourlyRate, 2),
            'earned_salary' => round($earnedSalary, 2),
            'overtime_hours' => round($overtimeHours, 2),
            'overtime_multiplier' => $overtimeMultiplier,
            'overtime_amount' => round($overtimeAmount, 2),
            'total_overtime_amount' => round($overtimeAmount, 2),
            'deductions' => round($totalDeductions, 2),
            'deduction_details' => [
                'absent' => round($absentDeduction, 2),
                'half_day' => round($halfDayDeduction, 2),
                'late' => round($lateDeduction, 2)
            ],
            'bonuses' => 0,
            'bonus_details' => [],
            'absent_days' => $absentDays,
            'absent_deduction' => round($absentDeduction, 2),
            'late_minutes' => $lateMinutes,
            'late_deduction' => round($lateDeduction, 2),
            'half_days' => $halfDays,
            'half_day_deduction' => round($halfDayDeduction, 2),
            'gross_salary' => round($grossSalary, 2),
            'net_salary' => round($netSalary, 2),
            'status' => 'draft'
        ];
    }

    private function generatePayrollCode(): string
    {
        $prefix = 'PAY';
        $year = date('Y');
        $count = Payroll::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
