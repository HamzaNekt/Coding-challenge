<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    
    private Category $model;

   
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

   
    public function all(): Collection
    {
        return $this->model->all();
    }

  
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    
    public function findById(int $id): ?Category
    {
        return $this->model->find($id);
    }
}