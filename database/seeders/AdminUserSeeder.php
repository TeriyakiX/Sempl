<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'login' => 'admin',
            'password' => Hash::make('admin'), // Используем Hash::make для хеширования пароля
            'first_name' => 'Admin',
            'last_name' => 'User',
            'gender' => 'male',
            'birthdate' => '2000-01-01',
            'app_name' => 'Admin App',
            'email' => 'admin@example.com',
            'address' => '123 Admin St, Admin City',
            'role' => '1', // Устанавливаем роль пользователя как 1
        ]);
    }
}
