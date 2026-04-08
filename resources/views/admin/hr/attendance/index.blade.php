@extends('admin.layout')

@section('title', 'Attendance Manager')

@section('content')
<div x-data="attendanceManager()" x-init="initComponent()">
    <x-admin-page-standard 
        icon="clipboard-check" 
        title="Attendance" 
        highlight="Manager"
        subtitle="Monitor daily attendance logs and working hours"
        dot="orange">
        
        <x-slot name="actions">
            <x-admin-button variant="secondary" icon="users" href="{{ route('admin.hr.attendance.bulk.create') }}">Bulk Mark</x-admin-button>
            <x-admin-button icon="plus" href="{{ route('admin.hr.attendance.create') }}">Mark Attendance</x-admin-button>
        </x-slot>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <x-admin-stat-card label="Total" value="{{ $stats['total'] }}" icon="users" color="slate" />
            <x-admin-stat-card label="Present" value="{{ $stats['present'] }}" icon="user-check" color="emerald" />
            <x-admin-stat-card label="Absent" value="{{ $stats['absent'] }}" icon="user-x" color="rose" />
            <x-admin-stat-card label="Late" value="{{ $stats['late'] }}" icon="clock" color="orange" />
            <x-admin-stat-card label="On Leave" value="{{ $stats['on_leave'] }}" icon="calendar-x" color="blue" />
        </div>

        <!-- Filters -->
        <x-admin-filter-bar class="mt-6">
            <div class="w-48">
                <x-elite-date dateName="filter_date" label="Select Date" />
            </div>
            <div class="w-48">
                <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block mb-2">Employee</label>
                <x-elite-select icon="user" name="employee_id" x-model="employeeFilter">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                    @endforeach
                </x-elite-select>
            </div>
            <div class="w-48">
                <label class="text-[0.65rem] font-black uppercase text-slate-500 tracking-[0.2em] ml-1 block mb-2">Status</label>
                <x-elite-select icon="tag" name="status" x-model="statusFilter">
                    <option value="">All Status</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="vacation">Vacation</option>
                </x-elite-select>
            </div>
            <div class="w-48 pt-[22px]">
                <x-admin-button variant="secondary" icon="rotate-ccw" @click="resetFilters()">Reset</x-admin-button>
            </div>
        </x-admin-filter-bar>

        <!-- Attendance Data View -->
        <x-admin-data-view count="{{ $attendances->count() }}">
            @forelse($attendances as $attendance)
                <x-admin-data-item 
                    number="{{ $loop->iteration }}" 
                    title="{{ $attendance->employee->full_name ?? 'Unknown' }}"
                    subtitle="{{ $attendance->attendance_date->format('M d, Y') }} | {{ $attendance->check_in ?? '&ndash;&ndash;:&ndash;&ndash;' }} - {{ $attendance->check_out ?? '&ndash;&ndash;:&ndash;&ndash;' }}"
                    :highlight="true">
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 text-[0.65rem] font-black uppercase tracking-wider rounded-full {{ $attendance->status_badge['class'] }}">
                            {{ $attendance->status_badge['label'] }}
                        </span>
                        <span class="text-[0.75rem] font-bold text-slate-500">{{ $attendance->working_hours ?? '0' }}h</span>
                        <x-admin-action icon="edit-3" title="Edit" variant="slate" href="{{ route('admin.hr.attendance.edit', $attendance) }}" />
                    </div>
                </x-admin-data-item>
            @empty
                <x-admin-empty-state 
                    title="No Attendance Records" 
                    subtitle="No attendance logs found for the selected filters."
                    icon="clipboard-x" />
            @endforelse
            
            @if($attendances->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $attendances->links() }}
                </div>
            @endif
        </x-admin-data-view>
    </x-admin-page-standard>
</div>

<script>
function attendanceManager() {
    return {
        employeeFilter: "{{ request('employee_id') }}",
        statusFilter: "{{ request('status') }}",
        
        initComponent() {
            this.$nextTick(() => { 
                if (typeof lucide !== 'undefined') lucide.createIcons(); 
            });
        },
        
        resetFilters() {
            this.employeeFilter = '';
            this.statusFilter = '';
            window.location.href = '{{ route('admin.hr.attendance.index') }}';
        }
    }
}
</script>
@endsection
