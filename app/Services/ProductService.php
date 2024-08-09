<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create(array $data): Product
    {
        $product = $this->productRepository->create($data);

        if ($data['category_id']) {
            $product->categories()->attach($data['category_id']);
        }

        if (!empty($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return $product;
    }

    public function update($id, array $data): Product
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

            $product->categories()->detach();
            $product->tags()->detach();

            if ($data['category_id']) {
                $product->categories()->attach($data['category_id']);
            }
            if (!empty($data['tags'])) {
                $product->tags()->sync($data['tags']);
            }
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        return $this->productRepository->delete($id);
    }
}
