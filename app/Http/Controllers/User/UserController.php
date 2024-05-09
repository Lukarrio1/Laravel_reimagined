<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->with('roles')->get();
        $roles = Role::all();
        return \view('User.View', ['users' => $users->map(function (User $user) {
            $user->role_name = \optional(\optional($user->roles)->first())->name;
            $user->role = $user->roles->first();
            $user = $user->updateUserHtml();
            return $user;
        }),'roles' => $roles]);
    }

    public function assignRole(Request $request, User $user)
    {
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);
        return \redirect()->route('viewUsers');
    }


    public function updateUser(UserUpdateRequest $request)
    {
        User::find((int)$request->id)->update($request->except(['password']) + ['password' => Hash::make($request->password)]);

        return \redirect()->route('viewUsers');
    }
}
