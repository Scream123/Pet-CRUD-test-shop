<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Product;
use App\Schema\CategorySchema;
use App\Schema\ProductSchema;
use App\Schema\TagSchema;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(
        Product                     $model,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface      $tagRepository
    )
    {
        $this->model = $model;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function create(array $data): Product
    {
        $product = $this->model->create([
            ProductSchema::NAME => $data['name'],
            ProductSchema::DESCRIPTION => $data['description'],
        ]);

        if (isset($data['category_ids']) && is_array($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
            $product->tags()->sync($data['tag_ids']);
        }

        return $product;
    }

    public function find(string $id): ?Product
    {
        return $this->model->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->model->with(CategorySchema::TABLE, TagSchema::TABLE)->get();
    }

    public function update(string $id, array $data): Product
    {

        $product = $this->find($id);
        $product->update($data);

        return $product;
    }

    public function delete(string $id): void
    {
        $product = $this->find($id);
        $product->delete();
    }

    public function paginate(int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?: config('pagination.per_page');
        return $this->model->with(CategorySchema::TABLE, TagSchema::TABLE)->paginate($perPage);
    }
}
