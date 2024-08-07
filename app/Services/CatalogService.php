<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Exception;
use Illuminate\Support\Str;


class CatalogService
{
    protected $productRepository;
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(
        ProductRepositoryInterface  $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface      $tagRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function createProduct(array $data): Product
    {
        $this->validateProductData($data);

        $product = $this->productRepository->create($data);

        $category = $this->categoryRepository->find($data['category_id']);
        $tags = $this->tagRepository->findMany($data['tags']);

        if ($category && $tags->isNotEmpty()) {
            foreach ($tags as $tag) {
                $product->categories()->attach($category->id, ['tag_id' => $tag->id]);
            }
        }

        return $product;
    }


    public function updateProduct($id, array $data): Product
    {
        $this->validateProductData($data);
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new \Exception('Product not found.');
        }

        $product->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->find($data['category_id']);
            if ($category) {
                $product->categories()->detach();
                $product->categories()->attach($category->id);
            }
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $tags = $this->tagRepository->findMany($data['tags']);
            if ($tags->isNotEmpty()) {
                $product->tags()->detach();
                foreach ($tags as $tag) {
                    $product->categories()->attach($data['category_id'], ['tag_id' => $tag->id]);
                }
            }
        }

        return $product;
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    protected function validateProductData(array $data)
    {
        if (Product::where('name', $data['name'])->exists()) {
            throw new \Exception('Product with the name already exists.');
        }
        if (!isset($data['category_id']) || !isset($data['tags'])) {
            throw new \InvalidArgumentException('Category ID and Tags are required.');
        }
    }

    protected function generateSlug($name): string
    {
        $slug = Str::slug($name);
        $count = $this->productRepository->countSlugs($slug);
        return $count ? "{$slug}-{$count}" : $slug;
    }


    public function createCategory(array $data)
    {
        $data['slug'] = $this->generateSlug($data['name']);
        return $this->categoryRepository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        $this->categoryRepository->delete($id);
    }

    public function createTag(array $data)
    {
        $data['slug'] = $this->generateSlug($data['name']);
        return $this->tagRepository->create($data);
    }

    public function updateTag($id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag($id)
    {
        $this->tagRepository->delete($id);
    }
}
