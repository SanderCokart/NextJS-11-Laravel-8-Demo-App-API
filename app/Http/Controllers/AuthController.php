<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function verify(EmailVerificationRequest $request): JsonResponse
    {
        $request->fulfill();

        return response()->json([
            'message' => 'Email has been verified!',
        ], 200);
    }

    public function sign_up(Request $request): void
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create(array_merge($validatedData, ['password' => bcrypt($validatedData['password'])]));

        event(new Registered($user));
    }

    public function login(Request $request): JsonResponse
    {
        //validate login
        $validatedData = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
            'remember_me' => 'boolean|nullable',
        ]);

        $remember_me = $validatedData['remember_me'] ?? false;

        //attempt login otherwise return abort 401
        if (!auth()->attempt($validatedData, $remember_me)) {
            abort(401);
        }

        //return user
        return response()->json(['user' => auth()->user()]);
    }

    public function check(): JsonResponse
    {
        return response()->json(['user' => auth()->user()]);
    }

    public function logout(): void
    {
        auth()->guard('web')->logout();
    }

    public function forget_password(Request $request): JsonResponse
    {
        $validatedData = $request->validate(['email' => 'required|email|string']);

        return response()->json(['status' => Password::sendResetLink($validatedData)]);
    }


    public function reset_password(Request $request): string
    {
        return Password::reset(
            $request->validate([
                'password' => 'confirmed|min:6|required',
                'email' => 'email|required',
                'token' => 'required',
            ]),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])//                    ->setRememberToken(Str::random(60))
                ;

                $user->save();

                event(new PasswordReset($user));
            }
        );

    }
}
