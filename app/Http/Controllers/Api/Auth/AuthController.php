<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Mail\Api\Auth\PasswordEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\PasswordEmailRequest;

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

        return ['user' => $user,'token' => $token];
    }

    public function sendPasswordEmail(PasswordEmailRequest $request)
    {

        $this->sendEmail($request->email, 'Password Email', "Hello there");
        return \response()->json(['message' => "A mail was sent to the provided email address"]);
    }


}
