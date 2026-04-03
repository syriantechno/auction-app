<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.dashboard', $this->buildDashboardData($request));
    }

    public function catalog(Request $request)
    {
        return view('admin.cars.catalog', [
            'catalog' => $this->readCatalogOverview($request),
            'makeOptions' => $this->makeOptions(),
        ]);
    }

    public function catalogApi(Request $request)
    {
        $size = (int) $request->input('size', 50);
        if ($size < 1)
            $size = 50;

        $normalizedCatalog = $this->usesNormalizedCatalog();
        $paginator = $normalizedCatalog
            ? Car::with(['brand', 'carModel'])->orderBy('make')->orderBy('model')->paginate($size)
            : Car::orderBy('make')->orderBy('model')->paginate($size);

        $data = collect($paginator->items())->map(function ($car) {
            return [
                'id' => $car->id,
                'make' => $car->brand?->name ?? $car->make,
                'model' => $car->carModel?->name ?? $car->model,
                'year' => $car->year,
                'status' => 'Active Seed',
            ];
        });

        return response()->json([
            'last_page' => $paginator->lastPage(),
            'data' => $data
        ]);
    }

    public function storeCatalogEntry(Request $request)
    {
        $validated = $request->validate([
            'make' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
        ]);

        [$brandId, $modelId, $canonicalMake, $canonicalModel] = $this->resolveBrandAndModel(
            $validated['make'],
            $validated['model']
        );

        $car = Car::firstOrCreate([
            'brand_id' => $brandId,
            'car_model_id' => $modelId,
            'make' => $canonicalMake,
            'model' => $canonicalModel,
            'year' => (int) $validated['year'],
        ], [
            'brand_id' => $brandId,
            'car_model_id' => $modelId,
            'make' => $canonicalMake,
            'model' => $canonicalModel,
            'year' => (int) $validated['year'],
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Car successfully committed to master catalog.',
                'car' => $car
            ]);
        }

        return redirect()
            ->route('admin.cars.catalog')
            ->with('catalog_success', 'Car catalog row added successfully.');
    }

    public function updateCatalogEntry(Request $request, Car $car)
    {
        $validated = $request->validate([
            'make' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['required', 'integer', 'min:1950', 'max:' . ((int) date('Y') + 1)],
        ]);

        [$brandId, $modelId, $canonicalMake, $canonicalModel] = $this->resolveBrandAndModel(
            $validated['make'],
            $validated['model']
        );

        $car->update([
            'brand_id' => $brandId,
            'car_model_id' => $modelId,
            'make' => $canonicalMake,
            'model' => $canonicalModel,
            'year' => $validated['year'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Car record updated successfully.',
            'car' => $car
        ]);
    }

    public function destroyCatalogEntry(Car $car)
    {
        $car->delete();
        return response()->json([
            'success' => true,
            'message' => 'Car record removed from master catalog.'
        ]);
    }

    private function buildDashboardData(Request $request): array
    {
        $stats = [
            'active_auctions' => \App\Models\Auction::where('status', 'active')->count(),
            'available_cars' => \App\Models\Car::count(),
            'total_bids' => \App\Models\Bid::count(),
            'pending_negotiations' => \App\Models\Bid::latest()->take(5)->count(),
        ];

        $recent_bids = \App\Models\Bid::with(['user', 'auction.car'])
            ->latest()
            ->take(6)
            ->get();

        return [
            'stats' => $stats,
            'recent_bids' => $recent_bids,
        ];
    }

    private function readCatalogOverview(Request $request): array
    {
        $filters = [
            'make' => trim((string) $request->input('make', '')),
            'model' => trim((string) $request->input('model', '')),
            'year' => trim((string) $request->input('year', '')),
        ];

        $query = Car::query();
        $normalizedCatalog = $this->usesNormalizedCatalog();

        if ($filters['make'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('make', 'like', '%' . $filters['make'] . '%')
                    ;

                if ($this->usesNormalizedCatalog()) {
                    $q->orWhereHas('brand', fn ($brandQuery) => $brandQuery->where('name', 'like', '%' . $filters['make'] . '%'));
                }
            });
        }

        if ($filters['model'] !== '') {
            $query->where(function ($q) use ($filters) {
                $q->where('model', 'like', '%' . $filters['model'] . '%')
                    ;

                if ($this->usesNormalizedCatalog()) {
                    $q->orWhereHas('carModel', fn ($modelQuery) => $modelQuery->where('name', 'like', '%' . $filters['model'] . '%'));
                }
            });
        }

        if ($filters['year'] !== '') {
            $query->where('year', 'like', '%' . $filters['year'] . '%');
        }

        $filteredCars = $normalizedCatalog
            ? $query->with(['brand', 'carModel'])->orderBy('make')->orderBy('model')->orderBy('year')->get()
            : $query->orderBy('make')->orderBy('model')->orderBy('year')->get();

        $limit = $request->routeIs('admin.cars.catalog') ? 50 : 12;
        $preview = $filteredCars->take($limit)->map(fn(Car $car) => [
            'make' => $car->brand?->name ?? $car->make,
            'model' => $car->carModel?->name ?? $car->model,
            'year' => $car->year,
        ])->values()->all();

        $topMakes = $this->usesNormalizedCatalog()
            ? Brand::query()
                ->withCount('cars')
                ->orderByDesc('cars_count')
                ->orderBy('name')
                ->take(8)
                ->get()
                ->mapWithKeys(fn (Brand $brand) => [$brand->name => $brand->cars_count])
                ->toArray()
            : Car::query()
                ->selectRaw('make, COUNT(*) as total')
                ->groupBy('make')
                ->orderByDesc('total')
                ->orderBy('make')
                ->take(8)
                ->pluck('total', 'make')
                ->toArray();

        $minYear = Car::min('year');
        $maxYear = Car::max('year');
        $uniqueMakes = $this->usesNormalizedCatalog() ? Brand::count() : Car::distinct('make')->count('make');
        $uniqueModels = $this->usesNormalizedCatalog() ? CarModel::count() : Car::distinct('model')->count('model');

        return [
            'total_rows' => Car::count(),
            'unique_makes' => $uniqueMakes,
            'unique_models' => $uniqueModels,
            'min_year' => $minYear,
            'max_year' => $maxYear,
            'filters' => $filters,
            'preview' => $preview,
            'top_makes' => $topMakes,
        ];
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

    private function makeOptions(): array
    {
        if ($this->usesNormalizedCatalog()) {
            return Brand::query()->orderBy('name')->pluck('name')->all();
        }

        return Car::distinct()->orderBy('make')->pluck('make')->all();
    }
}
