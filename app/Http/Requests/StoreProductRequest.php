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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'image.image' => 'The file must be an image',
            'image.mimes' => 'Image must be jpeg, png, jpg or gif',
            'image.max' => 'Image must not exceed 2MB',
            'category_ids.array' => 'Categories must be an array',
            'category_ids.*.exists' => 'One or more selected categories do not exist'
        ];
    }
}