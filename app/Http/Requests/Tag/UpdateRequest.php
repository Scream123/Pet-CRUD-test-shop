<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use App\Schema\TagSchema;
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
                    Rule::unique(TagSchema::TABLE)->ignore($this->route('tag')),
                ],
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Tag with the name already exists.',
        ];
    }
}
