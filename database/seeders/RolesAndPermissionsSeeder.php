<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── مسح الـ cache ──────────────────────────────────────────
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ══════════════════════════════════════════════════════════
        // 1. تعريف كل الصلاحيات مجمّعة بالأقسام
        // ══════════════════════════════════════════════════════════
        $permissions = [

            // ─── Dashboard ───────────────────────────────────────
            'dashboard.view',

            // ─── Leads / CRM ─────────────────────────────────────
            'leads.view',
            'leads.create',
            'leads.edit',
            'leads.delete',
            'leads.confirm',        // جدولة المعاينة

            // ─── Inspections ─────────────────────────────────────
            'inspections.view',
            'inspections.create',
            'inspections.edit',
            'inspections.delete',
            'inspections.calendar', // عرض التقويم
            'inspections.tasks',    // المهام الميدانية

            // ─── Cars / Vehicles ─────────────────────────────────
            'cars.view',
            'cars.create',
            'cars.edit',
            'cars.delete',

            // ─── Auctions ─────────────────────────────────────────
            'auctions.view',
            'auctions.create',
            'auctions.edit',
            'auctions.delete',
            'auctions.approve',
            'auctions.settings',    // إعدادات المزادات العامة

            // ─── Stock ────────────────────────────────────────────
            'stock.view',
            'stock.manage',         // QC, complete deal

            // ─── Dealers ─────────────────────────────────────────
            'dealers.view',

            // ─── Finance / Accounting ────────────────────────────
            'finance.view',
            'finance.invoices',
            'finance.receipts',
            'finance.vouchers',
            'finance.accounts',

            // ─── Content / CMS ───────────────────────────────────
            'cms.view',
            'cms.edit',
            'posts.view',
            'posts.create',
            'posts.edit',
            'posts.delete',
            'pages.view',
            'pages.edit',
            'menus.view',
            'menus.edit',

            // ─── SEO ─────────────────────────────────────────────
            'seo.view',
            'seo.edit',

            // ─── Settings ────────────────────────────────────────
            'settings.view',
            'settings.edit',        // Profile, Maps, etc.
            'settings.communication', // Email + WhatsApp

            // ─── Notifications ───────────────────────────────────
            'notifications.view',

            // ─── Roles & Users Management ────────────────────────
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.assign',         // إسناد roles للمستخدمين
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ══════════════════════════════════════════════════════════
        // 2. تعريف الـ Roles وربطها بالصلاحيات
        // ══════════════════════════════════════════════════════════

        // ─── Super Admin ──────────────────────────────────────────
        // يحصل على كل الصلاحيات تلقائياً (Super override)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // ─── Admin ────────────────────────────────────────────────
        // كل شيء عدا إدارة الـ Roles والـ Users
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'dashboard.view',
            'leads.view', 'leads.create', 'leads.edit', 'leads.delete', 'leads.confirm',
            'inspections.view', 'inspections.create', 'inspections.edit', 'inspections.delete',
            'inspections.calendar', 'inspections.tasks',
            'cars.view', 'cars.create', 'cars.edit', 'cars.delete',
            'auctions.view', 'auctions.create', 'auctions.edit', 'auctions.delete', 'auctions.approve', 'auctions.settings',
            'stock.view', 'stock.manage',
            'dealers.view',
            'finance.view', 'finance.invoices', 'finance.receipts', 'finance.vouchers', 'finance.accounts',
            'cms.view', 'cms.edit',
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'pages.view', 'pages.edit',
            'menus.view', 'menus.edit',
            'seo.view', 'seo.edit',
            'settings.view', 'settings.edit', 'settings.communication',
            'notifications.view',
        ]);

        // ─── Inspector ────────────────────────────────────────────
        // يشتغل على المعاينات والطلبات فقط
        $inspector = Role::firstOrCreate(['name' => 'inspector', 'guard_name' => 'web']);
        $inspector->syncPermissions([
            'dashboard.view',
            'leads.view', 'leads.confirm',
            'inspections.view', 'inspections.create', 'inspections.edit',
            'inspections.calendar', 'inspections.tasks',
            'cars.view',
            'notifications.view',
        ]);

        // ─── Dealer ───────────────────────────────────────────────
        // يشوف المزادات والعروض فقط
        $dealer = Role::firstOrCreate(['name' => 'dealer', 'guard_name' => 'web']);
        $dealer->syncPermissions([
            'dashboard.view',
            'auctions.view',
            'cars.view',
            'stock.view',
            'notifications.view',
        ]);

        // ─── Finance Manager ──────────────────────────────────────
        // يتحكم بالنظام المالي بالكامل
        $financeManager = Role::firstOrCreate(['name' => 'finance-manager', 'guard_name' => 'web']);
        $financeManager->syncPermissions([
            'dashboard.view',
            'finance.view', 'finance.invoices', 'finance.receipts', 'finance.vouchers', 'finance.accounts',
            'auctions.view',
            'cars.view',
            'dealers.view',
            'notifications.view',
        ]);

        // ══════════════════════════════════════════════════════════
        // 3. إسناد Super Admin لأول مستخدم أدمن
        // ══════════════════════════════════════════════════════════
        $firstAdmin = User::where('role', 'admin')
            ->orWhereIn('email', ['admin@motorbazar.ae', 'admin@automazad.com'])
            ->first();

        if ($firstAdmin && !$firstAdmin->hasRole('super-admin')) {
            $firstAdmin->assignRole('super-admin');
            echo "  ✅ Super Admin assigned to: {$firstAdmin->email}\n";
        }

        echo "\n  ✅ Roles created: super-admin, admin, inspector, dealer, finance-manager\n";
        echo "  ✅ " . Permission::count() . " permissions seeded.\n\n";
    }
}
