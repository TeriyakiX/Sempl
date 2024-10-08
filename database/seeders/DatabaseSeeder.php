<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FeedbackSeeder::class);
        $this->call(RegistrationQuestionsSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
