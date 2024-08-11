<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    public function find(string $id): ?Tag;

    public function all(): Collection;

    public function create(array $data): Tag;

    public function update(string $id, array $data): Tag;

    public function delete(string $id): void;

    public function paginate(int $perPage): LengthAwarePaginator;
}
