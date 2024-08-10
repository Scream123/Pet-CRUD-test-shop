<?php

namespace App\Http\Requests\Product;

use App\Schema\CategorySchema;
use App\Schema\ProductSchema;
use App\Schema\TagSchema;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use ValidationErrorHandler;

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
                Rule::unique(ProductSchema::TABLE)->ignore((int) $this->route('product'))
            ],
            'description' => 'nullable|string',
            'category_id' => 'required|exists:' . CategorySchema::TABLE . ',' . CategorySchema::ID,
            'tags' => 'required|array',
            'tags.*' => 'integer|exists:' . TagSchema::TABLE . ',' . TagSchema::ID,
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
