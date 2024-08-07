<?php

namespace App\Repositories;

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
        if (!isset($data['category_id']) || !isset($data['tags'])) {
            throw new \InvalidArgumentException('Category ID and Tags are required.');
        }

        $product = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        if (isset($data['tags']) && is_array($data['tags'])) {
            foreach ($data['tags'] as $tagId) {
                $product->categories()->attach($data['category_id'], ['tag_id' => $tagId]);
            }
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

    public function countSlugs($slug)
    {
        return $this->model->where('slug', 'LIKE', "{$slug}%")->count();
    }
}
