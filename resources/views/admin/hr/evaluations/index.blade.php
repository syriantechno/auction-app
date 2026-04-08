@extends('admin.layout')

@section('title', 'Evaluations')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Employee Evaluations</h1>
            <p class="text-slate-500 mt-1">Performance reviews and assessments</p>
        </div>
        <a href="{{ route('admin.hr.evaluations.create') }}" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
            New Evaluation
        </a>
    </div>

    <!-- Evaluations Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Result</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Evaluated By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($evaluations as $evaluation)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ strtoupper(substr($evaluation->employee->first_name ?? 'E', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $evaluation->employee->full_name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $evaluation->evaluation_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ ucfirst($evaluation->evaluation_period) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $evaluation->total_score }}/100</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $evaluation->result_badge['class'] }}">
                                    {{ $evaluation->result_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $evaluation->evaluatedBy->full_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.evaluations.show', $evaluation) }}" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                <a href="{{ route('admin.hr.evaluations.edit', $evaluation) }}" class="text-slate-600 hover:text-slate-900">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin-empty-state 
                                    title="No Evaluations Found" 
                                    subtitle="The performance record is currently empty. Assessments will appear here once finalized." 
                                    icon="trending-up" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($evaluations->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $evaluations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
