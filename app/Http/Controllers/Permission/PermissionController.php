<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Permission\PermissionSaveRequest;

class PermissionController extends Controller
{
    public function index($permission = null)
    {
        $permissions = Permission::all();
        return view('Permission.View', ['permissions' => $permissions, 'permission' => $permission]);
    }

    public function save(PermissionSaveRequest $request)
    {
        Permission::updateOrCreate(['id'=>$request->id],$request->all());
        return \redirect()->route('viewPermissions');
    }

    public function edit(Permission $permission){
        return $this->index($permission);
    }

    public function delete(Permission $permission){
        $permission->delete();
        return \redirect()->route('viewPermissions');
    }
}
