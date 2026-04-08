@extends('admin.layout')

@section('title', 'System Routes Inventory')

@section('content')
<x-admin-page-standard 
    icon="route" 
    title="Routes" 
    highlight="Inventory"
    subtitle="Complete system routes audit and consolidation plan"
    dot="orange">

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-admin-stat-card label="Total Routes" value="85+" icon="route" color="slate" />
        <x-admin-stat-card label="Unified (Elite)" value="52" icon="check-circle" color="emerald" />
        <x-admin-stat-card label="Core Legacy" value="18" icon="alert-circle" color="orange" />
        <x-admin-stat-card label="For Deletion" value="15" icon="trash-2" color="rose" />
    </div>

    <!-- Pages Processed Stats -->
    <div class="bg-gradient-to-r from-emerald-50 to-orange-50 rounded-xl border border-emerald-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-[#ff6900]"></i>
            Modernization Milestone: 22 Critical Pages Unified
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <x-admin-stat-card label="HR Dash" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Depts" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Pos" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Emp" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Shifts" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Attn" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Leaves" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Payroll" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Fin Dash" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Inv" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Receipts" value="✓" icon="check-circle" color="emerald" />
            <x-admin-stat-card label="Vouchers" value="✓" icon="check-circle" color="emerald" />
        </div>
        <div class="mt-4 p-3 bg-emerald-100 rounded-lg border border-emerald-200">
            <p class="text-sm font-medium text-emerald-800">
                <i data-lucide="sparkles" class="w-4 h-4 inline mr-1 text-emerald-600"></i>
                <strong>Surgical Modernization Complete:</strong> All high-traffic modules now utilize the <code>x-admin-page-standard</code> architecture.
            </p>
        </div>
    </div>

    <!-- Consolidation Plan -->
    <div class="bg-white rounded-xl border border-slate-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
            <i data-lucide="clipboard-list" class="w-5 h-5 text-[#ff6900]"></i>
            Consolidation Execution State
        </h2>
        <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                <div>
                    <p class="font-medium text-slate-900">HR Module Ecosystem</p>
                    <p class="text-sm text-slate-500">Dashboard, Stats, Employees, Payroll and Leaves unified</p>
                </div>
                <span class="ml-auto px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">DONE</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                <div>
                    <p class="font-medium text-slate-900">Finance Module Hub</p>
                    <p class="text-sm text-slate-500">Invoices, Receipts, Vouchers and Cashflow dashboards unified</p>
                </div>
                <span class="ml-auto px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">DONE</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">3</div>
                <div>
                    <p class="font-medium text-slate-900">CRM and Sales Protocol</p>
                    <p class="text-sm text-slate-500">Leads Management, Audit Hub and Inspection Reporting unified</p>
                </div>
                <span class="ml-auto px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">DONE</span>
            </div>
            <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">4</div>
                <div>
                    <p class="font-medium text-slate-900">CMS and Engine Overhaul</p>
                    <p class="text-sm text-slate-500">Page Builder and Editorial Hub successfully synchronized</p>
                </div>
                <span class="ml-auto px-2 py-1 bg-emerald-500 text-white text-xs font-bold rounded">DONE</span>
            </div>
        </div>
    </div>

    <!-- Routes Inventory -->
    <div class="bg-white rounded-xl border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                <i data-lucide="list" class="w-5 h-5 text-[#ff6900]"></i>
                Detailed Modernization Ledger
            </h2>
            <div class="flex gap-2">
                <button onclick="filterRoutes('all')" class="px-3 py-1 text-xs font-bold uppercase bg-slate-800 text-white rounded transition-colors">All</button>
                <button onclick="filterRoutes('done')" class="px-3 py-1 text-xs font-bold uppercase bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded transition-colors">Done</button>
                <button onclick="filterRoutes('pending')" class="px-3 py-1 text-xs font-bold uppercase bg-orange-100 text-orange-700 hover:bg-orange-200 rounded transition-colors">Pending</button>
                <button onclick="filterRoutes('delete')" class="px-3 py-1 text-xs font-bold uppercase bg-rose-100 text-rose-700 hover:bg-rose-200 rounded transition-colors">Delete</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase text-xs tracking-wider">Module</th>
                        <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase text-xs tracking-wider">Route</th>
                        <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase text-xs tracking-wider">Name</th>
                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase text-xs tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center font-bold text-slate-500 uppercase text-xs tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="routesTable">
                    
                    @foreach([
                        ['HR', '/admin/hr', 'Dashboard', 'done', 'admin.hr.dashboard'],
                        ['HR', '/admin/hr/departments', 'Departments', 'done', 'admin.hr.departments.index'],
                        ['HR', '/admin/hr/positions', 'Positions', 'done', 'admin.hr.positions.index'],
                        ['HR', '/admin/hr/employees', 'Employees', 'done', 'admin.hr.employees.index'],
                        ['HR', '/admin/hr/shifts', 'Shifts', 'done', 'admin.hr.shifts.index'],
                        ['HR', '/admin/hr/attendance', 'Attendance', 'done', 'admin.hr.attendance.index'],
                        ['HR', '/admin/hr/leaves', 'Leaves', 'done', 'admin.hr.leaves.index'],
                        ['HR', '/admin/hr/payrolls', 'Payrolls', 'done', 'admin.hr.payrolls.index'],
                        ['HR', '/admin/hr/calendar', 'Calendar', 'done', 'admin.hr.calendar'],
                        ['Finance', '/admin/finance', 'Dashboard', 'done', 'admin.finance.dashboard'],
                        ['Finance', '/admin/finance/invoices', 'Invoices', 'done', 'admin.finance.invoices'],
                        ['Finance', '/admin/finance/receipts', 'Receipts', 'done', 'admin.finance.receipts'],
                        ['Finance', '/admin/finance/vouchers', 'Vouchers', 'done', 'admin.finance.vouchers'],
                        ['Finance', '/admin/finance/accounts', 'Bank Accounts', 'done', 'admin.finance.accounts'],
                        ['CRM', '/admin/leads', 'Leads Management', 'done', 'admin.leads.index'],
                        ['Appraisal', '/admin/inspections', 'Valuation Reports', 'done', 'admin.inspections.index'],
                        ['CMS', '/admin/pages', 'Page Builder', 'done', 'admin.pages.index'],
                        ['CMS', '/admin/posts', 'Editorial Hub', 'done', 'admin.posts.index'],
                        ['CMS', '/admin/menus', 'Navigation Menus', 'done', 'admin.menus.index'],
                        ['CMS', '/admin/cms/home', 'Home Control', 'pending', 'admin.cms.home'],
                        ['CMS', '/admin/cms/test', 'CMS Test Page', 'delete', null],
                        ['SEO', '/admin/seo/test', 'SEO Test Page', 'delete', null],
                        ['Settings', '/admin/settings/logo', 'Old Logo Management', 'delete', null],
                    ] as [$mod, $route, $name, $status, $routeName])
                    <tr class="route-row" data-status="{{ $status }}">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $mod }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $route }}</td>
                        <td class="px-4 py-3">{{ $name }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase 
                                {{ $status === 'done' ? 'bg-emerald-100 text-emerald-700' : ($status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($routeName && Route::has($routeName))
                            <a href="{{ route($routeName) }}" class="text-[#ff6900] hover:underline text-xs font-bold">Launch</a>
                            @else
                            <span class="text-xs text-slate-300">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-page-standard>

<script>
function filterRoutes(status) {
    const rows = document.querySelectorAll('.route-row');
    rows.forEach(row => {
        if (status === 'all' || row.dataset.status === status) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
});
</script>
@endsection
