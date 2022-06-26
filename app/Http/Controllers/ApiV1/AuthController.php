<?php

namespace App\Http\Controllers\ApiV1;

use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'    => 'required|email|max:250|exists:users,email',
            'password' => 'required|min:6',
        ]);


        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->messages()->toArray());
        }

        $user = User::where('email', $request->get('email'))->first();
        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return $this->validationErrorResponse([
                'email' => [
                    'Credentials did not match',
                ],
            ]);
        }

        $data = [
            'user'    => [
                'name'  => data_get($user, 'name'),
                'email' => data_get($user, 'email'),
            ],
            'isAdmin' => (bool)data_get($user, 'is_admin'),
            'token'   => $user->createToken('lms-demo')->plainTextToken,
            'expiry'  => Carbon::now()
                ->addYear()
                ->endOfDay()
                ->format('d-m-Y\TH:i'),
        ];

        return $this->successResponse($data, 'Login success');

    }

    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'                 => 'required|email|max:250|unique:users',
            'name'                  => 'required|string|max:250',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->messages()->toArray());
        }

        $data             = $request->only(['email', 'name']);
        $data['password'] = Hash::make($request->get('password'));
        $user             = User::create($data);


        return $this->successResponse($user->toArray(), 'Register successful');
    }

    public function logout()
    {
        if (!blank(auth()->user())) {
            auth()->user()->currentAccessToken()->delete();
        }

        return $this->successResponse([], 'Logged out.');

    }
}