<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Permission\PermissionSaveRequest;

class PermissionController extends Controller
{
    public function index($permission = null)
    {
        \request()->merge(['page' => \request('page') == null ? 1 : \request('page')]);
        $permissions = Permission::latest()
            ->skip((int) 5 * (int)  \request('page') - (int) 5)
            ->take((int) 5)->get();
        return view('Permission.View', ['permissions' => $permissions,'permissions_count'=>Permission::all()->count(), 'permission' => $permission]);
    }

    public function save(PermissionSaveRequest $request)
    {
        Permission::updateOrCreate(['id' => $request->id], $request->all() + ['guard' => 'api']);
        Session::flash('message', 'The permission was saved successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->route('viewPermissions');
    }

    public function edit(Permission $permission)
    {
        return $this->index($permission);
    }

    public function delete(Permission $permission)
    {
        $permission->delete();
        Session::flash('message', 'The permission was deleted successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewPermissions');
    }
}
