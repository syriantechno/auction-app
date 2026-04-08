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
            'leads.assign',         // إسناد العميل لموظف

            // ─── Inspections ─────────────────────────────────────
            'inspections.view',
            'inspections.create',
            'inspections.edit',
            'inspections.delete',
            'inspections.approve',
            'inspections.reject',
            'inspections.calendar', // عرض التقويم
            'inspections.tasks',    // المهام الميدانية

            // ─── Cars / Vehicles ─────────────────────────────────
            'cars.view',
            'cars.create',
            'cars.edit',
            'cars.delete',
            'cars.import',
            'cars.export',

            // ─── Auctions ─────────────────────────────────────────
            'auctions.view',
            'auctions.create',
            'auctions.edit',
            'auctions.delete',
            'auctions.approve',
            'auctions.close',
            'auctions.settings',    // إعدادات المزادات العامة
            'auctions.manage-bids', // إدارة المزايدات

            // ─── Bids ────────────────────────────────────────────
            'bids.place',
            'bids.view-all',
            'bids.manage',
            'bids.cancel',

            // ─── Stock ────────────────────────────────────────────
            'stock.view',
            'stock.manage',         // QC, complete deal
            'stock.entry',
            'stock.approve',

            // ─── Dealers ─────────────────────────────────────────
            'dealers.view',
            'dealers.create',
            'dealers.edit',
            'dealers.manage',

            // ─── Customers ───────────────────────────────────────
            'customers.view',
            'customers.create',
            'customers.edit',
            'customers.delete',

            // ─── Negotiations ────────────────────────────────────
            'negotiations.view',
            'negotiations.create',
            'negotiations.edit',
            'negotiations.approve',
            'negotiations.reject',

            // ─── HR / Employees ──────────────────────────────────
            'hr.view',
            'hr.employees.view',
            'hr.employees.create',
            'hr.employees.edit',
            'hr.employees.delete',
            'hr.attendance.view',
            'hr.attendance.manage',
            'hr.attendance.approve',
            'hr.leaves.view',
            'hr.leaves.manage',
            'hr.leaves.approve',
            'hr.payroll.view',
            'hr.payroll.manage',
            'hr.payroll.process',
            'hr.reports',

            // ─── Finance / Accounting ────────────────────────────
            'finance.view',
            'finance.invoices.view',
            'finance.invoices.create',
            'finance.invoices.edit',
            'finance.invoices.delete',
            'finance.invoices.approve',
            'finance.receipts.view',
            'finance.receipts.create',
            'finance.receipts.edit',
            'finance.receipts.delete',
            'finance.receipts.approve',
            'finance.vouchers.view',
            'finance.vouchers.create',
            'finance.vouchers.edit',
            'finance.vouchers.delete',
            'finance.vouchers.approve',
            'finance.accounts.view',
            'finance.accounts.create',
            'finance.accounts.edit',
            'finance.transactions.view',
            'finance.transactions.create',
            'finance.journal.view',
            'finance.journal.create',
            'finance.reports',

            // ─── Appointments ────────────────────────────────────
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',
            'appointments.approve',
            'appointments.calendar',

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
            'settings.system',

            // ─── Notifications ───────────────────────────────────
            'notifications.view',
            'notifications.manage',

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
            'users.assign-role',

            // ─── Warehouse ───────────────────────────────────────
            'warehouse.view',
            'warehouse.manage',
            'warehouse.transfer',
            'warehouse.adjust',

            // ─── Reports ─────────────────────────────────────────
            'reports.view',
            'reports.sales',
            'reports.auctions',
            'reports.finance',
            'reports.hr',
            'reports.export',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ══════════════════════════════════════════════════════════
        // 2. تعريف الـ Roles وربطها بالصلاحيات
        // ══════════════════════════════════════════════════════════

        // ─── 1. Super Admin ─────────────────────────────────────
        // يحصل على كل الصلاحيات تلقائياً
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // ─── 2. Admin (Regular) ─────────────────────────────────
        // كل شيء عدا إدارة الـ Roles والـ Users
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'dashboard.view',
            'leads.view', 'leads.create', 'leads.edit', 'leads.delete', 'leads.confirm', 'leads.assign',
            'inspections.view', 'inspections.create', 'inspections.edit', 'inspections.delete',
            'inspections.calendar', 'inspections.tasks', 'inspections.approve', 'inspections.reject',
            'cars.view', 'cars.create', 'cars.edit', 'cars.delete', 'cars.import', 'cars.export',
            'auctions.view', 'auctions.create', 'auctions.edit', 'auctions.delete', 'auctions.approve', 'auctions.close', 'auctions.settings', 'auctions.manage-bids',
            'bids.view-all', 'bids.manage', 'bids.cancel',
            'stock.view', 'stock.manage', 'stock.entry', 'stock.approve',
            'dealers.view', 'dealers.create', 'dealers.edit', 'dealers.manage',
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
            'negotiations.view', 'negotiations.create', 'negotiations.edit', 'negotiations.approve', 'negotiations.reject',
            'finance.view', 'finance.invoices.view', 'finance.receipts.view', 'finance.vouchers.view', 'finance.accounts.view',
            'finance.transactions.view', 'finance.journal.view', 'finance.reports',
            'appointments.view', 'appointments.create', 'appointments.edit', 'appointments.delete', 'appointments.approve', 'appointments.calendar',
            'cms.view', 'cms.edit',
            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'pages.view', 'pages.edit',
            'menus.view', 'menus.edit',
            'seo.view', 'seo.edit',
            'settings.view', 'settings.edit', 'settings.communication',
            'notifications.view', 'notifications.manage',
            'warehouse.view', 'warehouse.manage', 'warehouse.transfer',
            'reports.view', 'reports.sales', 'reports.auctions', 'reports.finance', 'reports.export',
        ]);

        // ─── 3. HR Manager ─────────────────────────────────────
        // إدارة الموارد البشرية والموظفين
        $hrManager = Role::firstOrCreate(['name' => 'hr-manager', 'guard_name' => 'web']);
        $hrManager->syncPermissions([
            'dashboard.view',
            'hr.view',
            'hr.employees.view', 'hr.employees.create', 'hr.employees.edit', 'hr.employees.delete',
            'hr.attendance.view', 'hr.attendance.manage', 'hr.attendance.approve',
            'hr.leaves.view', 'hr.leaves.manage', 'hr.leaves.approve',
            'hr.payroll.view', 'hr.payroll.manage', 'hr.payroll.process',
            'hr.reports',
            'users.view',
            'appointments.view', 'appointments.calendar',
            'reports.view', 'reports.hr',
            'notifications.view',
        ]);

        // ─── 4. Accounts Manager ───────────────────────────────
        // مدير الحسابات - النظام المالي بالكامل
        $accountsManager = Role::firstOrCreate(['name' => 'accounts-manager', 'guard_name' => 'web']);
        $accountsManager->syncPermissions([
            'dashboard.view',
            'finance.view',
            'finance.invoices.view', 'finance.invoices.create', 'finance.invoices.edit', 'finance.invoices.delete', 'finance.invoices.approve',
            'finance.receipts.view', 'finance.receipts.create', 'finance.receipts.edit', 'finance.receipts.delete', 'finance.receipts.approve',
            'finance.vouchers.view', 'finance.vouchers.create', 'finance.vouchers.edit', 'finance.vouchers.delete', 'finance.vouchers.approve',
            'finance.accounts.view', 'finance.accounts.create', 'finance.accounts.edit',
            'finance.transactions.view', 'finance.transactions.create',
            'finance.journal.view', 'finance.journal.create',
            'finance.reports',
            'customers.view', 'customers.create', 'customers.edit',
            'auctions.view',
            'cars.view',
            'dealers.view',
            'reports.view', 'reports.finance',
            'notifications.view',
        ]);

        // ─── 5. Dealer ─────────────────────────────────────────
        // ديلر - إدارة السيارات والمزادات والمبيعات
        $dealer = Role::firstOrCreate(['name' => 'dealer', 'guard_name' => 'web']);
        $dealer->syncPermissions([
            'dashboard.view',
            'cars.view', 'cars.create', 'cars.edit',
            'stock.view', 'stock.manage',
            'auctions.view', 'auctions.create', 'auctions.edit', 'auctions.approve', 'auctions.close',
            'bids.place', 'bids.view-all',
            'leads.view', 'leads.create', 'leads.edit', 'leads.assign',
            'customers.view', 'customers.create', 'customers.edit',
            'negotiations.view', 'negotiations.create', 'negotiations.edit',
            'inspections.view',
            'appointments.view', 'appointments.create', 'appointments.edit',
            'finance.invoices.view', 'finance.receipts.view',
            'reports.view', 'reports.sales', 'reports.auctions',
            'notifications.view',
        ]);

        // ─── 6. Inspector ─────────────────────────────────────
        // مفتش فني - فحص السيارات والمعاينات
        $inspector = Role::firstOrCreate(['name' => 'inspector', 'guard_name' => 'web']);
        $inspector->syncPermissions([
            'dashboard.view',
            'inspections.view', 'inspections.create', 'inspections.edit',
            'inspections.calendar', 'inspections.tasks', 'inspections.approve', 'inspections.reject',
            'cars.view',
            'stock.view',
            'auctions.view',
            'leads.view', 'leads.confirm',
            'appointments.view', 'appointments.edit', 'appointments.calendar',
            'notifications.view',
        ]);

        // ─── 7. Secretary ──────────────────────────────────────
        // سكرتيرة / منظم مواعيد
        $secretary = Role::firstOrCreate(['name' => 'secretary', 'guard_name' => 'web']);
        $secretary->syncPermissions([
            'dashboard.view',
            'appointments.view', 'appointments.create', 'appointments.edit', 'appointments.delete', 'appointments.approve', 'appointments.calendar',
            'leads.view', 'leads.create', 'leads.edit', 'leads.assign',
            'customers.view', 'customers.create', 'customers.edit',
            'inspections.view', 'inspections.calendar',
            'auctions.view',
            'bids.view-all',
            'negotiations.view',
            'cars.view',
            'notifications.view',
        ]);

        // ─── 8. Regular User ─────────────────────────────────
        // مستخدم عادي - مزايدة ومشاهدة
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $user->syncPermissions([
            'dashboard.view',
            'bids.place',
            'auctions.view',
            'cars.view',
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

        echo "\n  ✅ الأدوار التي تم إنشاؤها:\n";
        echo "     1. Super Admin (سوبر أدمن) - تحكم كامل\n";
        echo "     2. Admin (أدمن عادي) - إدارة الموقع\n";
        echo "     3. HR Manager (مدير موارد بشرية) - إدارة الموظفين\n";
        echo "     4. Accounts Manager (مدير حسابات) - المالية والمحاسبة\n";
        echo "     5. Dealer (ديلر) - السيارات والمزادات\n";
        echo "     6. Inspector (مفتش فني) - فحص السيارات\n";
        echo "     7. Secretary (سكرتيرة) - المواعيد والاستقبال\n";
        echo "     8. User (مستخدم) - المزايدة والمشاهدة\n";
        echo "  ✅ " . Permission::count() . " صلاحية تم إنشاؤها\n\n";
    }
}
