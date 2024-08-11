<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryInterface
{
    public function find(string $id): ?Category;
    public function all(): Collection;

    public function create(array $data): Category;
    public function update(string $id, array $data): Category;
    public function delete(string $id): void;
    public function paginate(int $perPage): LengthAwarePaginator;
}
