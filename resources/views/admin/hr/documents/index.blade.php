@extends('admin.layout')

@section('title', 'Documents')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Employee Documents</h1>
            <p class="text-slate-500 mt-1">Manage employee documentation and records</p>
        </div>
        <a href="{{ route('admin.hr.documents.create') }}" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg hover:bg-[#e85a00] transition-colors">
            Add Document
        </a>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Document Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Document Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($documents as $document)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ strtoupper(substr($document->employee->first_name ?? 'E', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $document->employee->full_name ?? 'Unknown' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $documentTypes[$document->document_type] ?? $document->document_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $document->document_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900">{{ $document->expiry_date ? $document->expiry_date->format('M d, Y') : 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $document->status_badge['class'] }}">
                                    {{ $document->status_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.hr.documents.edit', $document) }}" class="text-slate-600 hover:text-slate-900 mr-3">Edit</a>
                                @if($document->file_path)
                                    <a href="{{ route('admin.hr.documents.download', $document) }}" class="text-blue-600 hover:text-blue-900 mr-3">Download</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin-empty-state 
                                    title="No Documents Found" 
                                    subtitle="The document repository is currently empty. Employee records will appear here once uploaded." 
                                    icon="file-text" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
