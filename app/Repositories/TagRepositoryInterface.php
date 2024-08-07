<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    public function find($id): ?Tag;
    public function all();
    public function findMany(array $ids): Collection;
    public function create(array $data): Tag;
    public function update($id, array $data): Tag;
    public function delete($id): void;
    public function countSlugs($slug);
}
