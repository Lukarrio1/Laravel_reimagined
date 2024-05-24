<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $translate = [
            'name' => 'name',
            'email' => 'email',
            'role' => 'name',
        ];

        $translateExamples = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ];

        $search = \request()->get('search', '||');
        $searchParams = collect(explode('|', $search))
            ->filter(fn ($section) => !empty($section)) // Filter out empty sections
            ->map(function ($section) {
                return explode(':', $section);
            });
        // Build the search placeholder
        $searchPlaceholder = \collect($translate)->keys()->map(function ($key, $idx) use ($translate, $translateExamples) {
            if ($idx == 0) {
                return '|' . $key . ":$translateExamples[$key]";
            }
            if ($idx + 1 == count($translate)) {
                return $key . ":$translateExamples[$key]|";
            }
            return $key . ":$translateExamples[$key]";
        })->join('|');

        $users = User::query()->with('roles');
        $roles = Role::all();

        $searchParams->when(
            $searchParams->filter(fn ($val) => \count($val) > 1)->count() > 0,
            fn ($collection) => $collection->each(function ($section) use ($users, $translate) {
                list($key, $value) = $section;
                // Check if the key is valid in the translation map
                if (!isset($translate[$key])) {
                    return; // Skip invalid keys
                }
                // Convert 'type' value to its corresponding node type ID
                if ($translate[$key] === 'role') {
                    $convertedValue = $value;
                } else {
                    $convertedValue = $value;
                }
                if ($translate[$key] === 'role') {
                    $users->whereHas('roles', fn ($q) => $q->where($translate[$key], 'LIKE', '%' . $convertedValue . '%')); // Apply the condition to the query)
                } else {
                    $users->where($translate[$key], 'LIKE', '%' . $convertedValue . '%'); // Apply the condition to the query

                }
            })
        );
        $users = $users->take(\request()->get('load_more'))->get();

        return \view('User.View', ['users' => $users->map(function (User $user) {
            $user->role_name = \optional(\optional($user->roles)->first())->name;
            $user->role = $user->roles->first();
            $user = $user->updateUserHtml();
            return $user;
        }), 'roles' => $roles, 'search_placeholder' => $searchPlaceholder]);
    }

    public function assignRole(Request $request, User $user)
    {
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);
        Session::flash('message', 'The role was assigned successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->route('viewUsers');
    }

    public function update(UserUpdateRequest $request)
    {
        User::find((int) $request->id)->update($request->except(['password']) + ['password' => Hash::make($request->password)]);
        Session::flash('message', 'The user was saved successfully.');
        Session::flash('alert-class', 'alert-success');

        return \redirect()->route('viewUsers');
    }

    public function delete(User $user)
    {
        $user->delete();
        Session::flash('message', 'The user was saved successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->back();
    }
}
