<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Schema\ProductSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function find(string $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function create(array $data): Product
    {
        $product = $this->productRepository->create($data);

        if (isset($data['category_id'])) {
            $product->categories()->attach($data['category_id']);
        }

        if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
            $product->tags()->sync($data['tag_ids']);
        }

        return $product;
    }

    public function update(string $id, array $data): Product
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                throw new ModelNotFoundException('Product not found.');
            }

            $product->update([
                ProductSchema::NAME => $data['name'],
                ProductSchema::DESCRIPTION => $data['description'],
            ]);

            $product->categories()->detach();
            $product->tags()->detach();

            if (isset($data['category_id'])) {
                $product->categories()->attach($data['category_id']);
            }
            if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
                $product->tags()->sync($data['tag_ids']);
            }
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(string $id): void
    {
        $this->productRepository->delete($id);
    }
}
