<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\InspectionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InspectionController extends Controller
{
    public function index()
    {
        $reports = InspectionReport::with(['car', 'expert'])->latest()->paginate(20);
        return view('admin.inspections.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $lead = null;
        if ($request->has('lead_id')) {
            $lead = \App\Models\Lead::find($request->lead_id);
        }

        $selectedCar = null;
        if ($request->has('car_id')) {
            $selectedCar = Car::find($request->car_id);
        }

        // AUTO-RESOLVE CAR FOR LEADS: Seamless flow from CRM to Technical Audit
        if ($lead && !$selectedCar) {
            $details = $lead->car_details;
            $make = $details['make'] ?? null;
            $model = $details['model'] ?? null;
            $year = $details['year'] ?? null;
            
            if ($make && $model) {
                [$brandId, $modelId, $canonicalMake, $canonicalModel] = $this->resolveBrandAndModel($make, $model);

                // Try matching by precise attributes to avoid duplicate assets
                $selectedCar = Car::where('brand_id', $brandId)
                    ->where('car_model_id', $modelId)
                    ->where('year', $year)
                    ->first();

                // If not found, create a new master record for this vehicle asset
                if (!$selectedCar) {
                    $selectedCar = Car::create([
                        'brand_id' => $brandId,
                        'car_model_id' => $modelId,
                        'make' => $canonicalMake,
                        'model' => $canonicalModel,
                        'year' => $year,
                        'vin' => $details['vin'] ?? 'AUTO-' . strtoupper(Str::random(8)),
                        'status' => 'pending_inspection',
                        'ownership_type' => 'consignment'
                    ]);
                }
            }
        }

        // Only load a subset of cars for the select or if a car is pre-selected, just that one.
        $cars = $selectedCar ? collect([$selectedCar]) : Car::latest()->take(50)->get();

        return view('admin.inspections.create', compact('cars', 'selectedCar', 'lead'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'lead_id' => 'nullable|exists:leads,id',
            'paint_score' => 'required|integer|min:0|max:100',
            'engine_score' => 'required|integer|min:0|max:100',
            'transmission_score' => 'required|integer|min:0|max:100',
            'interior_score' => 'required|integer|min:0|max:100',
            'tires_score' => 'required|integer|min:0|max:100',
            'body_notes' => 'nullable|string',
            'engine_notes' => 'nullable|string',
            'expert_summary' => 'required|string',
        ]);

        // Calculate weighted overall score
        $overall = ($validated['paint_score'] + $validated['engine_score'] + $validated['transmission_score'] + $validated['interior_score'] + $validated['tires_score']) / 5;

        $validated['expert_id'] = Auth::id();
        $validated['overall_score'] = round($overall);

        $report = InspectionReport::create($validated);

        // Update Car Status
        $car = Car::find($validated['car_id']);
        $car->update(['status' => 'inspected']);

        // Update Lead Status if applicable
        if ($request->filled('lead_id')) {
            \App\Models\Lead::where('id', $request->lead_id)->update(['status' => 'inspected']);
        }

        // AUTOMATED WORKFLOW: Create "Coming Soon" Auction
        // This ensures the car enters the marketplace queue but stays "Pending Approval" (Coming Soon)
        \App\Models\Auction::create([
            'car_id' => $car->id,
            'status' => 'coming_soon',
            'initial_price' => 1000, // Placeholder, admin will refine later
            'current_price' => 1000,
            'start_at' => now()->addDays(2), // Give admin time to approve
            'end_at' => now()->addDays(5),
            'duration_minutes' => 4320, // 3 days
            'deposit_type' => 'fixed',
            'deposit_amount' => 500,
        ]);

        return redirect()->route('admin.inspections.index')->with('success', 'Technical report published and vehicle queued for auction clearance.');
    }

    public function show(InspectionReport $report)
    {
        return view('admin.inspections.show', compact('report'));
    }

    public function calendar()
    {
        $scheduledLeads = \App\Models\Lead::where('status', 'inspection_scheduled')->get();
        
        $events = $scheduledLeads->map(function($lead) {
            $details = $lead->car_details ?? [];
            $date = $details['inspection_date'] ?? null;
            $time = $details['inspection_time'] ?? null;
            
            $start = null;
            if ($date && $time) {
                try {
                    // Normalize "02 Apr 2026 04:30 PM" to standard ISO
                    $start = \Carbon\Carbon::createFromFormat('d M Y h:i A', $date . ' ' . $time)->toIso8601String();
                } catch (\Exception $e) {}
            }

            $inspector = \App\Models\User::find($details['inspector_id'] ?? 0);

            // Palette: High-Voltage Solid Operational Colors
            $colors = [
                ['bg' => '#4f46e5', 'border' => '#3730a3', 'text' => '#ffffff'], // Indigo Solid
                ['bg' => '#10b981', 'border' => '#065f46', 'text' => '#ffffff'], // Emerald Solid
                ['bg' => '#f97316', 'border' => '#9a3412', 'text' => '#ffffff'], // Orange Solid
                ['bg' => '#3b82f6', 'border' => '#1e40af', 'text' => '#ffffff'], // Blue Solid
                ['bg' => '#d946ef', 'border' => '#86198f', 'text' => '#ffffff'], // Pink Solid
            ];
            $c = $colors[$lead->id % 5];

            return [
                'id' => $lead->id,
                'title' => ($details['make'] ?? '') . ' ' . ($details['model'] ?? ''),
                'start' => $start,
                'allDay' => false,
                'extendedProps' => [
                    'client' => $details['name'] ?? 'Guest',
                    'location' => $details['location'] ?? 'Not Specified',
                    'inspector' => $inspector ? $inspector->name : 'Unassigned',
                    'phone' => $details['phone'] ?? '',
                    'borderColor' => $c['border']
                ],
                'backgroundColor' => $c['bg'],
                'textColor' => $c['text'],
                'borderColor' => 'transparent'
            ];
        })->filter(fn($e) => $e['start'] !== null)->values();

        return view('admin.inspections.calendar', compact('events'));
    }

    public function tasks()
    {
        $userId = Auth::id();
        $leads = \App\Models\Lead::where('status', 'inspection_scheduled')->latest()->get();
        
        // Filter tasks where current user is the assigned inspector
        $tasks = $leads->filter(function($lead) use ($userId) {
            $details = $lead->car_details ?? [];
            return ($details['inspector_id'] ?? 0) == $userId;
        });

        return view('admin.inspections.tasks', compact('tasks'));
    }

    public function destroy(InspectionReport $report)
    {
        $report->delete();
        return redirect()->route('admin.inspections.index')->with('success', 'Report archived.');
    }

    private function resolveBrandAndModel(string $make, string $model): array
    {
        $cleanMake = trim($make);
        $cleanModel = trim($model);
        $brandSlug = Str::slug($cleanMake) ?: Str::of($cleanMake)->lower()->replaceMatches('/[^a-z0-9]+/i', '-')->trim('-')->toString();

        $brand = Brand::firstOrCreate(
            ['slug' => $brandSlug],
            ['name' => $cleanMake, 'logo_url' => null]
        );

        if ($brand->name !== $cleanMake) {
            $brand->update(['name' => $cleanMake]);
        }

        $modelSlug = Str::slug($cleanModel) ?: Str::of($cleanModel)->lower()->replaceMatches('/[^a-z0-9]+/i', '-')->trim('-')->toString();

        $carModel = CarModel::firstOrCreate(
            ['brand_id' => $brand->id, 'slug' => $modelSlug],
            ['name' => $cleanModel]
        );

        if ($carModel->name !== $cleanModel) {
            $carModel->update(['name' => $cleanModel]);
        }

        return [$brand->id, $carModel->id, $brand->name, $carModel->name];
    }
}
