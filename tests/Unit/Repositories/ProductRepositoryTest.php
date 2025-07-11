<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use App\Repositories\ProductRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository(new Product());
    }

    /** @test */
    public function test_can_get_all_products()
    {
        
        Product::create([
            'name' => 'iPhone 15',
            'description' => 'Latest iPhone',
            'price' => 999.99
        ]);
        
        Product::create([
            'name' => 'Samsung Galaxy',
            'description' => 'Android phone',
            'price' => 799.99
        ]);

        $products = $this->repository->all();

        $this->assertCount(2, $products);
        $this->assertEquals('iPhone 15', $products->first()->name);
    }

    /** @test */
    public function test_can_create_product()
    {
        $productData = [
            'name' => 'MacBook Pro',
            'description' => 'Laptop computer',
            'price' => 1999.99,
            'image' => 'macbook.jpg'
        ];

        $product = $this->repository->create($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('MacBook Pro', $product->name);
        $this->assertEquals(1999.99, $product->price);
        $this->assertDatabaseHas('products', $productData);
    }

    /** @test */
    public function test_can_find_product_by_id()
    {
        $createdProduct = Product::create([
            'name' => 'iPad',
            'description' => 'Tablet',
            'price' => 599.99
        ]);

        $foundProduct = $this->repository->findById($createdProduct->id);

        $this->assertInstanceOf(Product::class, $foundProduct);
        $this->assertEquals('iPad', $foundProduct->name);
        $this->assertEquals($createdProduct->id, $foundProduct->id);
    }

    /** @test */
    public function test_find_non_existent_product_returns_null()
    {
        $foundProduct = $this->repository->findById(999);

        $this->assertNull($foundProduct);
    }

    /** @test */
    public function test_can_filter_products_by_category()
    {
        
        $category = Category::create(['name' => 'Electronics']);

     
        $product1 = Product::create([
            'name' => 'iPhone',
            'description' => 'Smartphone',
            'price' => 999.99
        ]);

        $product2 = Product::create([
            'name' => 'Book',
            'description' => 'Reading material',
            'price' => 19.99
        ]);

        
        $product1->categories()->attach($category->id);

      
        $filteredProducts = $this->repository->filter(['category_id' => $category->id]);

        $this->assertCount(1, $filteredProducts);
        $this->assertEquals('iPhone', $filteredProducts->first()->name);
    }

    /** @test */
    public function test_can_sort_products_by_price_ascending()
    {
        
        Product::create(['name' => 'Expensive', 'description' => 'Test', 'price' => 999.99]);
        Product::create(['name' => 'Cheap', 'description' => 'Test', 'price' => 19.99]);
        Product::create(['name' => 'Medium', 'description' => 'Test', 'price' => 199.99]);

        $sortedProducts = $this->repository->filter(['sort_by_price' => 'asc']);

        $this->assertEquals('Cheap', $sortedProducts->first()->name);
        $this->assertEquals('Expensive', $sortedProducts->last()->name);
    }

    /** @test */
    public function test_can_sort_products_by_price_descending()
    {
        
        Product::create(['name' => 'Expensive', 'description' => 'Test', 'price' => 999.99]);
        Product::create(['name' => 'Cheap', 'description' => 'Test', 'price' => 19.99]);

        $sortedProducts = $this->repository->filter(['sort_by_price' => 'desc']);

        $this->assertEquals('Expensive', $sortedProducts->first()->name);
        $this->assertEquals('Cheap', $sortedProducts->last()->name);
    }

    /** @test */
    public function test_can_filter_by_category_and_sort_by_price()
    {
        
        $category = Category::create(['name' => 'Electronics']);

     
        $expensivePhone = Product::create([
            'name' => 'iPhone Pro',
            'description' => 'Premium phone',
            'price' => 1299.99
        ]);

        $cheapPhone = Product::create([
            'name' => 'iPhone Standard',
            'description' => 'Standard phone',
            'price' => 699.99
        ]);

        $book = Product::create([
            'name' => 'Programming Book',
            'description' => 'Learn coding',
            'price' => 49.99
        ]);

     
        $expensivePhone->categories()->attach($category->id);
        $cheapPhone->categories()->attach($category->id);
      

 
        $results = $this->repository->filter([
            'category_id' => $category->id,
            'sort_by_price' => 'desc'
        ]);

        $this->assertCount(2, $results);
        $this->assertEquals('iPhone Pro', $results->first()->name);
        $this->assertEquals('iPhone Standard', $results->last()->name);

        $this->assertFalse($results->contains('name', 'Programming Book'));
    }

    /** @test */
    public function test_filter_with_empty_filters_returns_all_products()
    {
        Product::create(['name' => 'Product 1', 'description' => 'Test', 'price' => 10.00]);
        Product::create(['name' => 'Product 2', 'description' => 'Test', 'price' => 20.00]);

        $products = $this->repository->filter([]);

        $this->assertCount(2, $products);
    }
}