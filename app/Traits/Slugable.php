<?php

declare(strict_types=1);

namespace App\Traits;

use App\Helpers\SlugHelper;

trait Slugable
{
    public static function bootSlugable(): void
    {
        static::creating(function ($model) {
            $model->slug = SlugHelper::generateSlug(self::class, $model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = SlugHelper::generateSlug(self::class, $model->name);
            }
        });
    }
}
