<?php

declare(strict_types=1);

namespace App\Http\Requests\Category;

use App\Traits\PreparesForValidation;
use App\Traits\ValidationErrorHandler;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    use ValidationErrorHandler, PreparesForValidation;

    private const PARAM_NAME = 'category';
    private const MERGE_KEY = 'id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::MERGE_KEY => 'required|integer|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            self::MERGE_KEY . '.required' => 'The ID parameter is required.',
            self::MERGE_KEY . '.integer' => 'The ID must be an integer.',
            self::MERGE_KEY . '.exists' => 'The specified category does not exist.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->setParamName(self::PARAM_NAME);
        $this->setMergeKey(self::MERGE_KEY);

        $this->applyPreparation();
    }
}
