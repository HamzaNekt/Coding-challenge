<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'Electronics', 'parent_id' => null],
            ['id' => 2, 'name' => 'Smartphones', 'parent_id' => 1],
            ['id' => 3, 'name' => 'Laptops', 'parent_id' => 1],
            ['id' => 4, 'name' => 'Books', 'parent_id' => null],
            ['id' => 5, 'name' => 'Fiction', 'parent_id' => 4],
            ['id' => 6, 'name' => 'Non-Fiction', 'parent_id' => 4],
            ['id' => 7, 'name' => 'Clothing', 'parent_id' => null],
            ['id' => 8, 'name' => 'Men', 'parent_id' => 7],
            ['id' => 9, 'name' => 'Women', 'parent_id' => 7],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}