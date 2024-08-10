<?php

namespace App\Models;

use App\Schema\CategorySchema;
use App\Schema\ProductCategorySchema;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use Slugable;

    protected $fillable = [
        CategorySchema::NAME,
        CategorySchema::SLUG,
        CategorySchema::PARENT_ID,
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, CategorySchema::PARENT_ID);
    }

    // Получение дочерних категорий
    public function children()
    {
        return $this->hasMany(Category::class, CategorySchema::PARENT_ID);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, ProductCategorySchema::TABLE, ProductCategorySchema::CATEGORY_ID, ProductCategorySchema::PRODUCT_ID);
    }
}
