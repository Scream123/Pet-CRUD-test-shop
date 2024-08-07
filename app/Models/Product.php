<?php
namespace App\Models;

use App\Helpers\SlugHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slug'];

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

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category_tags', 'product_id', 'category_id')->withPivot('tag_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_category_tags', 'product_id', 'tag_id')->withPivot('category_id');
    }
}
