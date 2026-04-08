@extends('admin.layout')

@section('title', 'Rewards')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Employee Rewards</h1>
            <p class="text-slate-500 mt-1">Recognize and reward employee achievements</p>
        </div>
        <a href="{{ route('admin.hr.rewards.create') }}" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
            Add Reward
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 border border-slate-100">
            <div class="text-center">
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_rewards'] }}</p>
                <p class="text-sm text-slate-500">Total Rewards</p>
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
                <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_unpaid'], 2) }}</p>
                <p class="text-sm text-slate-500">Unpaid Amount</p>
            </div>
        </div>
    </div>

    <!-- Rewards Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Reward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($rewards as $reward)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ strtoupper(substr($reward->employee->first_name ?? 'E', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $reward->employee->full_name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $reward->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $reward->type_badge['class'] }}">
                                    {{ $reward->type_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $reward->reward_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ number_format($reward->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($reward->is_paid)
                                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Paid</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.rewards.show', $reward) }}" class="text-[#ff6900] hover:text-[#e85a00] mr-3">View</a>
                                @if(!$reward->is_paid)
                                    <a href="{{ route('admin.hr.rewards.edit', $reward) }}" class="text-slate-600 hover:text-slate-900 mr-2">Edit</a>
                                    <form action="{{ route('admin.hr.rewards.pay', $reward) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">Pay</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin-empty-state 
                                    title="No Rewards Issued" 
                                    subtitle="The achievement log is currently empty. Employee recognitions will appear here." 
                                    icon="trophy" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($rewards->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $rewards->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
