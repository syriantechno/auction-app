@extends('admin.layout')

@section('title', 'Payrolls')

@section('content')
    <x-admin-page-standard 
        icon="banknote" 
        title="Payroll" 
        highlight="Management" 
        subtitle="Generate and manage employee salaries"
        dot="emerald">
        
        <x-slot name="actions">
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.hr.payrolls.generate-bulk') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="h-14 px-6 border-2 border-slate-100 rounded-2xl text-[0.7rem] font-black uppercase text-slate-600 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm italic flex items-center gap-2">
                        <i data-lucide="zap" class="w-4 h-4"></i>
                        Generate Bulk
                    </button>
                </form>
                <a href="{{ route('admin.hr.payrolls.create') }}" class="group bg-slate-900 hover:bg-[#ff6900] text-white px-8 py-4 rounded-2xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-200">
                    <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-500 text-[#ff6900] group-hover:text-white"></i>
                    Generate Payroll
                </a>
            </div>
        </x-slot>

    <!-- Month/Year Filter -->
    <div class="bg-white rounded-xl border border-slate-100 p-4">
        <form method="GET" class="flex items-center gap-4">
            <select name="month" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                    </option>
                @endfor
            </select>
            
            <select name="year" class="px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#ff6900]">
                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-slate-900">{{ $summary['total_payrolls'] }}</p>
                <p class="text-sm text-slate-500">Total Payrolls</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($summary['total_net_salary'], 2) }}</p>
                <p class="text-sm text-slate-500">Total Net Salary</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $summary['approved_count'] }}</p>
                <p class="text-sm text-slate-500">Approved</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ $summary['paid_count'] }}</p>
                <p class="text-sm text-slate-500">Paid</p>
            </div>
        </div>
    </div>

    <!-- Payrolls Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Basic Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Deductions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Net Salary</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ strtoupper(substr($payroll->employee->first_name ?? 'E', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $payroll->employee->full_name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $payroll->month_name }} {{ $payroll->year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ number_format($payroll->basic_salary, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ number_format($payroll->deductions, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ number_format($payroll->net_salary, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $payroll->status_badge['class'] }}">
                                    {{ $payroll->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.payrolls.show', $payroll) }}" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                @if($payroll->status === 'draft' || $payroll->status === 'calculated')
                                    <a href="{{ route('admin.hr.payrolls.edit', $payroll) }}" class="text-slate-600 hover:text-slate-900 mr-2">Edit</a>
                                @endif
                                @if($payroll->status === 'calculated')
                                    <form action="{{ route('admin.hr.payrolls.approve', $payroll) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                    </form>
                                @endif
                                @if($payroll->status === 'approved')
                                    <form action="{{ route('admin.hr.payrolls.pay', $payroll) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Pay</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin-empty-state 
                                    title="No Payroll Records" 
                                    subtitle="The salary registry is currently empty. Staff records will appear here once generated." 
                                    icon="banknote" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payrolls->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
    </x-admin-page-standard>
@endsection
