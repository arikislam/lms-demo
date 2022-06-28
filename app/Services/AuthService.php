<?php

namespace App\Services;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Validator;

class AuthService
{

    public function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            'email'    => 'required|email|max:250|exists:users,email',
            'password' => 'required|min:6',
        ]);
    }

    public function checkUserPassword(User $user, Request $request): bool
    {
        return !Hash::check($request->get('password'), $user->password);
    }

    public function validateUserRegistration(Request $request)
    {
        return Validator::make($request->all(), [
            'email'                 => 'required|email|max:250|unique:users',
            'name'                  => 'required|string|max:250',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
    }

    public function registerNewUser(Request $request)
    {
        $data             = $request->only(['email', 'name']);
        $data['password'] = Hash::make($request->get('password'));
        return User::create($data);

    }

    public function logOutUser($user): bool
    {
        if (!blank($user)) {
            $user->currentAccessToken()->delete();
        }

        return true;
    }
}