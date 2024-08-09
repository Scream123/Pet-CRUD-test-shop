<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function find($id): ?Category
    {
        return $this->model->find($id);
    }
    public function all()
    {
        return $this->model->all();
    }
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Category
    {
        $category = $this->find($id);
        $category->update($data);
        return $category;
    }

    public function delete($id): void
    {
        $category = $this->find($id);
        $category->delete();
    }
    public function paginate($perPage = 15)
    {
        return Category::with('parent')
        ->paginate($perPage);
    }
}
