<?php

declare(strict_types=1);

namespace App\Models;

use App\Schema\ProductTagSchema;
use App\Schema\TagSchema;
use App\Traits\FormatsDates;
use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;
    use Slugable;
    use FormatsDates;

    protected $fillable = [
        TagSchema::NAME,
        TagSchema::SLUG,
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, ProductTagSchema::TABLE);
    }
}
