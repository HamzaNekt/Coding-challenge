<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\CategoryService;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\ProductService;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {
            return new ProductRepository(new Product());
        });

        $this->app->bind(CategoryRepositoryInterface::class, function ($app) {
            return new CategoryRepository(new Category());
        });

        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}