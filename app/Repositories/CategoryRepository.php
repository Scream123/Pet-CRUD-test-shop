<?php

namespace App\Repositories;

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
        if (isset($data['name'])) {
            $data['slug'] = $this->model->generateSlug($data['name']);
        }
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
    public function countSlugs($slug)
    {
        return $this->model->where('slug', 'LIKE', "{$slug}%")->count();
    }
}
