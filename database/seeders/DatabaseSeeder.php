<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory(10)->create();
        $this->call(FeedbackSeeder::class);
        $this->call(CategoryFactory::class);
        $this->call(UserFactory::class);
        $this->call(ProductFactory::class);
        $this->call(CategorySeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(RegistrationQuestionsSeeder::class);
    }
}
