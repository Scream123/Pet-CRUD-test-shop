<?php

namespace App\Http\Requests\Tag;

use App\Traits\PreparesForValidation;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    use ValidationErrorHandler, PreparesForValidation;

    private const PARAM_NAME = 'tag';
    private const MERGE_KEY = 'id';

    use ValidationErrorHandler;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::MERGE_KEY => 'required|integer|exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            self::MERGE_KEY . '.required' => 'The ID parameter is required.',
            self::MERGE_KEY . '.integer' => 'The ID must be an integer.',
            self::MERGE_KEY . '.exists' => 'The specified tag does not exist.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->setParamName(self::PARAM_NAME);
        $this->setMergeKey(self::MERGE_KEY);

        $this->applyPreparation();
    }

}
