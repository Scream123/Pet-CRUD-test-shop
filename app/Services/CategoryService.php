<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function find(string $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function update(string $id, array $data): Category
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->categoryRepository->delete($id);
    }
}
