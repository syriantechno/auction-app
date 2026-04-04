<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /** قائمة الـ Roles */
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /** إنشاء role جديد */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0]; // group by prefix (leads, cars, etc.)
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:60|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', "Role \"{$role->name}\" created successfully.");
    }

    /** عرض وتعديل role — يدعم AJAX */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return explode('.', $p->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        if (request()->wantsJson()) {
            return response()->json([
                'role'            => ['id' => $role->id, 'name' => $role->name],
                'permissions'     => $permissions->map(fn($g) => $g->map(fn($p) => $p->name)->values())->toArray(),
                'rolePermissions' => $rolePermissions,
            ]);
        }

        // fallback للمتصفح المباشر — redirect للـ Hub
        return redirect()->route('admin.settings.hub', ['tab' => 'tab2']);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'          => 'required|string|max:60|unique:roles,name,' . $role->id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($role->name === 'super-admin') {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($request->permissions ?? []);
        }

        if (!in_array($role->name, ['super-admin', 'admin'])) {
            $role->update(['name' => $request->name]);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->wantsJson()) {
            $role->loadCount('permissions');
            return response()->json([
                'message'          => "Role \"{$role->name}\" updated successfully ✓",
                'permissionsCount' => $role->permissions_count,
            ]);
        }

        return redirect()->route('admin.settings.hub', ['tab' => 'tab2'])->with('success', "Role \"{$role->name}\" updated.");
    }


    public function destroy(Role $role)
    {
        if (in_array($role->name, ['super-admin', 'admin'])) {
            return back()->with('error', 'Cannot delete core system roles.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', "Role deleted.");
    }

    /** ────────────────────────────────────────
     *  User → Role Assignment
     * ──────────────────────────────────────── */
    public function users()
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        $roles = Role::orderBy('name')->get();
        return view('admin.roles.users', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);
        // حفظ في عمود role القديم للتوافق
        $user->update(['role' => $request->role === 'super-admin' ? 'admin' : $request->role]);

        return back()->with('success', "Role \"{$request->role}\" assigned to {$user->name}.");
    }

    public function removeRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|string']);
        $user->removeRole($request->role);
        return back()->with('success', "Role removed from {$user->name}.");
    }
}
