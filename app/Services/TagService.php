<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\TagRepositoryInterface;
use App\Models\Tag;

class TagService
{
    protected TagRepositoryInterface $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function find(string $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    public function create(array $data): Tag
    {
        return $this->tagRepository->create($data);
    }

    public function update(string $id, array $data): Tag
    {
        return $this->tagRepository->update($id, $data);
    }

    public function delete(string $id): void
    {
        $this->tagRepository->delete($id);
    }
}
