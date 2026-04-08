<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeDocument;
use App\Models\HR\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeDocument::with(['employee']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $documents = $query->latest()->paginate(20);
        $employees = Employee::active()->get();
        $documentTypes = $this->getDocumentTypes();

        return view('admin.hr.documents.index', compact('documents', 'employees', 'documentTypes'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $documentTypes = $this->getDocumentTypes();

        return view('admin.hr.documents.create', compact('employees', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'document_type' => 'required|string|max:255',
            'document_number' => 'required|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'issuing_authority' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240', // Max 10MB
            'notes' => 'nullable|string'
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('employee_documents', $filename, 'public');
            $validated['file_path'] = $path;
        }

        $validated['is_active'] = true;

        EmployeeDocument::create($validated);

        return redirect()->route('admin.hr.documents.index')
            ->with('success', 'Document created successfully.');
    }

    public function edit(EmployeeDocument $document)
    {
        $employees = Employee::active()->get();
        $documentTypes = $this->getDocumentTypes();

        return view('admin.hr.documents.edit', compact('document', 'employees', 'documentTypes'));
    }

    public function update(Request $request, EmployeeDocument $document)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'document_number' => 'required|string|max:255',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'issuing_authority' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('employee_documents', $filename, 'public');
            $validated['file_path'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $document->update($validated);

        return redirect()->route('admin.hr.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(EmployeeDocument $document)
    {
        // Delete file
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.hr.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(EmployeeDocument $document)
    {
        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path);
    }

    private function getDocumentTypes(): array
    {
        return [
            'passport' => 'Passport',
            'national_id' => 'National ID',
            'iqama' => 'Iqama/Residence',
            'driving_license' => 'Driving License',
            'contract' => 'Employment Contract',
            'medical_certificate' => 'Medical Certificate',
            'degree' => 'Educational Degree',
            'training_certificate' => 'Training Certificate',
            'other' => 'Other'
        ];
    }
}
