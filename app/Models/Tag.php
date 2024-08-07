<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = SlugHelper::generateSlug(self::class, $model->name);
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = SlugHelper::generateSlug(self::class, $model->name);
            }
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_tags', 'tag_id', 'product_id');
    }
}
