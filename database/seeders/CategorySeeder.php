<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{
    public function run()
    {
        $mainCategories = Category::factory()->count(5)->create();

        $mainCategories->each(function ($mainCategory) {
            $subcategories = Category::factory()->count(3)->create([
                'parent_id' => $mainCategory->id,
            ]);
        });
    }
}
