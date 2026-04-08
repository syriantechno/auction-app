@extends('admin.layout')

@section('title', 'Recruitment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Recruitment Management</h1>
            <p class="text-slate-500 mt-1">Manage job openings and recruitment process</p>
        </div>
        <a href="{{ route('admin.hr.recruitments.create') }}" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
            Post Job Opening
        </a>
    </div>

    <!-- Recruitment Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Job Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vacancies</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Salary Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Posted Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($recruitments as $recruitment)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ $recruitment->job_title }}</div>
                                    <div class="text-sm text-slate-500">{{ $recruitment->code }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $recruitment->department->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $recruitment->vacancies }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $recruitment->salary_range }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $recruitment->opening_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $recruitment->status_badge['class'] }}">
                                    {{ $recruitment->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.recruitments.show', $recruitment) }}" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                @if(in_array($recruitment->status, ['open', 'in_progress']))
                                    <a href="{{ route('admin.hr.recruitments.edit', $recruitment) }}" class="text-slate-600 hover:text-slate-900 mr-2">Edit</a>
                                @endif
                                @if($recruitment->status === 'open')
                                    <form action="{{ route('admin.hr.recruitments.close', $recruitment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-orange-600 hover:text-orange-900 mr-2">Close</button>
                                    </form>
                                @endif
                                @if(in_array($recruitment->status, ['open', 'in_progress']))
                                    <form action="{{ route('admin.hr.recruitments.fill', $recruitment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-purple-600 hover:text-purple-900">Fill</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin-empty-state 
                                    title="No Job Openings" 
                                    subtitle="The recruitment board is currently empty. Open positions will appear here." 
                                    icon="briefcase" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($recruitments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $recruitments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
