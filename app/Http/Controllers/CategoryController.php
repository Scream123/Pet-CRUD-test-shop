<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $categoryRepository;

    public function __construct(CategoryService $categoryService, CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): View
    {
        $categories = $this->categoryRepository->all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->create($data);

        return response()->json(['message' => 'Category successfully created', 'category' => $category], 201);
    }

    public function show(string $id): View
    {
        $category = $this->categoryService->find($id);

        if (!$category) {
            abort(404, 'Category not found.');
        }

        return view('categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);
        return view('categories.edit', compact('category'));
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $category = $this->categoryService->update($id, $data);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->categoryService->delete($id);

            return response()->json(['message' => 'Category deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing category.'], 500);
        }
    }
}
