<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_complete_product_creation_workflow()
    {
        
        $electronicsCategory = Category::create(['name' => 'Electronics']);
        $smartphonesCategory = Category::create([
            'name' => 'Smartphones', 
            'parent_id' => $electronicsCategory->id
        ]);

        
        $productData = [
            'name' => 'iPhone 15 Pro',
            'description' => 'Latest iPhone with Pro features',
            'price' => 1199.99,
            'image' => 'iphone15pro.jpg'
        ];

        $product = Product::create($productData);

       
        $product->categories()->attach([
            $electronicsCategory->id,
            $smartphonesCategory->id
        ]);

     
        $this->assertDatabaseHas('products', $productData);
        $this->assertDatabaseHas('product_categories', [
            'product_id' => $product->id,
            'category_id' => $electronicsCategory->id
        ]);
        $this->assertDatabaseHas('product_categories', [
            'product_id' => $product->id,
            'category_id' => $smartphonesCategory->id
        ]);

      
        $this->assertEquals(2, $product->categories()->count());
        $this->assertTrue($product->categories->contains('name', 'Electronics'));
        $this->assertTrue($product->categories->contains('name', 'Smartphones'));
    }

    
    public function test_product_creation_with_mass_assignment_protection()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 99.99,
            'image' => 'test.jpg',
            'id' => 999, 
            'created_at' => '2020-01-01' 
        ];

        $product = Product::create($productData);

        $this->assertNotEquals(999, $product->id);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_creation_fails_with_invalid_data()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Product::create([
            'description' => 'Test description',
            'price' => 99.99
        ]);
    }

    
    public function test_product_creation_with_multiple_categories()
    {
        $categories = collect();
        $categories->push(Category::create(['name' => 'Electronics']));
        $categories->push(Category::create(['name' => 'Mobile Phones']));
        $categories->push(Category::create(['name' => 'Apple Products']));

        $product = Product::create([
            'name' => 'iPhone 15',
            'description' => 'Latest iPhone',
            'price' => 999.99
        ]);

        $product->categories()->attach($categories->pluck('id')->toArray());

        $this->assertEquals(3, $product->categories()->count());
        
        foreach ($categories as $category) {
            $this->assertTrue($category->products->contains($product));
        }

        $electronicsProducts = Product::byCategory($categories->first()->id)->get();
        $this->assertTrue($electronicsProducts->contains($product));
    }
}