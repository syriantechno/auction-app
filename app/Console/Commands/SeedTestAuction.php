<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SeedTestAuction extends Command
{
    protected $signature = 'auction:seed-test';
    protected $description = 'Seed a test auction for the modern bidding engine';

    public function handle()
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $car = Car::create([
            'make' => 'Mercedes-Benz',
            'model' => 'G63 AMG',
            'year' => 2024,
            'mileage' => 1200,
            'color' => 'Obsidian Black',
            'vin' => 'W1AG63' . rand(1000, 9999),
            'status' => 'approved',
        ]);

        $auction = Auction::create([
            'car_id' => $car->id,
            'initial_price' => 25000,
            'current_price' => 25000,
            'start_at' => Carbon::now()->subMinutes(5),
            'end_at' => Carbon::now()->addMinutes(20),
            'status' => 'active',
            'duration_minutes' => 20,
        ]);

        $this->info("Test Auction created! Viewing at: /auctions/{$auction->id}");
    }
}
