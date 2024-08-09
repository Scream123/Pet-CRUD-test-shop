<?php

namespace App\Http\Requests\Tag;

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
            'name' => 'required|string|max:255|unique:tags',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Tag with the name already exists.',];
    }
}
