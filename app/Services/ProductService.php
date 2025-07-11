<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductService implements ProductServiceInterface
{
    private ProductRepositoryInterface $productRepository;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function createProduct(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $categoryIds = $data['category_ids'] ?? [];
            unset($data['category_ids']);


            $product = $this->productRepository->create($data);


            if (!empty($categoryIds)) {
                $this->validateAndAttachCategories($product, $categoryIds);
            }

            return $product->load('categories');
        });
    }

    public function getAllProducts(array $filters = []): Collection
    {
        return $this->productRepository->filter($filters);
    }

    private function validateAndAttachCategories(Product $product, array $categoryIds): void
    {
        foreach ($categoryIds as $categoryId) {
            if (!$this->categoryRepository->findById($categoryId)) {
                throw new InvalidArgumentException("Category with ID {$categoryId} does not exist.");
            }
        }
        
        $product->categories()->attach($categoryIds);
    }
}