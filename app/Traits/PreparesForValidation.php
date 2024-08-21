<?php

namespace App\Traits;

trait PreparesForValidation
{
    protected ?string $paramName = null;
    protected string $mergeKey = 'id';

    public function setParamName(string $paramName): void
    {
        $this->paramName = $paramName;
    }

    public function setMergeKey(string $mergeKey): void
    {
        $this->mergeKey = $mergeKey;
    }

    protected function applyPreparation(): void
    {
        if ($this->paramName && $this->route($this->paramName)) {
            $this->merge([
                $this->mergeKey => $this->route($this->paramName),
            ]);
        }
    }
}
