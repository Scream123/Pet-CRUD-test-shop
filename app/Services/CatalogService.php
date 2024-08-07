<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new \Exception('Product not found.');
            }

            $product->update([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);

            $category = $this->categoryRepository->find($data['category_id']);
            $tags = $this->tagRepository->findMany($data['tags']);

            $product->categories()->detach();
            $product->tags()->detach();

            if ($category) {
                foreach ($tags as $tag) {
                    $product->categories()->attach($category->id, ['tag_id' => $tag->id]);
                }
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        $this->categoryRepository->delete($id);
    }

    public function createTag(array $data)
    {
        return $this->tagRepository->create($data);
    }

    public function updateTag($id, array $data)
    {
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag($id)
    {
        $this->tagRepository->delete($id);
    }
}
