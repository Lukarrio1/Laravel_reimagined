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
    protected function processVerificationEmail($email)
    {
        $client_app_url = \getSetting('client_app_url');
        $verification_front_end_link = \explode('/', \optional(optional(Node::where('uuid', 'yuUkEHFptRPqzkBdOosQPeU5yeKbycDcE2qPvmr8LhIb6OmlYE')->first()->properties)['value'])->node_route);
        $email_token = Str::random(30);
        $verification_front_end_link = $client_app_url . collect($verification_front_end_link)
            ->filter(fn ($_, $idx) => 1 + $idx != \count($verification_front_end_link))
            ->join('/') . '/' . $email_token;
        \defer(fn () =>   $this->sendEmail(
            $email,
            "Email Verification",
            "Click <a href='$verification_front_end_link'>here</a> to verify your email address."
        ));



    }

    public function register(RegisterRequest $request)
    {
        $token = Str::random(50);
        $setting = \optional(Cache::get('settings', \collect([]))->where('key', 'registration_role')->first())
            ->getSettingValue();
        $api_email_verification = (int) \getSetting('api_email_verification');
        $email_token = '';
        if ($api_email_verification == 1) {
            $this->processVerificationEmail($request->email);
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

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function sendPasswordEmail(PasswordEmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!empty($user)) {
            $token = Str::random(50);
            $user->update(['password_reset_token' => $token]);
            $route = "/per/{$token}";
            \defer(fn () => $this->sendEmail($request->email, 'Password Email', "Click <a href='$route'> here to reset your password.</a>"));
        }
        return \response()->json(['message' => "An email was sent to the provided email address"]);
    }

    public function resetPassword(PasswordUpdateRequest $request, $param)
    {
        $user = User::where('password_reset_token', $param)->first();
        $email = $user->email;
        if (!empty($user)) {
            $token = Str::random(50);
            $user->update(['password' => Hash::make($request->password), 'password_reset_token' => $token]);
            \defer(fn () =>   $this->sendEmail($email, 'Password Update', 'Your password was updated successfully'));
        }
        return response()->json(['message' => "You've updated your password successfully."]);
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->whereEmail($request->email)->first();
        $api_email_verification = (int) \getSetting('api_email_verification');
        // no record found
        if (empty($user)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
        // password mismatch
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        User::find($user->id)->update(['last_login_at' => Carbon::now()]);

        $token = $user->createToken($user->name . '_' . Carbon::now(), ['*'], Carbon::now()->addDays(6))->plainTextToken;
        // email verification check
        if ($api_email_verification == 0) {
            return  \response()->json(['token' => $token, 'user' => $user]);
        }
        //if empty the user needs to verify email address
        if (empty($user->email_verified_at)) {
            $this->processVerificationEmail($user->email);
            return response()
                   ->json([
                    'message' => 'Please verify your email address, an email was sent to your email address when registered.'
                ], 401);
        }

        return \response()->json(['token' => $token, 'user' => $user]);
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
