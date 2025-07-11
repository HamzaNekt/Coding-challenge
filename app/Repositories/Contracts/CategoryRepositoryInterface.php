<?php

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    
    public function all(): Collection;

   
    public function create(array $data): Category;

    
    public function findById(int $id): ?Category;
}