<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:products,name',
            'slug' => 'required|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'img_thumbnail' => 'required',
            'variants' => 'nullable'
        ];
    }
    protected $stopOnFirstFailure = true;
}
