<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Housing']);
        Category::create(['name' => 'Utilities']);
        Category::create(['name' => 'Food']);
        Category::create(['name' => 'Transport']);
        Category::create(['name' => 'Entertainment']);
        Category::create(['name' => 'Health']);
        Category::create(['name' => 'Education']);
        Category::create(['name' => 'Other']);
    }
}
