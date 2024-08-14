<?php

declare(strict_types=1);

namespace App\Http\Requests\Product;

use App\Schema\CategorySchema;
use App\Schema\ProductSchema;
use App\Schema\TagSchema;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:' . ProductSchema::TABLE . ',' . ProductSchema::NAME,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:' . CategorySchema::TABLE . ',' . CategorySchema::ID,
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'integer|exists:' . TagSchema::TABLE . ',' . TagSchema::ID,
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Product with the name already exists.',
            'category_id.required' => 'category_id is required.',
            'tag_ids.*.exists' => 'Some tag_ids do not exist in the database.',
        ];
    }
}
