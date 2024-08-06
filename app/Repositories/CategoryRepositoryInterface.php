<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function find($id): ?Category;
    public function all();

    public function create(array $data): Category;
    public function update($id, array $data): Category;
    public function delete($id): void;
    public function countSlugs($slug);
}
