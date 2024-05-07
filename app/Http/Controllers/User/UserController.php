<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $users = User::query()->with('roles')->get();
        $roles = Role::all();
        return \view('User.View',['users'=>$users->map(function($user){
             $user->role_name =\optional(\optional($user->roles)->first())->name;
             $user->role = $user->roles->first();
             return $user;
        }),'roles'=>$roles]);
    }

    public function assignRole(Request $request ,User $user){
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);
        return \redirect()->route('viewUsers');
    }
}
