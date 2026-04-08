<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\CMS\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Popular Brands with their Slugs
        $popularBrands = [
            ['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz', 'models' => ['S-Class', 'C-Class', 'G-Wagon', 'E-Class']],
            ['name' => 'BMW', 'slug' => 'bmw', 'models' => ['X5', 'M4', 'M3', '7 Series']],
            ['name' => 'Audi', 'slug' => 'audi', 'models' => ['A8', 'Q7', 'RS6', 'R8']],
            ['name' => 'Porsche', 'slug' => 'porsche', 'models' => ['911', 'Cayenne', 'Taycan', 'Panamera']],
            ['name' => 'Toyota', 'slug' => 'toyota', 'models' => ['Land Cruiser', 'Camry', 'Supra', 'Corolla']],
            ['name' => 'Nissan', 'slug' => 'nissan', 'models' => ['Patrol', 'GT-R', 'Altima', 'Z']],
            ['name' => 'Ford', 'slug' => 'ford', 'models' => ['Mustang', 'F-150', 'Explorer', 'Ranger']],
            ['name' => 'Honda', 'slug' => 'honda', 'models' => ['Civic', 'Accord', 'CR-V', 'NSX']],
            ['name' => 'Land Rover', 'slug' => 'land-rover', 'models' => ['Defender', 'Range Rover', 'Discovery']],
            ['name' => 'Bentley', 'slug' => 'bentley', 'models' => ['Continental GT', 'Bentayga', 'Flying Spur']],
        ];

        foreach ($popularBrands as $bData) {
            $brand = Brand::updateOrCreate(
                ['slug' => $bData['slug']],
                ['name' => $bData['name']]
            );

            foreach ($bData['models'] as $mName) {
                CarModel::updateOrCreate(
                    ['brand_id' => $brand->id, 'name' => $mName],
                    ['slug' => Str::slug($mName)]
                );
            }
        }

        // 2. Discover ALL Brands from the Cars table (e.g. if the user has 70+ makes)
        Car::query()->select('make')->distinct()->get()->each(function (Car $car) {
             $make = trim($car->make);
             if (empty($make)) return;
             
             $slug = Str::slug($make);
             if (empty($slug)) return;

             Brand::updateOrCreate(
                ['slug' => $slug],
                ['name' => $make]
             );
        });

        // 3. Discover ALL Models from the Cars table
        Car::query()->select(['make', 'model'])->distinct()->get()->each(function (Car $car) {
            $make = trim($car->make);
            $model = trim($car->model);
            if (empty($make) || empty($model)) return;

            $bSlug = Str::slug($make);
            $brand = Brand::where('slug', $bSlug)->first();

            if ($brand && !empty($model)) {
                CarModel::updateOrCreate(
                    ['brand_id' => $brand->id, 'slug' => Str::slug($model)],
                    ['name' => $model]
                );
            }
        });

        // 4. Seed Home Page with lead_form_brands and content
        $homeContent = [
            'hero' => [
                'announcement' => 'Elite Marketplace Hub',
                'title' => 'The Future of Car Auctions',
                'subtitle' => 'Premium vehicles, absolute transparency, and zero-latency bidding.',
                'primary_cta_label' => 'Explore Inventory',
                'primary_cta_url' => '/auctions',
                'secondary_cta_label' => 'Sell Your Car',
                'secondary_cta_url' => '#',
                'car_scale' => 1.25,
                'background_mode' => 'image',
                'background_image' => '/images/hero-bg.png',
                'background_color' => '#0e1017',
                'background_overlay_enabled' => true,
                'background_overlay_opacity' => 0.72
            ],
            'lead_form_brands' => [
                ['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz'],
                ['name' => 'BMW', 'slug' => 'bmw'],
                ['name' => 'Audi', 'slug' => 'audi'],
                ['name' => 'Toyota', 'slug' => 'toyota'],
                ['name' => 'Nissan', 'slug' => 'nissan'],
                ['name' => 'Porsche', 'slug' => 'porsche'],
            ],
            'lead_form' => [
                'wizard_w1' => 'Select',
                'wizard_w2' => 'Customize',
                'wizard_w3' => 'Submit',
                'step1' => [
                    'title' => 'Choose brand, model, and year',
                    'subtitle' => 'Pick a brand first. The model list updates automatically.',
                    'brand_label' => 'Brand Selection',
                    'model_label' => 'Model Selection',
                    'year_label' => 'Production Year',
                    'button_label' => 'Get Free Valuation',
                ],
            ],
            'trust_badges' => [
                ['label'=>'Guaranteed Purchase','icon'=>'shield-check','color'=>'#ff4605','bg_color'=>'#fff7ed'],
                ['label'=>'No Costs. No Obligation','icon'=>'wallet','color'=>'#031629','bg_color'=>'#f1f5f9'],
                ['label'=>'Quick and Easy','icon'=>'zap','color'=>'#3b82f6','bg_color'=>'#eff6ff'],
            ],
            'brands' => [
                 ['name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz'],
                 ['name' => 'BMW', 'slug' => 'bmw'],
                 ['name' => 'Audi', 'slug' => 'audi'],
                 ['name' => 'Land Rover', 'slug' => 'land-rover'],
                 ['name' => 'Porsche', 'slug' => 'porsche'],
            ]
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Motor Bazar - Premium Car Marketplace',
                'content' => $homeContent,
                'is_published' => true,
                'seo_title' => 'Motor Bazar | Buying & Selling Cars',
                'seo_description' => 'The world\'s most trusted platform for premium car auctions.',
            ]
        );
    }
}
