<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalogPath = base_path('car_catalog.csv');

        if (!file_exists($catalogPath)) {
            return;
        }

        $existingKeys = Car::query()
            ->get(['make', 'model', 'year'])
            ->map(fn (Car $car) => mb_strtolower(trim($car->make) . '|' . trim($car->model) . '|' . (string) $car->year))
            ->flip();

        $insertRows = [];
        $handle = fopen($catalogPath, 'r');

        if ($handle === false) {
            return;
        }

        fgetcsv($handle); // skip header

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            [$make, $model, $year] = array_map('trim', $row);

            if ($make === '' || $model === '' || $year === '') {
                continue;
            }

            $key = mb_strtolower($make . '|' . $model . '|' . $year);

            if ($existingKeys->has($key)) {
                continue;
            }

            $insertRows[] = [
                'make' => $make,
                'model' => $model,
                'year' => (int) $year,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($insertRows) >= 1000) {
                Car::insert($insertRows);
                $insertRows = [];
            }
        }

        fclose($handle);

        if (!empty($insertRows)) {
            Car::insert($insertRows);
        }
    }
}
