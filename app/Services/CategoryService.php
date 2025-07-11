<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(array $data): Category
    {

        if (isset($data['parent_id']) && $data['parent_id']) {
            $parent = $this->categoryRepository->findById($data['parent_id']);
            if (!$parent) {
                throw new InvalidArgumentException("Parent category with ID {$data['parent_id']} does not exist.");
            }
        }

        return $this->categoryRepository->create($data);
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }
}