<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'email'    => 'admin@admin.com',
            'name'     => 'Admin',
            'is_admin' => true,
            'password' => Hash::make('password'),
        ];

        User::create($data);

    }
}
