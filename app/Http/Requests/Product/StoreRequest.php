<?php

namespace App\Http\Requests\Product;

use App\Schema\CategorySchema;
use App\Schema\ProductSchema;
use App\Schema\TagSchema;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use ValidationErrorHandler;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:' . ProductSchema::TABLE . ',' . ProductSchema::NAME,

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
