<?php

namespace App\Repositories;

use App\Models\Tag;

interface TagRepositoryInterface
{
    public function find($id): ?Tag;
    public function all();
    public function findMultiple(array $ids): array;
    public function create(array $data): Tag;
    public function update($id, array $data): Tag;
    public function delete($id): void;
    public function countSlugs($slug);
}
