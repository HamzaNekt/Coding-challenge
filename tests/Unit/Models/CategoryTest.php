<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_category_can_be_created()
    {
        $category = Category::create(['name' => 'Electronics']);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Electronics', $category->name);
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }

    /** @test */
    public function test_category_can_have_products()
    {
        $category = Category::create(['name' => 'Electronics']);
        $product = Product::create([
            'name' => 'iPhone',
            'description' => 'Smartphone',
            'price' => 999.99
        ]);

        $product->categories()->attach($category->id);

        $this->assertEquals(1, $category->products()->count());
        $this->assertEquals('iPhone', $category->products->first()->name);
    }
}