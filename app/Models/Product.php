<?php

declare(strict_types=1);

namespace App\Models;

use App\Schema\ProductCategorySchema;
use App\Schema\ProductSchema;
use App\Schema\ProductTagSchema;
use App\Traits\FormatsDates;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use Slugable;
    use FormatsDates;

    protected $fillable = [
        ProductSchema::NAME,
        ProductSchema::DESCRIPTION,
        ProductSchema::SLUG,

    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            ProductCategorySchema::TABLE,
            ProductCategorySchema::PRODUCT_ID,
            ProductCategorySchema::CATEGORY_ID
        );

    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            ProductTagSchema::TABLE,
            ProductTagSchema::PRODUCT_ID,
            ProductTagSchema::TAG_ID
        );
    }
}
