<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\EmployeeEvaluation;
use App\Models\HR\EmployeeEvaluationItem;
use App\Models\HR\EvaluationCriterion;
use App\Models\HR\Employee;
use Illuminate\Http\Request;

class EmployeeEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeEvaluation::with(['employee', 'evaluatedBy']);

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('evaluation_period')) {
            $query->where('evaluation_period', $request->evaluation_period);
        }

        $evaluations = $query->latest()->paginate(20);
        $employees = Employee::active()->get();

        return view('admin.hr.evaluations.index', compact('evaluations', 'employees'));
    }

    public function create()
    {
        $employees = Employee::active()->get();
        $criteria = EvaluationCriterion::active()->get();
        $evaluators = Employee::active()->get();

        return view('admin.hr.evaluations.create', compact('employees', 'criteria', 'evaluators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'evaluation_date' => 'required|date',
            'evaluation_period' => 'required|in:monthly,quarterly,annual',
            'result' => 'required|in:excellent,good,average,needs_improvement',
            'notes' => 'nullable|string',
            'evaluated_by' => 'nullable|exists:employees,id',
            'criteria' => 'required|array',
            'criteria.*.criterion_id' => 'required|exists:evaluation_criteria,id',
            'criteria.*.score' => 'required|integer|min:0|max:100',
            'criteria.*.notes' => 'nullable|string'
        ]);

        // Calculate total score based on criteria weights
        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($validated['criteria'] as $criterionData) {
            $criterion = EvaluationCriterion::find($criterionData['criterion_id']);
            $weight = $criterion->weight ?? 1;
            $totalWeightedScore += ($criterionData['score'] * $weight);
            $totalWeight += $weight;
        }

        $totalScore = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight) : 0;

        $evaluation = EmployeeEvaluation::create([
            'employee_id' => $validated['employee_id'],
            'evaluation_date' => $validated['evaluation_date'],
            'evaluation_period' => $validated['evaluation_period'],
            'total_score' => $totalScore,
            'result' => $validated['result'],
            'notes' => $validated['notes'] ?? null,
            'evaluated_by' => $validated['evaluated_by'] ?? auth()->id()
        ]);

        // Create evaluation items
        foreach ($validated['criteria'] as $criterionData) {
            EmployeeEvaluationItem::create([
                'employee_evaluation_id' => $evaluation->id,
                'evaluation_criterion_id' => $criterionData['criterion_id'],
                'score' => $criterionData['score'],
                'notes' => $criterionData['notes'] ?? null
            ]);
        }

        return redirect()->route('admin.hr.evaluations.index')
            ->with('success', 'Evaluation created successfully.');
    }

    public function show(EmployeeEvaluation $evaluation)
    {
        $evaluation->load(['employee', 'evaluatedBy', 'items.criterion']);

        return view('admin.hr.evaluations.show', compact('evaluation'));
    }

    public function edit(EmployeeEvaluation $evaluation)
    {
        $employees = Employee::active()->get();
        $criteria = EvaluationCriterion::active()->get();
        $evaluators = Employee::active()->get();

        return view('admin.hr.evaluations.edit', compact('evaluation', 'employees', 'criteria', 'evaluators'));
    }

    public function update(Request $request, EmployeeEvaluation $evaluation)
    {
        $validated = $request->validate([
            'evaluation_date' => 'required|date',
            'evaluation_period' => 'required|in:monthly,quarterly,annual',
            'result' => 'required|in:excellent,good,average,needs_improvement',
            'notes' => 'nullable|string',
            'evaluated_by' => 'nullable|exists:employees,id',
            'criteria' => 'required|array',
            'criteria.*.criterion_id' => 'required|exists:evaluation_criteria,id',
            'criteria.*.score' => 'required|integer|min:0|max:100',
            'criteria.*.notes' => 'nullable|string'
        ]);

        // Calculate total score
        $totalWeightedScore = 0;
        $totalWeight = 0;

        foreach ($validated['criteria'] as $criterionData) {
            $criterion = EvaluationCriterion::find($criterionData['criterion_id']);
            $weight = $criterion->weight ?? 1;
            $totalWeightedScore += ($criterionData['score'] * $weight);
            $totalWeight += $weight;
        }

        $totalScore = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight) : 0;

        $evaluation->update([
            'evaluation_date' => $validated['evaluation_date'],
            'evaluation_period' => $validated['evaluation_period'],
            'total_score' => $totalScore,
            'result' => $validated['result'],
            'notes' => $validated['notes'] ?? null,
            'evaluated_by' => $validated['evaluated_by'] ?? auth()->id()
        ]);

        // Delete old items and create new ones
        $evaluation->items()->delete();
        foreach ($validated['criteria'] as $criterionData) {
            EmployeeEvaluationItem::create([
                'employee_evaluation_id' => $evaluation->id,
                'evaluation_criterion_id' => $criterionData['criterion_id'],
                'score' => $criterionData['score'],
                'notes' => $criterionData['notes'] ?? null
            ]);
        }

        return redirect()->route('admin.hr.evaluations.index')
            ->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(EmployeeEvaluation $evaluation)
    {
        $evaluation->items()->delete();
        $evaluation->delete();

        return redirect()->route('admin.hr.evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }
}
