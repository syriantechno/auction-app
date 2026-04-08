<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Employee;
use App\Models\HR\Attendance;
use App\Models\HR\Leave;
use App\Models\HR\Payroll;
use App\Models\HR\Advance;
use Carbon\Carbon;

class HRController extends Controller
{
    public function dashboard()
    {
        // Statistics
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::active()->count(),
            'inactive_employees' => Employee::where('is_active', false)->count(),
            'today_attendance' => Attendance::where('attendance_date', today())->count(),
            'present_today' => Attendance::where('attendance_date', today())->where('status', 'present')->count(),
            'absent_today' => Attendance::where('attendance_date', today())->where('status', 'absent')->count(),
            'late_today' => Attendance::where('attendance_date', today())->where('status', 'late')->count(),
            'on_leave' => Leave::where('start_date', '<=', today())
                ->where('end_date', '>=', today())
                ->where('status', 'approved')
                ->count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'pending_advances' => Advance::where('status', 'pending')->count(),
            'monthly_payroll' => Payroll::where('month', now()->month)
                ->where('year', now()->year)
                ->sum('net_salary')
        ];

        // Recent employees
        $recentEmployees = Employee::with('department')
            ->latest()
            ->limit(5)
            ->get();

        // Today's attendance
        $todayAttendance = Attendance::with('employee')
            ->where('attendance_date', today())
            ->latest()
            ->limit(10)
            ->get();

        // Pending approvals
        $pendingLeaves = Leave::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $pendingAdvances = Advance::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // Upcoming birthdays
        $upcomingBirthdays = Employee::active()
            ->whereRaw("DATE_FORMAT(birth_date, '%m-%d') >= DATE_FORMAT(CURDATE(), '%m-%d')")
            ->whereRaw("DATE_FORMAT(birth_date, '%m-%d') <= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 30 DAY), '%m-%d')")
            ->orderByRaw("DATE_FORMAT(birth_date, '%m-%d')")
            ->limit(5)
            ->get();

        return view('admin.hr.dashboard', compact(
            'stats',
            'recentEmployees',
            'todayAttendance',
            'pendingLeaves',
            'pendingAdvances',
            'upcomingBirthdays'
        ));
    }

    public function calendar()
    {
        $currentMonth = request('month', now()->month);
        $currentYear = request('year', now()->year);

        $startDate = Carbon::create($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Get all leaves for the month
        $leaves = Leave::with('employee')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->where('status', 'approved')
            ->get();

        // Get all attendance for the month
        $attendances = Attendance::with('employee')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get();

        return view('admin.hr.calendar', compact(
            'leaves',
            'attendances',
            'startDate',
            'endDate',
            'currentMonth',
            'currentYear'
        ));
    }

    public function reports()
    {
        return view('admin.hr.reports');
    }

    public function generateReport(Request $request)
    {
        $reportType = $request->report_type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $data = [];

        switch ($reportType) {
            case 'attendance':
                $data = $this->generateAttendanceReport($startDate, $endDate);
                break;
            case 'payroll':
                $data = $this->generatePayrollReport($startDate, $endDate);
                break;
            case 'leaves':
                $data = $this->generateLeaveReport($startDate, $endDate);
                break;
        }

        return view('admin.hr.report_result', compact('data', 'reportType', 'startDate', 'endDate'));
    }

    private function generateAttendanceReport($startDate, $endDate)
    {
        return Attendance::with('employee')
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->get()
            ->groupBy('employee_id')
            ->map(function ($records) {
                return [
                    'employee' => $records->first()->employee->full_name,
                    'present' => $records->where('status', 'present')->count(),
                    'absent' => $records->where('status', 'absent')->count(),
                    'late' => $records->where('status', 'late')->count(),
                    'leave' => $records->whereIn('status', ['vacation', 'sick_leave', 'unpaid_leave'])->count(),
                    'total_hours' => $records->sum('working_hours')
                ];
            });
    }

    private function generatePayrollReport($startDate, $endDate)
    {
        return Payroll::with('employee')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($payroll) {
                return $payroll->year . '-' . str_pad($payroll->month, 2, '0', STR_PAD_LEFT);
            })
            ->map(function ($payrolls) {
                return [
                    'period' => $payrolls->first()->month_name . ' ' . $payrolls->first()->year,
                    'count' => $payrolls->count(),
                    'total_basic' => $payrolls->sum('basic_salary'),
                    'total_overtime' => $payrolls->sum('total_overtime_amount'),
                    'total_deductions' => $payrolls->sum('deductions'),
                    'total_net' => $payrolls->sum('net_salary')
                ];
            });
    }

    private function generateLeaveReport($startDate, $endDate)
    {
        return Leave::with('employee')
            ->whereBetween('start_date', [$startDate, $endDate])
            ->get()
            ->groupBy('leave_type')
            ->map(function ($leaves) {
                return [
                    'type' => $leaves->first()->leave_type_label,
                    'count' => $leaves->count(),
                    'total_days' => $leaves->sum('days_count'),
                    'paid' => $leaves->where('is_paid', true)->sum('days_count'),
                    'unpaid' => $leaves->where('is_paid', false)->sum('days_count')
                ];
            });
    }
}
