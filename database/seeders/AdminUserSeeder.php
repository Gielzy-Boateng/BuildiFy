<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@cms.com'], // FIRST array: what to match by
            [ // SECOND array: what to create/update
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'slug' => 'admin',
                'role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}