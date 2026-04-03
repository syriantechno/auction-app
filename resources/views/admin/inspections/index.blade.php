@extends('admin.layout')

@section('title', 'Valuation Reports')
@section('page_title', 'Valuation Reports')

@section('content')
<div class="px-1 space-y-6">

    <x-admin-header icon="clipboard-check" title="Inspection Reports"
        subtitle="Vehicle inspection & valuation records">
        <x-slot name="actions">
            <a href="{{ route('admin.inspections.create') }}" class="px-6 h-11 bg-[#ff4605] text-white rounded-lg font-black shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all text-[0.7rem] uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> New Inspection
            </a>
        </x-slot>
    </x-admin-header>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-md mb-6 font-bold border border-emerald-100 flex items-center gap-2 text-xs">
            <i data-lucide="check-circle" class="w-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-[#f1f5f9] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#f1f5f9] bg-[#f8fafc]">
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Vehicle</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest text-center">Score</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Inspector</th>
                    <th class="text-left text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Date</th>
                    <th class="text-right text-[0.6rem] text-[#adb5bd] uppercase py-4 px-6 font-black tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f1f5f9]">
                @forelse($reports as $report)
                <tr class="hover:bg-[#fbfcfe] transition-all">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-50 border border-gray-100 shadow-sm flex items-center justify-center">
                                <img src="{{ optional($report->car)->image_url ?? '/images/cars/car-main.jpg' }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="font-extrabold text-[0.85rem] text-[#111827]">{{ optional($report->car)->year }} {{ optional($report->car)->make }} {{ optional($report->car)->model }}</div>
                                <div class="text-[0.6rem] text-[#adb5bd] font-bold uppercase tracking-wider mt-0.5">Report ID: #RP-{{ $report->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $scoreColor = $report->overall_score >= 80 ? 'text-emerald-600 bg-emerald-50' : ($report->overall_score >= 50 ? 'text-amber-600 bg-amber-50' : 'text-red-500 bg-red-50');
                        @endphp
                        <div class="inline-flex flex-col items-center justify-center p-2 rounded-md border border-gray-100 shadow-sm {{ $scoreColor }}">
                            <span class="text-lg font-black leading-none tabular-nums">{{ $report->overall_score }}</span>
                            <span class="text-[0.45rem] font-black uppercase tracking-widest opacity-80 mt-1 leading-none">OVERALL</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-400 font-bold text-[0.6rem] shadow-inner uppercase tracking-widest">EXP</div>
                            <div class="font-bold text-[0.75rem] text-[#111827]">{{ optional($report->expert)->name ?? 'Auditor X' }}</div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-[0.75rem] text-[#adb5bd] font-bold tabular-nums">
                        {{ $report->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.inspections.show', $report) }}" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-[#111827] hover:text-white transition-all shadow-sm">
                                <i data-lucide="book-open" class="w-3.5"></i>
                            </a>
                            <form action="{{ route('admin.inspections.destroy', $report) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Archive this audit record?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <i data-lucide="trash-2" class="w-3.5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-20 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.7rem]">Technical ledger is empty. No audits finalized.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reports->hasPages())
        <div class="mt-6 px-1">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection

