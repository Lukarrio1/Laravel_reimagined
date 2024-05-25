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
        $roles = Role::with('permissions')->get()
            ->map(fn($role) => [ ...$role->toArray(),
                'permission_name' => collect($role->permissions)->map(fn($permission) => $permission->name)]);
        $permissions = Permission::all();
        return view('Role.View', ['role' => optional($role)->load('permissions'), 'roles' => $roles, 'permissions' => $permissions]);
    }

    public function save(RoleSaveRequest $request)
    {
        Role::updateOrCreate(['id' => $request->id], $request->all()+['guard'=>'api'])
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
