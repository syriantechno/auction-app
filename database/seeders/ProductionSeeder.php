<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lead;
use App\Models\Car;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\CMS\Category;
use App\Models\CMS\Post;
use App\Models\CMS\Page;
use App\Models\CMS\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create Users
        $users = collect([
            [
                'name' => 'Admin User',
                'email' => 'admin@motorbazar.ae',
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Expert User',
                'email' => 'expert@motorbazar.ae', 
                'password' => bcrypt('12345678'),
            ],
            [
                'name' => 'Bidder User',
                'email' => 'bidder@motorbazar.ae',
                'password' => bcrypt('12345678'),
            ],
        ])->map(fn ($user) => User::create($user));

        // Create CMS Categories
        $categories = collect([
            ['name' => 'Luxury Cars', 'slug' => 'luxury-cars'],
            ['name' => 'Sports Cars', 'slug' => 'sports-cars'],
            ['name' => 'Classic Cars', 'slug' => 'classic-cars'],
            ['name' => 'SUVs', 'slug' => 'suvs'],
        ])->map(fn ($cat) => Category::create($cat));

        // Create CMS Posts
        collect([
            [
                'title' => 'مرحباً بك في عالم السيارات الفاخرة',
                'slug' => 'welcome-to-luxury-cars',
                'content' => json_encode(['text' => 'نقدم لكم أفضل مجموعة من السيارات الفاخرة بعناية فائقة...']),
                'category_id' => $categories[0]->id,
                'published_at' => now(),
            ],
            [
                'title' => 'دليل شراء السيارة المستعملة',
                'slug' => 'used-car-buying-guide',
                'content' => json_encode(['text' => 'نصائح هامة عند شراء سيارة مستعملة...']),
                'category_id' => $categories[1]->id,
                'published_at' => now(),
            ],
        ])->each(fn ($post) => Post::create($post));

        // Create CMS Pages
        collect([
            [
                'title' => 'من نحن',
                'slug' => 'about-us',
                'content' => json_encode(['text' => 'Automazad هو منصة رائدة لمزادات السيارات الفاخرة...']),
                'is_published' => true,
            ],
            [
                'title' => 'شروط الاستخدام',
                'slug' => 'terms-of-service',
                'content' => json_encode(['text' => 'الشروط والأحكام المنظمة لاستخدام المنصة...']),
                'is_published' => true,
            ],
        ])->each(fn ($page) => Page::create($page));

        // Create CMS Menu
        $mainMenu = Menu::create([
            'name' => 'القائمة الرئيسية',
            'location' => 'header',
        ]);

        // Create Cars
        $cars = collect([
            [
                'make' => 'Mercedes-Benz',
                'model' => 'S-Class',
                'year' => 2023,
                'vin' => 'WDD222811A123456',
                'ownership_type' => 'brokerage',
                'base_price' => 85000.00,
                'status' => 'approved',
                'inspection_data' => [
                    'engine_condition' => 'excellent',
                    'transmission_condition' => 'excellent',
                    'mechanical_notes' => 'المحرك في حالة ممتازة، جميع الأنظمة تعمل بشكل صحيح'
                ],
            ],
            [
                'make' => 'BMW',
                'model' => 'X5',
                'year' => 2022,
                'vin' => '5UXCR6C03N123456',
                'ownership_type' => 'owned',
                'base_price' => 65000.00,
                'status' => 'available',
                'inspection_data' => [
                    'engine_condition' => 'good',
                    'transmission_condition' => 'good',
                    'mechanical_notes' => 'سيارة جيدة، تحتاج بعض الصيانة الدورية'
                ],
            ],
            [
                'make' => 'Audi',
                'model' => 'A8',
                'year' => 2024,
                'vin' => 'WAUZZZF74N012345',
                'ownership_type' => 'brokerage',
                'base_price' => 92000.00,
                'status' => 'inspection',
                'inspection_data' => [
                    'engine_condition' => 'fair',
                    'transmission_condition' => 'good',
                    'mechanical_notes' => 'تحتاج فحص شامل قبل البيع'
                ],
            ],
        ])->map(fn ($car) => Car::create($car));

        // Create Leads
        collect([
            [
                'user_id' => $users[1]->id, // Expert User
                'car_details' => [
                    'make' => 'Toyota',
                    'model' => 'Camry',
                    'year' => 2021,
                    'budget' => 25000,
                    'message' => 'أرغب في بيع سيارتي'
                ],
                'status' => 'new',
                'notes' => 'عميل مهتم في بيع سيارته',
            ],
            [
                'user_id' => $users[1]->id, // Expert User
                'car_details' => [
                    'make' => 'Honda',
                    'model' => 'Civic',
                    'year' => 2020,
                    'budget' => 15000,
                    'message' => 'سيارة جيدة للعائلات'
                ],
                'status' => 'contacted',
                'notes' => 'تم التواصل مع العميل',
            ],
        ])->each(fn ($lead) => Lead::create($lead));

        // Create Auctions
        $auctions = collect([
            [
                'car_id' => $cars[0]->id, // Mercedes S-Class
                'initial_price' => 85000.00,
                'current_price' => 85000.00,
                'start_at' => now()->addHours(2),
                'end_at' => now()->addHours(2)->addMinutes(20),
                'status' => 'coming_soon',
                'duration_minutes' => 20,
                'deposit_type' => 'fixed',
                'deposit_amount' => 1000.00,
            ],
            [
                'car_id' => $cars[1]->id, // BMW X5
                'initial_price' => 65000.00,
                'current_price' => 65000.00,
                'start_at' => now()->addHours(4),
                'end_at' => now()->addHours(4)->addMinutes(20),
                'status' => 'active',
                'duration_minutes' => 20,
                'deposit_type' => 'percentage',
                'deposit_amount' => 5.0,
            ],
        ])->map(fn ($auction) => Auction::create($auction));

        // Create Bids
        collect([
            [
                'auction_id' => $auctions[1]->id, // BMW X5 auction
                'user_id' => $users[2]->id, // Bidder User
                'amount' => 68000.00,
            ],
            [
                'auction_id' => $auctions[1]->id, // BMW X5 auction
                'user_id' => $users[0]->id, // Admin User
                'amount' => 72000.00,
            ],
        ])->each(fn ($bid) => Bid::create($bid));

    }
}
