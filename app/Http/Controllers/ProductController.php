<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $catalogService;
    protected $productRepository;
    protected $tagRepository;
    protected $categoriesRepository;

    public function __construct(
        CatalogService              $catalogService,
        ProductRepositoryInterface  $productRepository,
        TagRepositoryInterface      $tagRepository,
        CategoryRepositoryInterface $categoriesRepository,
    )
    {
        $this->catalogService = $catalogService;
        $this->productRepository = $productRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index()
    {
        $products = $this->productRepository->all()->load('categories', 'tags')->map(function ($product) {
            $product->category_name = $product->categories->pluck('name')->first();
            $product->tag_names = $product->tags->pluck('name')->implode(', ');

            return $product;
        });

        return view('products.index', compact('products'));
    }


    public function create()
    {
        $categories = $this->productRepository->all();
        $tags = $this->tagRepository->all();
        return view('products.create', compact('categories', 'tags'));
    }


    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        try {
            $product = $this->catalogService->createProduct($data);
            return response()->json([
                'message' => 'Product added successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating product'], 500);
        }
    }

    public function show($id)
    {
        $product = $this->productRepository->find($id)->load('categories', 'tags');

        if (!$product) {
            abort(404, 'Product not found.');
        }

        $product->category_name = $product->categories->pluck('name')->first();
        $product->tag_names = $product->tags->pluck('name')->implode(', ');

        return view('products.show', compact('product'));
    }


    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoriesRepository->all();
        $tags = $this->tagRepository->all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $data = $request->validated();
        try {
            $product = $this->catalogService->updateProduct($id, $data);
            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error update product: ' . $e->getMessage());
            return response()->json(['error' => 'Error update product'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->catalogService->deleteProduct($id);
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Ошибка при удалении продукта: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', 'Ошибка при удалении продукта.');
        }
    }
}
