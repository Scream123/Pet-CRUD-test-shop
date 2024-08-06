<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    protected $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    public function find($id): ?Tag
    {
        return $this->model->find($id);
    }
    public function all()
    {
        return $this->model->all();
    }
    public function findMultiple(array $ids): array
    {
        return $this->model->whereIn('id', $ids)->get()->all();
    }

    public function create(array $data): Tag
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Tag
    {
        $tag = $this->find($id);
        $tag->update($data);
        return $tag;
    }

    public function delete($id): void
    {
        $tag = $this->find($id);
        $tag->delete();
    }
    public function countSlugs($slug)
    {
        return $this->model->where('slug', 'LIKE', "{$slug}%")->count();
    }
}
