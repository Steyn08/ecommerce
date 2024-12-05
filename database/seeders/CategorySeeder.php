<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Electronics'],
            ['name' => 'Fashion'],
            ['name' => 'Home & Kitchen'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Books'],
            ['name' => 'Toys & Games'],
        ]);
    }
}
