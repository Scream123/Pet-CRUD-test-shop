<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Str;

class SlugHelper
{
    public static function generateSlug(string $modelClass, string $name): string
    {
        $slug = Str::slug($name);

        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Class {$modelClass} does not exist.");
        }

        $count = $modelClass::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
