<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure header menu exists
        Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Main Navigation']
        );

        // Ensure footer menu exists
        Menu::firstOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Navigation']
        );

        $this->command->info('Menus seeded: header + footer');
    }
}
