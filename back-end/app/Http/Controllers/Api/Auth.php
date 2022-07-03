<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Auth extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        $result = auth()->attempt($request->validated());

        if (!$result) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = User::where('email', $request->email)->first();

        return response()->success([
            'user' => UserResource::make($user),
            'token' => $user->generateToken()
        ]);
    }

    public function user(Request $request)
    {

        $user = $request->user();
        return response()->success([
            'user' => UserResource::make($user),
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();

        return response()->success();
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('name', 'email') + [
                'password' => bcrypt($request->password)
            ]);
        $user->markEmailAsVerified();

        event(new Registered($user));

        return response()->success([
            'user' => UserResource::make($user),
            'token' => $user->generateToken()
        ]);
    }

    public function reset(PasswordResetRequest $request)
    {
        Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return response()->success();
    }
}