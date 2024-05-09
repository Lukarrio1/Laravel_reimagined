<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $setting = \optional(Setting::where('key', 'registration_role')->first())
            ->getSettingValue();
        $role = !empty($setting) ? Role::find($setting) : null;
        $user = User::create($request->except('password') + ['password' => Hash::make($request->password)]);
        if (!empty($role)) {
            $user->assignRole($role);

        }
        $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;

        return ['user'=>$user,'token'=>$token];
    }


}
