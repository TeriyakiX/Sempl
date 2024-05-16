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
            'password' => Hash::make('admin'),
            'first_name' => 'Admin',
            'last_name' => 'User',
            'gender' => 'male',
            'birthdate' => '2000-01-01',
            'app_name' => 'Admin App',
            'email' => 'admin@example.com',
            'address' => '123 Admin St, Admin City',
            'role' => '1',
            'people_living_with' => null,
            'has_children' => null,
            'pets' => null,
            'average_monthly_income' => null,
            'percentage_spent_on_cosmetics' => null,
            'vk_profile' => null,
            'telegram_profile' => null,
            'profile_photo' => null,
            'delivery_address' => null,
            'city' => null,
            'street' => null,
            'house_number' => null,
            'apartment_number' => null,
            'entrance' => null,
            'postal_code' => null,
        ]);
    }
}
