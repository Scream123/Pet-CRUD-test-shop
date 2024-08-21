<?php

declare(strict_types=1);

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\DestroyRequest;
use App\Http\Requests\Category\IndexRequest;
use App\Http\Requests\Category\ShowRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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


    public function index(IndexRequest $request): CategoryCollection
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

    public function show(ShowRequest $request): JsonResponse|CategoryResource
    {
        $validated = $request->validated();
        $category = $this->categoryService->find($validated['id']);

        return new CategoryResource($category);
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $category = $this->categoryService->update($id, $request->validated());

            return response()->json($category, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(DestroyRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $this->categoryService->delete($validated['id']);

            return response()->json(['message' => 'Category deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing category.'], 500);
        }
    }
}
