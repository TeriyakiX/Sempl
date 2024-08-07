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
            'password' => Hash::make('admin'), // Устанавливаем пароль
            'first_name' => 'Admin',
            'last_name' => 'User',
            'gender' => 'male',
            'birthdate' => '2000-01-01',
            'app_name' => 'Admin App',
            'email' => 'admin@example.com',
            'address' => '123 Admin St, Admin City',
            'role' => '1',
            'people_living_with_id' => 3,
            'has_children_id' => 6,
            'pets_id' => 11,
            'average_monthly_income_id' => 13,
            'percentage_spent_on_cosmetics_id' => 16,
            'delivery_address' => '123 Admin St, Admin City',
            'city' => 'Admin City',
            'street' => '123 Admin St',
            'house_number' => '123',
            'apartment_number' => null,
            'entrance' => null,
            'postal_code' => '12345',
        ]);
    }
}
