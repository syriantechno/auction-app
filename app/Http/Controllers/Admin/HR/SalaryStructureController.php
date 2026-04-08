<?php

namespace App\Http\Controllers\Admin\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\SalaryStructure;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::withCount('employees')
            ->latest()
            ->paginate(20);

        return view('admin.hr.salary_structures.index', compact('structures'));
    }

    public function create()
    {
        return view('admin.hr.salary_structures.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string',
            'components.*.amount' => 'required_with:components|numeric|min:0',
            'components.*.type' => 'required_with:components|in:allowance,deduction',
            'is_active' => 'boolean'
        ]);

        $validated['code'] = $this->generateStructureCode();
        $validated['is_active'] = $request->boolean('is_active', true);

        // Format components
        if (!empty($validated['components'])) {
            $components = [];
            foreach ($validated['components'] as $component) {
                $components[] = [
                    'name' => $component['name'],
                    'amount' => $component['amount'],
                    'type' => $component['type']
                ];
            }
            $validated['components'] = $components;
        }

        SalaryStructure::create($validated);

        return redirect()->route('admin.hr.salary_structures.index')
            ->with('success', 'Salary structure created successfully.');
    }

    public function edit(SalaryStructure $salaryStructure)
    {
        return view('admin.hr.salary_structures.edit', compact('salaryStructure'));
    }

    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'nullable|array',
            'components.*.name' => 'required_with:components|string',
            'components.*.amount' => 'required_with:components|numeric|min:0',
            'components.*.type' => 'required_with:components|in:allowance,deduction',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        // Format components
        if (!empty($validated['components'])) {
            $components = [];
            foreach ($validated['components'] as $component) {
                $components[] = [
                    'name' => $component['name'],
                    'amount' => $component['amount'],
                    'type' => $component['type']
                ];
            }
            $validated['components'] = $components;
        }

        $salaryStructure->update($validated);

        return redirect()->route('admin.hr.salary_structures.index')
            ->with('success', 'Salary structure updated successfully.');
    }

    public function destroy(SalaryStructure $salaryStructure)
    {
        if ($salaryStructure->employees()->count() > 0) {
            return redirect()->route('admin.hr.salary_structures.index')
                ->with('error', 'Cannot delete salary structure assigned to employees.');
        }

        $salaryStructure->delete();

        return redirect()->route('admin.hr.salary_structures.index')
            ->with('success', 'Salary structure deleted successfully.');
    }

    private function generateStructureCode(): string
    {
        $prefix = 'STR';
        $count = SalaryStructure::count() + 1;
        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
