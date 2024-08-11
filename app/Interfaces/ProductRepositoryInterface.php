<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function create(array $data): Product;

    public function find(string $id): ?Product;

    public function all(): Collection;

    public function update(string $id, array $data): Product;

    public function delete(string $id): void;

    public function paginate(int $perPage): LengthAwarePaginator;
}
