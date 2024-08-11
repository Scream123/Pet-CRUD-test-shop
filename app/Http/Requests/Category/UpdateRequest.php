<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use App\Schema\CategorySchema;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique(CategorySchema::TABLE)->ignore($this->route('category')),
                ],
                'parent_id' => 'nullable|integer|exists:' . CategorySchema::TABLE . ',' . CategorySchema::ID,

            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Category with the name already exists.',
            'parent_id.exists' => 'Category not exists.',
        ];
    }
}
