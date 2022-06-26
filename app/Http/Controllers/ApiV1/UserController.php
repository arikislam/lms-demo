<?php

namespace App\Http\Controllers\ApiV1;

use Carbon\Carbon;

class UserController extends ApiController
{
    public function getUser()
    {
        $user = auth()->user();
        $data = [
            'user'    => [
                'name'  => data_get($user, 'name'),
                'email' => data_get($user, 'email'),
            ],
            'isAdmin' => (bool)data_get($user, 'is_admin'),
        ];

        return $this->successResponse($data, 'Login success');
    }
}