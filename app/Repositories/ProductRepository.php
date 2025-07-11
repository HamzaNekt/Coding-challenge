<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::with('categories')->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function filter(array $filters): Collection
    {
        $query = Product::with('categories');

        if (isset($filters['category_id'])) {
            $query->byCategory($filters['category_id']);
        }

        if (isset($filters['sort_by_price'])) {
            $direction = $filters['sort_by_price'] === 'desc' ? 'desc' : 'asc';
            $query->orderBy('price', $direction);
        }

        return $query->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::with('categories')->find($id);
    }
}