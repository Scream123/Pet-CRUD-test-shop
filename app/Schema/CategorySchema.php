<?php

declare(strict_types=1);

namespace App\Schema;

class CategorySchema
{
    const TABLE = 'categories';

    const ID = 'id';
    const NAME = 'name';
    const SLUG = 'slug';
    const PARENT_ID = 'parent_id';
}
