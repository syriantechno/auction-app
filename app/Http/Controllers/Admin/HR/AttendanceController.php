<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Attendance;
use App\Models\HR\Employee;
use App\Models\HR\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee', 'shift']);

        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        } else {
            $query->where('attendance_date', today());
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(50);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $attendances
            ]);
        }

        $employees = Employee::active()->get();

        // Statistics
        $date = $request->date ?? today();
        $stats = [
            'total' => Attendance::where('attendance_date', $date)->count(),
            'present' => Attendance::where('attendance_date', $date)->where('status', 'present')->count(),
            'absent' => Attendance::where('attendance_date', $date)->where('status', 'absent')->count(),
            'late' => Attendance::where('attendance_date', $date)->where('status', 'late')->count(),
            'on_leave' => Attendance::where('attendance_date', $date)->whereIn('status', ['vacation', 'sick_leave', 'unpaid_leave'])->count()
        ];

        return view('admin.hr.attendance.index', compact('attendances', 'employees', 'stats', 'date'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $shifts = Shift::active()->get();

        return view('admin.hr.attendance.create', compact('employees', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'shift_id' => 'nullable|exists:shifts,id',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,vacation,travel,half_day,holiday,sick_leave,unpaid_leave,late,early_departure,weekend',
            'notes' => 'nullable|string'
        ]);

        // Check if attendance already exists
        $existing = Attendance::where('employee_id', $validated['employee_id'])
            ->where('attendance_date', $validated['attendance_date'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Attendance record already exists for this employee on this date.');
        }

        // Calculate working hours and overtime
        if ($validated['check_in'] && $validated['check_out']) {
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $diffInHours = $checkIn->diffInMinutes($checkOut) / 60;
            $validated['working_hours'] = round($diffInHours, 2);
        }

        $attendance = Attendance::create($validated);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully.',
                'data' => $attendance->load(['employee', 'shift'])
            ]);
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::active()->get();
        $shifts = Shift::active()->get();

        return view('admin.hr.attendance.edit', compact('attendance', 'employees', 'shifts'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'shift_id' => 'nullable|exists:shifts,id',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,vacation,travel,half_day,holiday,sick_leave,unpaid_leave,late,early_departure,weekend',
            'notes' => 'nullable|string'
        ]);

        // Calculate working hours and overtime
        if ($validated['check_in'] && $validated['check_out']) {
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $diffInHours = $checkIn->diffInMinutes($checkOut) / 60;
            $validated['working_hours'] = round($diffInHours, 2);
        }

        $attendance->update($validated);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance updated successfully.',
                'data' => $attendance->load(['employee', 'shift'])
            ]);
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance record deleted successfully.'
            ]);
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', 'Attendance record deleted successfully.');
    }

    public function bulkCreate()
    {
        $employees = Employee::active()->get();
        $shifts = Shift::active()->get();

        return view('admin.hr.attendance.bulk-create', compact('employees', 'shifts'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'status' => 'required|in:present,absent,vacation,holiday',
            'notes' => 'nullable|string'
        ]);

        $startDate = Carbon::parse($validated['date_from']);
        $endDate = Carbon::parse($validated['date_to']);
        $createdCount = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            foreach ($validated['employee_ids'] as $employeeId) {
                // Skip weekends if status is not holiday
                if ($validated['status'] !== 'holiday' && in_array($date->dayOfWeek, [0, 6])) {
                    continue;
                }

                // Check if already exists
                $exists = Attendance::where('employee_id', $employeeId)
                    ->where('attendance_date', $date->format('Y-m-d'))
                    ->exists();

                if (!$exists) {
                    Attendance::create([
                        'employee_id' => $employeeId,
                        'attendance_date' => $date->format('Y-m-d'),
                        'status' => $validated['status'],
                        'notes' => $validated['notes'] ?? null
                    ]);
                    $createdCount++;
                }
            }
        }

        return redirect()->route('admin.hr.attendance.index')
            ->with('success', "{$createdCount} attendance records created successfully.");
    }

    public function report(Request $request)
    {
        $employeeId = $request->employee_id;
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $employee = null;
        if ($employeeId) {
            $employee = Employee::with(['attendances' => function($query) use ($month, $year) {
                $query->whereMonth('attendance_date', $month)
                      ->whereYear('attendance_date', $year);
            }])->find($employeeId);
        }

        $employees = Employee::active()->get();

        return view('admin.hr.attendance.report', compact('employee', 'employees', 'month', 'year'));
    }
}
