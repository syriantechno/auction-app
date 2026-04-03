<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $normalizedCatalog = $this->usesNormalizedCatalog();
        $query = $normalizedCatalog ? Car::query()->with(['brand', 'carModel']) : Car::query();

        // High-Performance Filtering Engine
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($wr) use ($q, $normalizedCatalog) {
                $wr->where('make', 'like', "%{$q}%")
                   ->orWhere('model', 'like', "%{$q}%")
                   ->orWhere('id', 'like', "%{$q}%");

                if ($normalizedCatalog) {
                    $wr->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', "%{$q}%"))
                       ->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->where('name', 'like', "%{$q}%"));
                }
            });
        }

        if ($request->filled('make')) {
            if ($normalizedCatalog) {
                $query->whereHas('brand', function ($brandQuery) use ($request) {
                    $brandQuery->where('name', $request->make);
                });
            } else {
                $query->where('make', $request->make);
            }
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $cars = $query->orderBy('make')->orderBy('model')->orderBy('year', 'desc')->paginate(50);
        
        // Fetch all available manufacturers for the filter
        $makeOptions = Brand::query()
            ->orderBy('name')
            ->pluck('name');

        if ($makeOptions->isEmpty()) {
            $makeOptions = Car::query()
                ->distinct()
                ->orderBy('make')
                ->pluck('make');
        }
        
        return view('admin.cars.index', compact('cars', 'makeOptions'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'vin' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'exterior_color' => 'nullable|string|max:255',
            'interior_color' => 'nullable|string|max:255',
            'engine' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'image_url' => 'nullable|url',
        ]);

        if ($this->usesNormalizedCatalog()) {
            [$brandId, $modelId, $canonicalMake, $canonicalModel] = $this->resolveBrandAndModel(
                $validated['make'],
                $validated['model']
            );

            Car::create(array_merge($validated, [
                'brand_id' => $brandId,
                'car_model_id' => $modelId,
                'make' => $canonicalMake,
                'model' => $canonicalModel,
            ]));
        } else {
            Car::create($validated);
        }

        return redirect()->route('admin.cars.index')->with('success', 'Car added to inventory successfully.');
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'vin' => 'nullable|string|max:255',
            'mileage' => 'nullable|integer|min:0',
            'image_url' => 'nullable|url',
        ]);

        if ($this->usesNormalizedCatalog()) {
            [$brandId, $modelId, $canonicalMake, $canonicalModel] = $this->resolveBrandAndModel(
                $validated['make'],
                $validated['model']
            );

            $car->update(array_merge($validated, [
                'brand_id' => $brandId,
                'car_model_id' => $modelId,
                'make' => $canonicalMake,
                'model' => $canonicalModel,
            ]));
        } else {
            $car->update($validated);
        }

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('admin.cars.index')->with('success', 'Car removed from inventory.');
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

    private function usesNormalizedCatalog(): bool
    {
        return Schema::hasTable('brands')
            && Schema::hasTable('car_models')
            && Schema::hasColumn('cars', 'brand_id')
            && Schema::hasColumn('cars', 'car_model_id');
    }
}
