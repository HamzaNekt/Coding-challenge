<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_product_can_be_created_with_valid_data()
    {
        $productData = [
            'name' => 'iPhone 15',
            'description' => 'Latest iPhone model',
            'price' => 999.99,
            'image' => 'iphone15.jpg'
        ];

        $product = Product::create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('iPhone 15', $product->name);
        $this->assertEquals(999.99, $product->price);
        $this->assertDatabaseHas('products', $productData);
    }

    /** @test */
    public function test_product_price_is_cast_to_float()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => '19.99'
        ]);

        $this->assertIsFloat($product->price);
        $this->assertEquals(19.99, $product->price);
    }

    /** @test */
    public function test_product_can_have_categories()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 99.99
        ]);

        $category = Category::create(['name' => 'Electronics']);
        
        $product->categories()->attach($category->id);

        $this->assertEquals(1, $product->categories()->count());
        $this->assertEquals('Electronics', $product->categories->first()->name);
    }

    /** @test */
    public function test_product_scope_by_category()
    {
        $category = Category::create(['name' => 'Electronics']);
        
        $product1 = Product::create([
            'name' => 'iPhone',
            'description' => 'Smartphone',
            'price' => 999.99
        ]);
        
        $product2 = Product::create([
            'name' => 'Laptop',
            'description' => 'Computer',
            'price' => 1299.99
        ]);

        $product1->categories()->attach($category->id);

        $productsInCategory = Product::byCategory($category->id)->get();

        $this->assertEquals(1, $productsInCategory->count());
        $this->assertEquals('iPhone', $productsInCategory->first()->name);
    }

    /** @test */
    public function test_product_can_be_sorted_by_price()
    {
        Product::create(['name' => 'Expensive Product', 'description' => 'Test', 'price' => 100.00]);
        Product::create(['name' => 'Cheap Product', 'description' => 'Test', 'price' => 10.00]);
        Product::create(['name' => 'Medium Product', 'description' => 'Test', 'price' => 50.00]);

        $productsAsc = Product::orderBy('price', 'asc')->get();
        $this->assertEquals('Cheap Product', $productsAsc->first()->name);
        $this->assertEquals('Expensive Product', $productsAsc->last()->name);

        $productsDesc = Product::orderBy('price', 'desc')->get();
        $this->assertEquals('Expensive Product', $productsDesc->first()->name);
        $this->assertEquals('Cheap Product', $productsDesc->last()->name);
    }
}