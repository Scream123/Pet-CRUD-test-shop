<?php

namespace App\Http\Requests\Tag;

use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    use ValidationErrorHandler;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
