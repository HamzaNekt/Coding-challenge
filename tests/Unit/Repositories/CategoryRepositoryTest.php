<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CategoryRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository(new Category());
    }

    /** @test */
    public function test_can_get_all_categories()
    {
       
        Category::create(['name' => 'Electronics']);
        Category::create(['name' => 'Clothing']);

        $categories = $this->repository->all();

        $this->assertCount(2, $categories);
        $this->assertEquals('Electronics', $categories->first()->name);
    }

    /** @test */
    public function test_can_create_category()
    {
        $categoryData = ['name' => 'Books'];

        $category = $this->repository->create($categoryData);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Books', $category->name);
        $this->assertDatabaseHas('categories', $categoryData);
    }

    /** @test */
    public function test_can_find_category_by_id()
    {
        $createdCategory = Category::create(['name' => 'Sports']);

        $foundCategory = $this->repository->findById($createdCategory->id);

        $this->assertInstanceOf(Category::class, $foundCategory);
        $this->assertEquals('Sports', $foundCategory->name);
        $this->assertEquals($createdCategory->id, $foundCategory->id);
    }

    /** @test */
    public function test_find_non_existent_category_returns_null()
    {
        $foundCategory = $this->repository->findById(999);

        $this->assertNull($foundCategory);
    }
}