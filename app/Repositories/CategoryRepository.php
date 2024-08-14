<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function find(string $id): ?Category
    {
        return $this->model->find($id);
    }
    public function all(): Collection
    {
        return $this->model->all();
    }
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): Category
    {
        $category = Category::find($id);

        if (!$category) {
            throw new ModelNotFoundException('Category not found');
        }

        $category->update($data);

        return $category;
    }

    public function delete(string $id): void
    {
        $category = $this->find($id);

        if (!$category) {
            throw new ModelNotFoundException('Category not found.');
        }

        $category->delete();
    }
    public function paginate(int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: config('pagination.per_page', 15); // Убедитесь, что по умолчанию используется 15 записей

        return $this->model->paginate($perPage);
    }
}
