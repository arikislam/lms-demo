<?php

namespace App\Transformers;

use Carbon\Carbon;

class UserTransformer
{
    public function transformUserForLogin($user)
    {
        return [
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
    }
}