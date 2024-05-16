<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\PasswordEmailRequest;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $token = Str::random(50);
        $setting = \optional(Setting::where('key', 'registration_role')->first())
            ->getSettingValue();
        $role = !empty($setting) ? Role::find($setting) : null;
        $user = User::create($request->except('password') + ['password' => Hash::make($request->password), 'password_reset_token' => $token]);
        if (!empty($role)) {
            $user->assignRole($role);
        }

        $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function sendPasswordEmail(PasswordEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {
            $token = Str::random(50);
            $user->update(['password_reset_token' => $token]);
            $route = "/per/{$token}";
            $this->sendEmail($request->email, 'Password Email', "Click <a href='$route'> here to reset your password.</a>");
        }
        return \response()->json(['message' => "A mail was sent to the provided email address"]);
    }

    public function resetPassword(PasswordUpdateRequest $request, $param)
    {
        $user = User::where('password_reset_token', $param)->first();
        $email = $user->email;
        if (!empty($user)) {
            $token = Str::random(50);
            $user->update(['password' => Hash::make($request->password), 'password_reset_token' => $token]);
            $this->sendEmail($email, 'Password Update', 'Your password was updated successfully');
        }
        return response()->json(['message' => "You've updated your password successfully."]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;
                return \response()->json(['token' => $token, 'user' => $user]);
            }
        }
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }

    public function sendVerificationEmail()
    {
        $user = \request()->user();
        if (!empty($user)) {
            $route = "/per/";
            $this->sendEmail($user->email, 'Password Email', "Click <a href='$route'> here to reset your password.</a>");
        }
        return \response()->json(['message' => "A mail was sent to the provided email address"]);
    }

}
