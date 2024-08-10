<?php

namespace App\Models;

use App\Schema\ProductTagSchema;
use App\Schema\TagSchema;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use Slugable;

    protected $fillable = [
        TagSchema::NAME,
        TagSchema::SLUG,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, ProductTagSchema::TABLE);
    }
}
