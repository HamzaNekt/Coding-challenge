<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_category_can_be_created()
    {
        $category = Category::create(['name' => 'Electronics']);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Electronics', $category->name);
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }

  
    public function test_category_hierarchy()
    {
        $parent = Category::create(['name' => 'Electronics']);
        $child = Category::create(['name' => 'Smartphones', 'parent_id' => $parent->id]);

        $this->assertTrue($child->hasParent());
        $this->assertFalse($parent->hasParent());
        $this->assertTrue($parent->hasChildren());
        $this->assertFalse($child->hasChildren());

        $this->assertEquals('Electronics', $child->parent->name);
        $this->assertEquals('Smartphones', $parent->children->first()->name);
    }

    
    public function test_root_categories_scope()
    {
        $root1 = Category::create(['name' => 'Electronics']);
        $root2 = Category::create(['name' => 'Clothing']);
        $child = Category::create(['name' => 'Smartphones', 'parent_id' => $root1->id]);

        $rootCategories = Category::root()->get();

        $this->assertEquals(2, $rootCategories->count());
        $this->assertTrue($rootCategories->contains('name', 'Electronics'));
        $this->assertTrue($rootCategories->contains('name', 'Clothing'));
        $this->assertFalse($rootCategories->contains('name', 'Smartphones'));
    }
}