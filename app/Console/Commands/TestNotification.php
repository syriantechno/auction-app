<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\NewLeadReceived;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestNotification extends Command
{
    protected $signature   = "notification:test";
    protected $description = "Test notification system";

    public function handle(): int
    {
        $admin = User::where("role", "admin")
            ->orWhereIn("email", ["admin@motorbazar.ae", "admin@automazad.com"])
            ->first();

        if (!$admin) { $this->error("No admin user found!"); return 1; }

        $this->info("Admin: [{$admin->id}] {$admin->email} role={$admin->role}");

        $lead = new Lead();
        $lead->id = 0;
        $lead->car_details = ["name"=>"Test","make"=>"Toyota","model"=>"Camry","year"=>"2024","inspection_date"=>date("Y-m-d"),"inspection_time"=>"10:00 AM"];

        $before = DB::table("notifications")->count();
        $admin->notify(new NewLeadReceived($lead));
        $after = DB::table("notifications")->count();

        $this->info("DB notifications: {$before}  {$after}");
        $this->info("Admin unread: " . $admin->fresh()->unreadNotifications()->count());
        $after > $before ? $this->info(" SUCCESS") : $this->error(" FAILED");

        return 0;
    }
}
