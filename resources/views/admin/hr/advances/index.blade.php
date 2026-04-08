@extends('admin.layout')

@section('title', 'Advances')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Salary Advances</h1>
            <p class="text-slate-500 mt-1">Manage employee advance requests</p>
        </div>
        <a href="{{ route('admin.hr.advances.create') }}" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
            Request Advance
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_pending'], 2) }}</p>
                <p class="text-sm text-slate-500">Pending Amount</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_paid'], 2) }}</p>
                <p class="text-sm text-slate-500">Paid Amount</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_remaining'], 2) }}</p>
                <p class="text-sm text-slate-500">Remaining Amount</p>
            </div>
        </div>
    </div>

    <!-- Advances Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Request Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Installments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($advances as $advance)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ strtoupper(substr($advance->employee->first_name ?? 'E', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $advance->employee->full_name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ number_format($advance->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $advance->request_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $advance->installments_count }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ number_format($advance->remaining_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $advance->status_badge['class'] }}">
                                    {{ $advance->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.advances.show', $advance) }}" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                @if($advance->status === 'pending')
                                    <form action="{{ route('admin.hr.advances.approve', $advance) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.hr.advances.reject', $advance) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                    </form>
                                @endif
                                @if($advance->status === 'approved')
                                    <form action="{{ route('admin.hr.advances.pay', $advance) }}" method="POST" class="inline">
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
                                    title="No Advance Requests" 
                                    subtitle="The advance ledger is currently empty. Staff requests will appear here once finalized." 
                                    icon="hand-coins" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($advances->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $advances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
