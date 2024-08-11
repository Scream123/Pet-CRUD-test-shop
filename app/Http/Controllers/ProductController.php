<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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

    public function index(): View
    {
        $products = $this->productRepository->paginate();

        $products->getCollection()->transform(function ($product) {
            $product->category_name = $product->categories->pluck('name')->first();
            $product->tag_names = $product->tags->pluck('name')->implode(', ');

            return $product;
        });

        return view('products.index', compact('products'));
    }


    public function create(): View
    {
        $categories = $this->categoriesRepository->all();
        $tags = $this->tagRepository->all();

        return view('products.create', compact('categories', 'tags'));
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

    public function show(string $id): View
    {
        $product = $this->productRepository->find($id)->load('categories', 'tags');

        if (!$product) {
            abort(404, 'Product not found.');
        }

        $product->category_name = $product->categories->pluck('name')->first();
        $product->tag_names = $product->tags->pluck('name')->implode(', ');

        return view('products.show', compact('product'));
    }


    public function edit(string $id): View
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoriesRepository->all();
        $tags = $this->tagRepository->all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        try {
            $product = $this->productService->update($id, $data);
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error update product: ' . $e->getMessage());
            return response()->json(['error' => 'Error update product'], 500);
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->productService->delete($id);
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error removing product.: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Error removing product..');
        }
    }
}
