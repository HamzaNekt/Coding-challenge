<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\Category;
use App\Services\ProductService;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->productService = new ProductService(
            new ProductRepository(new Product()),      
            new CategoryRepository(new Category())    
        );
    }

    /** @test */
    public function it_can_create_product_without_categories()
    {
        // Arrange
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'image' => 'test-image.jpg'
        ];

        $result = $this->productService->createProduct($productData);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Test Product', $result->name);
        $this->assertEquals(99.99, $result->price);
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99
        ]);
    }

    /** @test */
    public function it_can_create_product_with_categories()
    {
    
        $category1 = Category::create(['name' => 'Electronics']);
        $category2 = Category::create(['name' => 'Smartphones']);
        
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'image' => 'test-image.jpg',
            'category_ids' => [$category1->id, $category2->id]
        ];

        $result = $this->productService->createProduct($productData);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Test Product', $result->name);
        $this->assertCount(2, $result->categories);
        $this->assertTrue($result->categories->contains($category1));
        $this->assertTrue($result->categories->contains($category2));
    }

    /** @test */
    public function it_throws_exception_when_category_does_not_exist()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'category_ids' => [999] // Non-existent category
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Category with ID 999 does not exist.');

        $this->productService->createProduct($productData);
    }

   /** @test */
public function it_can_get_all_products_with_filters()
{
    $category = Category::create(['name' => 'Electronics']);
    $product1 = Product::create([
        'name' => 'Product 1',
        'description' => 'Description 1',
        'price' => 50.00
    ]);
    $product2 = Product::create([
        'name' => 'Product 2', 
        'description' => 'Description 2',
        'price' => 100.00
    ]);
    
    $product1->categories()->attach($category);

    $filters = [
        'category_id' => $category->id,
        'sort_by_price' => 'asc'
    ];

    $result = $this->productService->getAllProducts($filters);

    $this->assertEquals(1, $result->count());           
    $this->assertEquals($product1->id, $result->first()->id);
}

/** @test */
public function it_can_get_all_products_without_filters()
{
    Product::create([
        'name' => 'Product 1',
        'description' => 'Description 1',
        'price' => 50.00
    ]);
    Product::create([
        'name' => 'Product 2',
        'description' => 'Description 2', 
        'price' => 100.00
    ]);

    $result = $this->productService->getAllProducts();

    $this->assertEquals(2, $result->count());           
}

    /** @test */
    public function it_handles_empty_category_ids_array()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'category_ids' => [] 
        ];

        $result = $this->productService->createProduct($productData);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertCount(0, $result->categories);
    }
}