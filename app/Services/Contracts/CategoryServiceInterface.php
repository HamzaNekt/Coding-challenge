<?php

namespace App\Services\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function createCategory(array $data): Category;
    public function getAllCategories(): Collection;
}