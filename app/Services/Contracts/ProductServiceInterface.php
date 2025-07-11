<?php

namespace App\Services\Contracts;

use App\Models\Product;
use \Illuminate\Database\Eloquent\Collection;
interface ProductServiceInterface
{
    public function createProduct(array $data): Product;
    public function getAllProducts(array $filters = []): Collection;
}