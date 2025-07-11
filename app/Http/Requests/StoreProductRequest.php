<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'image' => 'nullable|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'description.required' => 'Product description is required',
            'price.required' => 'Product price is required',
            'price.min' => 'Product price must be greater than 0',
            'category_ids.array' => 'Categories must be an array',
            'category_ids.*.exists' => 'One or more selected categories do not exist'
        ];
    }
}