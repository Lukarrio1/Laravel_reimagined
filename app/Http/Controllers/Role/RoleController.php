<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleSaveRequest;

class RoleController extends Controller
{
    public function index($role=null)
    {
        $roles = Role::all();
        return view('Role.View',['role'=>$role,'roles'=>$roles]);
    }

    public function save(RoleSaveRequest $request)
    {
        Role::updateOrCreate(['id'=>$request->id],$request->all());
        return \redirect()->route('viewRoles');
    }

    public function edit(Role $role){
        return $this->index($role);
    }

    public function delete(Role $role){
        $role->delete();
        return \redirect()->route('viewRoles');
    }
}
