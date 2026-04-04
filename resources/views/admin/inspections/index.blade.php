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
                @php
                    $car = $report->car;
                    $rawMake = strtolower($car->make ?? 'generic');
                    $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                    $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                    if (str_contains($rawMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                    $finalLogo = null;
                    foreach ($searchPaths as $p) { if(file_exists(public_path($p))) { $finalLogo = $p; break; } }
                @endphp
                <tr id="report-row-{{ $report->id }}" class="hover:bg-[#fbfcfe] transition-all">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center p-2.5 shrink-0 shadow-sm group transition-all hover:bg-white">
                                @if($finalLogo)
                                    <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain filter drop-shadow-sm opacity-60 group-hover:opacity-100 transition-opacity">
                                @else
                                    <i data-lucide="car-front" class="w-6 h-6 text-slate-200"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-black text-[0.85rem] text-[#111827] uppercase italic leading-none tracking-tight">{{ optional($report->car)->make }} {{ optional($report->car)->model }}</div>
                                <div class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest mt-2 flex items-center gap-2">
                                    <span class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 text-slate-400 italic">Production Year: {{ optional($report->car)->year }}</span>
                                    <span class="text-emerald-500 italic">ID: #RP-{{ $report->id }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @php
                            $scoreColor = $report->overall_score >= 80 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : ($report->overall_score >= 50 ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-red-500 bg-red-50 border-red-100');
                        @endphp
                        <div class="inline-flex flex-col items-center justify-center p-2 rounded-xl border {{ $scoreColor }} shadow-sm">
                            <span class="text-xl font-black leading-none tabular-nums">{{ $report->overall_score }}</span>
                            <span class="text-[0.4rem] font-black uppercase tracking-widest opacity-80 mt-1 leading-none">SCORE</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-400 font-black text-[0.55rem] shadow-sm uppercase tracking-widest">EXP</div>
                            <div class="font-black text-[0.75rem] text-[#111827] italic tracking-tight">{{ optional($report->expert)->name ?? 'Auditor X' }}</div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-[0.75rem] text-slate-400 font-bold tabular-nums italic">
                        {{ $report->created_at->format('d-m-Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.inspections.show', $report) }}" class="w-8 h-8 rounded-lg bg-gray-50 text-[#5a6a85] flex items-center justify-center hover:bg-[#111827] hover:text-white transition-all shadow-sm">
                                <i data-lucide="book-open" class="w-3.5"></i>
                            </a>
                             <form id="delete-report-{{ $report->id }}" action="{{ route('admin.inspections.destroy', $report) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                    onclick="archiveReport({{ $report->id }}, '{{ route('admin.inspections.destroy', $report) }}')"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-400 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
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

@push('scripts')
<script>
    function archiveReport(reportId, url) {
        Swal.fire({
            title: 'Archive Audit?',
            text: 'This record will be moved to the archive vault.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff4605',
            cancelButtonColor: '#1e293b',
            confirmButtonText: 'Yes, Archive',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({
                        '_method': 'DELETE'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById('report-row-' + reportId);
                        row.classList.add('opacity-0', '-translate-x-4', 'transition-all', 'duration-500');
                        setTimeout(() => row.remove(), 500);
                        Toastify({ text: data.message, style: { background: "#1e293b", color: "#fff", borderRadius: "1rem" } }).showToast();
                    } else {
                        Swal.fire('Error!', data.message || 'Processing failed.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error!', 'System communication failure.', 'error');
                });
            }
        });
    }
</script>
@endpush

