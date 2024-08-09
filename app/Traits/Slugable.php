<?php

namespace App\Traits;

use App\Helpers\SlugHelper;

trait Slugable
{
    public static function bootSlugable()
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
