<?php

namespace App\Http\Controllers\ApiV1;

use App\Models\User;
use App\Services\AuthService;
use App\Transformers\UserTransformer;
use Exception;
use Illuminate\Http\Request;
use Log;

class AuthController extends ApiController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request)
    {
        $validation = $this->authService->validateLogin($request);

        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->messages()->toArray());
        }

        $user = User::findByEmail($request->get('email'));
        if (!$user || $this->authService->checkUserPassword($user, $request)) {
            return $this->validationErrorResponse([
                'email' => [
                    'Credentials did not match',
                ],
            ]);
        }

        try {
            return $this->successResponse(app(UserTransformer::class)->transformUserForLogin($user), 'Login success');

        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot login user');
        }

    }

    public function register(Request $request)
    {
        $validation = $this->authService->validateUserRegistration($request);

        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->messages()->toArray());
        }

        try {
            return $this->successResponse(app(UserTransformer::class)->transformUserForLogin($this->authService->registerNewUser($request)), 'Register successful');
        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot register user');

        }
    }

    public function logout()
    {
        try {
            return $this->successResponse($this->authService->logOutUser(auth()->user()), 'Logged out.');

        } catch (Exception $e) {
            Log::error($e);
            return $this->errorResponse('Cannot logout user');

        }


    }
}