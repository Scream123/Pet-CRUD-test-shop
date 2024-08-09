<?php

namespace App\Http\Requests\Category;

use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function rules()
    {
        {
            return [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->ignore($this->route('category')),
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'name.unique' => 'Category with the name already exists.',
        ];
    }
}
