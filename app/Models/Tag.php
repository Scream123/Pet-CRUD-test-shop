<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    use Slugable;

    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags');
    }
}
