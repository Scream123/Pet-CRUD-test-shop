<?php

namespace App\Models;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
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
                $model->slug = $model->generateSlug($model->name);
            }
        });
    }

    public function generateSlug($name)
    {
        $slug = Str::slug($name);

        $count = Category::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_tags', 'category_id', 'product_id');
    }
}
