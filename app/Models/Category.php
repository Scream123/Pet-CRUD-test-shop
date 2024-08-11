<?php

declare(strict_types=1);

namespace App\Models;

use App\Schema\CategorySchema;
use App\Schema\ProductCategorySchema;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    use Slugable;

    protected $fillable = [
        CategorySchema::NAME,
        CategorySchema::SLUG,
        CategorySchema::PARENT_ID,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, CategorySchema::PARENT_ID);
    }

    // Получение дочерних категорий
    public function children(): HasMany
    {
        return $this->hasMany(
            Category::class,
            CategorySchema::PARENT_ID
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            ProductCategorySchema::TABLE,
            ProductCategorySchema::CATEGORY_ID,
            ProductCategorySchema::PRODUCT_ID
        );
    }
}
