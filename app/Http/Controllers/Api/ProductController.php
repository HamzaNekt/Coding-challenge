<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

   
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category_id', 'sort_by_price']);
        
        $products = $this->productService->getAllProducts($filters);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ]);
    }

    
     public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $fileName, 'public');
                $data['image'] = $imagePath;
            }

            $product = $this->productService->createProduct($data);

            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product created successfully'
            ], Response::HTTP_CREATED);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the product'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}