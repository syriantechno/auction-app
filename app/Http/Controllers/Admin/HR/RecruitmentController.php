<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Recruitment;
use App\Models\HR\Department;
use App\Models\HR\Position;
use Illuminate\Http\Request;

class RecruitmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Recruitment::with(['department', 'position']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $recruitments = $query->latest()->paginate(20);
        $departments = Department::active()->get();

        return view('admin.hr.recruitments.index', compact('recruitments', 'departments'));
    }

    public function create()
    {
        $departments = Department::active()->get();
        $positions = Position::active()->get();

        return view('admin.hr.recruitments.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'requirements' => 'nullable|string',
            'vacancies' => 'required|integer|min:1',
            'salary_range_from' => 'nullable|numeric|min:0',
            'salary_range_to' => 'nullable|numeric|gte:salary_range_from',
            'opening_date' => 'required|date',
            'closing_date' => 'nullable|date|after_or_equal:opening_date'
        ]);

        $validated['code'] = $this->generateRecruitmentCode();
        $validated['status'] = 'open';
        $validated['created_by'] = auth()->id();

        Recruitment::create($validated);

        return redirect()->route('admin.hr.recruitments.index')
            ->with('success', 'Job opening created successfully.');
    }

    public function show(Recruitment $recruitment)
    {
        $recruitment->load(['department', 'position', 'createdBy']);

        return view('admin.hr.recruitments.show', compact('recruitment'));
    }

    public function edit(Recruitment $recruitment)
    {
        if (!in_array($recruitment->status, ['open', 'in_progress'])) {
            return redirect()->route('admin.hr.recruitments.index')
                ->with('error', 'Cannot edit filled or closed job openings.');
        }

        $departments = Department::active()->get();
        $positions = Position::active()->get();

        return view('admin.hr.recruitments.edit', compact('recruitment', 'departments', 'positions'));
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        if (!in_array($recruitment->status, ['open', 'in_progress'])) {
            return redirect()->route('admin.hr.recruitments.index')
                ->with('error', 'Cannot edit filled or closed job openings.');
        }

        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'requirements' => 'nullable|string',
            'vacancies' => 'required|integer|min:1',
            'salary_range_from' => 'nullable|numeric|min:0',
            'salary_range_to' => 'nullable|numeric|gte:salary_range_from',
            'opening_date' => 'required|date',
            'closing_date' => 'nullable|date|after_or_equal:opening_date'
        ]);

        $recruitment->update($validated);

        return redirect()->route('admin.hr.recruitments.index')
            ->with('success', 'Job opening updated successfully.');
    }

    public function close(Recruitment $recruitment)
    {
        if ($recruitment->status === 'closed') {
            return redirect()->back()->with('error', 'Job opening is already closed.');
        }

        $recruitment->update([
            'status' => 'closed',
            'closing_date' => now()
        ]);

        return redirect()->back()->with('success', 'Job opening closed successfully.');
    }

    public function fill(Recruitment $recruitment)
    {
        if ($recruitment->status !== 'open' && $recruitment->status !== 'in_progress') {
            return redirect()->back()->with('error', 'Job opening must be open or in progress to be filled.');
        }

        $recruitment->update([
            'status' => 'filled',
            'closing_date' => now()
        ]);

        return redirect()->back()->with('success', 'Job opening marked as filled successfully.');
    }

    public function destroy(Recruitment $recruitment)
    {
        if ($recruitment->status === 'filled') {
            return redirect()->back()->with('error', 'Cannot delete filled job openings.');
        }

        $recruitment->delete();

        return redirect()->route('admin.hr.recruitments.index')
            ->with('success', 'Job opening deleted successfully.');
    }

    private function generateRecruitmentCode(): string
    {
        $prefix = 'JOB';
        $year = date('Y');
        $count = Recruitment::whereYear('created_at', $year)->count() + 1;
        return $prefix . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
