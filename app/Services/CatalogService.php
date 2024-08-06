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

        $data['slug'] = $this->generateSlug($data['name']);

        $product = $this->productRepository->create($data);
        $category = $this->categoryRepository->find($data['category_id']);
        $tags = $this->tagRepository->find($data['tags']);

        $product->categories()->attach($category);
        $product->tags()->sync($tags);

        return $product;
    }

    public function updateProduct($id, array $data): Product
    {
        $this->validateProductData($data);

        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $product = $this->productRepository->update($id, $data);
        if (isset($data['category_id'])) {
            $category = $this->categoryRepository->find($data['category_id']);
            $product->categories()->sync($category);
        }
        if (isset($data['tags'])) {
            $tags = $this->tagRepository->find($data['tags']);
            $product->tags()->sync($tags);
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
            throw new Exception('Продукт с таким названием уже существует.');
        }
        if (!Category::find($data['category_id'])) {
            throw new Exception('Категория не существует.');
        }
        if (!Tag::find($data['tags'])) {
            throw new Exception('Один или несколько тегов не существует.');
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
