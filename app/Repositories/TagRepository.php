<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryInterface
{
    protected $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    public function find(string $id): ?Tag
    {
        return $this->model->find($id);
    }
    public function all(): Collection
    {
        return $this->model->all();
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

    public function delete(string $id): void
    {
        $tag = Tag::find($id);

        if (!$tag) {
            throw new ModelNotFoundException('Tag not found.');
        }

        $tag->delete();
    }

    public function paginate(int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: config('pagination.per_page');

        return $this->model->paginate($perPage);
    }
}
