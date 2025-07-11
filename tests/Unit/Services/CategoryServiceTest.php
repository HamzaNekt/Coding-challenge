<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;
    private $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryRepository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->categoryService = new CategoryService($this->categoryRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_create_category_without_parent()
    {
        
        $categoryData = [
            'name' => 'Electronics'
        ];

        $expectedCategory = new Category($categoryData);
        $expectedCategory->id = 1;

        $this->categoryRepository
            ->shouldReceive('create')
            ->once()
            ->with($categoryData)
            ->andReturn($expectedCategory);

        
        $result = $this->categoryService->createCategory($categoryData);

        
        $this->assertEquals($expectedCategory, $result);
    }

    /** @test */
    public function it_can_create_category_with_valid_parent()
    {
        
        $parentCategory = new Category(['id' => 1, 'name' => 'Electronics']);
        
        $categoryData = [
            'name' => 'Smartphones',
            'parent_id' => 1
        ];

        $expectedCategory = new Category($categoryData);
        $expectedCategory->id = 2;

        $this->categoryRepository
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($parentCategory);

        $this->categoryRepository
            ->shouldReceive('create')
            ->once()
            ->with($categoryData)
            ->andReturn($expectedCategory);

        
        $result = $this->categoryService->createCategory($categoryData);

        
        $this->assertEquals($expectedCategory, $result);
    }

    /** @test */
    public function it_throws_exception_when_parent_category_does_not_exist()
    {
       
        $categoryData = [
            'name' => 'Smartphones',
            'parent_id' => 999 
        ];

        $this->categoryRepository
            ->shouldReceive('findById')
            ->once()
            ->with(999)
            ->andReturn(null);

        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Parent category with ID 999 does not exist.');

        $this->categoryService->createCategory($categoryData);
    }

    /** @test */
    public function it_can_get_all_categories()
    {
        
        $categories = new Collection([
            new Category(['id' => 1, 'name' => 'Electronics']),
            new Category(['id' => 2, 'name' => 'Books'])
        ]);

        $this->categoryRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($categories);

        
        $result = $this->categoryService->getAllCategories();

       
        $this->assertEquals($categories, $result);
    }

    /** @test */
    public function it_skips_parent_validation_when_parent_id_is_null()
    {
       
        $categoryData = [
            'name' => 'Electronics',
            'parent_id' => null
        ];

        $expectedCategory = new Category($categoryData);
        $expectedCategory->id = 1;

        $this->categoryRepository
            ->shouldReceive('create')
            ->once()
            ->with($categoryData)
            ->andReturn($expectedCategory);

        
        $result = $this->categoryService->createCategory($categoryData);

      
        $this->assertEquals($expectedCategory, $result);
    }
}