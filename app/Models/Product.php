<?php

namespace App\Models;

use App\Schema\ProductCategorySchema;
use App\Schema\ProductSchema;
use App\Schema\ProductTagSchema;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    use Slugable;

    protected $fillable = [
        ProductSchema::NAME,
        ProductSchema::DESCRIPTION,
        ProductSchema::SLUG,

    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, ProductCategorySchema::TABLE, ProductCategorySchema::PRODUCT_ID, ProductCategorySchema::CATEGORY_ID);

    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, ProductTagSchema::TABLE, ProductTagSchema::PRODUCT_ID, ProductTagSchema::TAG_ID);
    }
}
