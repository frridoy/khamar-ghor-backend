<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['phone_number' => '01614898789'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@app.com',
                'user_role' => 0,
                'is_active' => true,
                'password' => Hash::make('password'),
            ]
        );
    }
}
