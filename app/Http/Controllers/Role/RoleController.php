<?php

namespace App\Http\Controllers\Role;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Role\RoleSaveRequest;

class RoleController extends Controller
{
    public function index($role = null)
    {
        \request()->merge(['page' => \request('page') == null ? 1 : \request('page')]);
        $roles = Role::with('permissions')->skip((int) 5 * (int) \request('page') - (int) 5)
            ->take((int) 5)->get()
            ->map(fn ($role) => [
                ...$role->toArray(),
                'permission_name' => collect($role->permissions)->map(fn ($permission) => $permission->name)
            ]);
        $permissions = Permission::all();
        return view('Role.View', ['role' => optional($role)->load('permissions'), 'roles' => $roles, 'permissions' => $permissions, 'roles_count' => Role::all()->count()]);
    }

    public function save(RoleSaveRequest $request)
    {
        Role::updateOrCreate(['id' => $request->id], $request->all() + ['guard' => 'api'])
            ->syncPermissions(Permission::whereIn('id', $request->permissions)
                ->pluck('name')->toArray());
        Session::flash('message', 'The role was saved successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewRoles');
    }

    public function edit(Role $role)
    {
        return $this->index($role);
    }

    public function delete(Role $role)
    {
        $role->delete();
        Session::flash('message', 'The role was deleted successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewRoles');
    }
}
