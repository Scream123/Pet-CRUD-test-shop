<?php

namespace App\Http\Requests\Tag;

use App\Schema\TagSchema;
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
            'name' => 'required|string|max:255|unique:' . TagSchema::TABLE,
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Tag with the name already exists.',];
    }
}
