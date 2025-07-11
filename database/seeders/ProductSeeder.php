<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with titanium design and advanced camera system',
                'price' => 9999.99,
                'image' => 'iphone15pro.jpg',
                'category_ids' => [1, 2] 
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Powerful Android smartphone with AI features',
                'price' => 8999.99,
                'image' => 'galaxy-s24.jpg',
                'category_ids' => [1, 2] 
            ],
            [
                'name' => 'MacBook Pro 14"',
                'description' => 'Professional laptop with M3 chip for demanding workflows',
                'price' => 19999.99,
                'image' => 'macbook-pro-14.jpg',
                'category_ids' => [1, 3] 
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Ultra-portable laptop with stunning display',
                'price' => 12999.99,
                'image' => 'dell-xps-13.jpg',
                'category_ids' => [1, 3] 
            ],
            [
                'name' => 'The Great Gatsby',
                'description' => 'Classic American novel by F. Scott Fitzgerald',
                'price' => 129.99,
                'image' => 'great-gatsby.jpg',
                'category_ids' => [4, 5] 
            ],
            [
                'name' => 'Clean Code',
                'description' => 'A handbook of agile software craftsmanship',
                'price' => 429.99,
                'image' => 'clean-code.jpg',
                'category_ids' => [4, 6] 
            ],
            [
                'name' => 'Casual T-Shirt',
                'description' => 'Comfortable cotton t-shirt for everyday wear',
                'price' => 199.99,
                'image' => 'casual-tshirt.jpg',
                'category_ids' => [7, 8] 
            ],
            [
                'name' => 'Summer Dress',
                'description' => 'Elegant dress perfect for summer occasions',
                'price' => 799.99,
                'image' => 'summer-dress.jpg',
                'category_ids' => [7, 9] 
            ],
        ];

        foreach ($products as $productData) {
            $categoryIds = $productData['category_ids'];
            unset($productData['category_ids']);
            
            $product = Product::create($productData);
            $product->categories()->attach($categoryIds);
        }
    }
}
