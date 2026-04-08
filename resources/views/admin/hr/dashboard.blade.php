@extends('admin.layout')

@section('title', 'HR Dashboard')

@section('content')
<x-admin-page-standard 
    icon="users" 
    title="HR" 
    highlight="Dashboard"
    subtitle="Manage your human resources efficiently"
    dot="orange">
    
    <x-slot name="actions">
        <x-admin-button icon="plus" href="{{ route('admin.hr.employees.create') }}">Add Employee</x-admin-button>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-admin-stat-card label="Total" value="{{ $stats['total_employees'] }}" icon="users" color="slate" />
        <x-admin-stat-card label="Present" value="{{ $stats['present_today'] }}" icon="user-check" color="emerald" />
        <x-admin-stat-card label="Absent" value="{{ $stats['absent_today'] ?? 0 }}" icon="user-x" color="rose" />
        <x-admin-stat-card label="Late" value="{{ $stats['late_today'] ?? 0 }}" icon="clock" color="orange" />
        <x-admin-stat-card label="On Leave" value="{{ $stats['on_leave'] }}" icon="calendar-x" color="blue" />
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <!-- Recent Employees -->
        <div class="bg-white rounded-xl border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900">Recent Employees</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentEmployees as $employee)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-slate-600">
                                        {{ strtoupper(substr($employee->first_name ?? 'E', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $employee->full_name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-slate-500">{{ $employee->department->name ?? 'No Department' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">{{ $employee->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-4">No employees yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-white rounded-xl border border-slate-100">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-semibold text-slate-900">Today's Attendance</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($todayAttendance as $attendance)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $attendance->status == 'present' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <span class="text-sm font-medium {{ $attendance->status == 'present' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ strtoupper(substr($attendance->employee->first_name ?? 'A', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $attendance->employee->full_name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-slate-500">{{ $attendance->status }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($attendance->check_in)
                                    <p class="text-sm text-slate-500">{{ $attendance->check_in }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-4">No attendance records for today</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-slate-100 p-6 mt-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.hr.attendance.create') }}" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Mark Attendance</span>
            </a>
            <a href="{{ route('admin.hr.leaves.create') }}" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calendar-minus" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Request Leave</span>
            </a>
            <a href="{{ route('admin.hr.payrolls.create') }}" class="flex items-center gap-3 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i data-lucide="calculator" class="w-5 h-5 text-slate-600"></i>
                <span class="font-medium text-slate-900">Generate Payroll</span>
            </a>
        </div>
    </div>
</x-admin-page-standard>

<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
});
</script>
@endsection
