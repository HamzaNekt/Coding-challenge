<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
   
    public function all(): Collection;

   
    public function create(array $data): Product;

  
    public function filter(array $filters): Collection;

   
    public function findById(int $id): ?Product;
}