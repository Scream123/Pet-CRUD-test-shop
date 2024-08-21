<?php

declare(strict_types=1);

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\DestroyRequest;
use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\ShowRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $tagRepository;
    protected $categoriesRepository;

    public function __construct(
        ProductService              $productService,
        ProductRepositoryInterface  $productRepository,
        TagRepositoryInterface      $tagRepository,
        CategoryRepositoryInterface $categoriesRepository,
    )
    {
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(IndexRequest $request): ProductCollection|JsonResponse
    {
        try {
            $products = $this->productRepository->paginate();
            return new ProductCollection($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to fetch products'], 500);
        }
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $product = $this->productService->create($data);
            return response()->json([
                'message' => 'Product added successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating product'], 500);
        }
    }

    public function show(ShowRequest $request): JsonResponse|ProductResource
    {
        $validated = $request->validated();
        $product = $this->productService->find($validated['id']);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->load('categories', 'tags');

        return new ProductResource($product);
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        try {
            $product = $this->productService->update($id, $data);
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating product'], 500);
        }
    }

    public function destroy(DestroyRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $this->productService->delete($validated['id']);

            return response()->json(['message' => 'Product deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing product.'], 500);
        }
    }
}
