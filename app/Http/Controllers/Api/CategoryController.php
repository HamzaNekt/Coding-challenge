<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryServiceInterface $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAllCategories();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'message' => 'Categories retrieved successfully'
        ]);
    }
}