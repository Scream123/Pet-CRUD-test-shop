<?php

declare(strict_types=1);

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $categoryRepository;

    public function __construct(
        CategoryService             $categoryService,
        CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }


    public function index(): CategoryCollection
    {
        $categories = $this->categoryRepository->paginate();

        return new CategoryCollection($categories);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->create($data);

        return response()->json(['message' => 'Category successfully created', 'category' => $category], 201);
    }

    public function show(string $id): JsonResponse|CategoryResource
    {
        $category = $this->categoryService->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return new CategoryResource($category);
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->update($id, $data);

        return response()->json(['message' => 'Category successfully updated', 'category' => $category], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->categoryService->delete($id);

            return response()->json(['message' => 'Category deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing category.'], 500);
        }
    }
}
