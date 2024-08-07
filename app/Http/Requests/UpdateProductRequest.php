<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->route('product'))
            ],
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'required|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Product with the name already exists.',
            'category_id.required' => 'Category ID is required.',
            'tags.required' => 'Tags are required.',
            'tags.*.exists' => 'Some tags do not exist in the database.',
        ];
    }
}
