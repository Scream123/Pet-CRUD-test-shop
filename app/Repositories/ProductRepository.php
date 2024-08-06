<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategoryTag;
use App\Models\Tag;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Product
    {
        $product = $this->model->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        if (isset($data['category_id'])) {
            $category = Category::findOrFail($data['category_id']);
            $product->categories()->attach($category);
        }

        // Присвоение тегов
        if (isset($data['tags']) && is_array($data['tags'])) {
            $tags = Tag::find($data['tags']);
            $product->tags()->sync($tags);
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
    // Если у вас есть методы, которые нужно реализовать, добавьте их здесь
    // Например:
    // public function attachCategoryAndTags($productId, $categoryId, array $tagIds)
    // {
    //     $data = [];
    //     if ($categoryId) {
    //         $data[] = [
    //             'product_id' => $productId,
    //             'category_id' => $categoryId,
    //             'tag_id' => null,
    //         ];
    //     }

    //     foreach ($tagIds as $tagId) {
    //         $data[] = [
    //             'product_id' => $productId,
    //             'category_id' => null,
    //             'tag_id' => $tagId,
    //         ];
    //     }

    //     ProductCategoryTag::insert($data);
    // }
}
