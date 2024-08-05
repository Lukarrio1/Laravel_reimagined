<?php

namespace App\Http\Controllers\Api\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\Node\Node;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\PasswordEmailRequest;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $token = Str::random(50);
        $setting = \optional(Cache::get('settings', \collect([]))->where('key', 'registration_role')->first())
            ->getSettingValue();
        $api_email_verification = (int) \optional(Cache::get('settings', \collect([]))
            ->where('key', 'api_email_verification')->first())
            ->getSettingValue();
        $email_token = '';
        if ($api_email_verification == 1) {
            $client_app_url = \optional(Cache::get('settings', \collect([]))->where('key', 'client_app_url')->first())
                ->getSettingValue();
            $verification_front_end_link = \explode('/', \optional(optional(Node::where('uuid', 'yuUkEHFptRPqzkBdOosQPeU5yeKbycDcE2qPvmr8LhIb6OmlYE')->first()->properties)['value'])->node_route);
            $email_token = Str::random(30);
            $verification_front_end_link = $client_app_url . collect($verification_front_end_link)
                ->filter(fn ($_, $idx) => 1 + $idx != \count($verification_front_end_link))
                ->join('/') . '/' . $email_token;
            $this->sendEmail(
                $request->email,
                "Email Verification",
                "Click <a href='$verification_front_end_link'>here</a> to verify your email address."
            );
        }
        $role = !empty($setting) ? Role::find($setting) : null;
        $user = User::create($request->except('password') + [
            'last_login_at' => Carbon::now(),
            'password' => Hash::make($request->password),
            'password_reset_token' => $token,
            'email_verification_token' => $email_token
        ]);

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
        $api_email_verification = (int) \optional(Cache::get('settings', \collect([]))
           ->where('key', 'api_email_verification')->first())
           ->getSettingValue();
        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;
                User::find($user->id)->update(['last_login_at' => Carbon::now()]);
                if($api_email_verification == 1) {
                    return !empty($user->email_verified_at) ?
                    \response()->json(['token' => $token, 'user' => $user]) :
                    response()->json(['message' => 'Invalid Credentials'], 401);
                }
                return \response()->json(['token' => $token, 'user' => $user]);
            }
        }
        return response()->json(['message' => 'Invalid Credentials'], 401);
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        $token = '';
        if (!empty($user)) {
            $user->update(['email_verified_at' => Carbon::now(), 'email_verification_token' => str::random(31)]);
            $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;
        }
        return \response()->json(['message' => "Your email was successfully verified.", 'token' => $token]);
    }
}
