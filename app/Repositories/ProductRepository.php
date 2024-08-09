<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Product;

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
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        if (isset($data['category_ids']) && is_array($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
            $product->tags()->sync($data['tag_ids']);
        }

        return $product;
    }

    public function findByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function all()
    {
        return $this->model->with('categories', 'tags')->get();
    }

    public function update($id, array $data)
    {

        $product = $this->find($id);
        $product->update($data);

        return $product;
    }

    public function delete($id)
    {
        $product = $this->find($id);
        $product->delete();
    }

    public function paginate($perPage = null)
    {
        $perPage = $perPage ?: config('pagination.per_page');
        return $this->model->with('categories', 'tags')->paginate($perPage);
    }
}
