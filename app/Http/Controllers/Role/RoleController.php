<?php

namespace App\Http\Controllers\Role;

use App\Models\Setting;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Role\RoleSaveRequest;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:can crud roles');
    }

    public function index($role = null)
    {
        $roles_count = Role::all()->count();
        $max_amount_of_pages= $roles_count/5;
        \request()->merge(['page' => \request('page') == null || (int) \request('page') < 1 ? 1 : ((int)\request('page') > $max_amount_of_pages ? \ceil($max_amount_of_pages) : \request('page'))]);
        $setting = \optional(Setting::where('key', 'admin_role')->first())->getSettingValue();
        $role_for_checking = !empty($setting) ? Role::find((int)$setting) : null;
        $roles = Role::with('permissions')
            ->when(!\request()->user()->hasRole($role_for_checking), fn ($q) => $q->where('priority', '>', Role::min('priority')))
            ->skip((int) 5 * (int) \request('page') - (int) 5)
            ->take((int) 5)->get()
            ->map(fn ($role) => [
                ...$role->toArray(),
                'permission_name' => collect($role->permissions)->map(fn ($permission) => $permission->name)
            ]);
        $permissions = Permission::all();
        return view('Role.View', ['role' => optional($role)->load('permissions'), 'roles' => $roles, 'permissions' => $permissions, 'roles_count' => $roles_count]);
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
