<?php

namespace App\Http\Requests\Category;

use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Category with the name already exists.',];
    }
}
