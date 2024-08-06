<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepositoryInterface;
use App\Services\CatalogService;

class CategoryController extends Controller
{
    protected $catalogService;
    protected $categoryRepository;

    public function __construct(CatalogService $catalogService, CategoryRepositoryInterface $categoryRepository)
    {
        $this->catalogService = $catalogService;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->catalogService->createCategory($data);

        return response()->json(['message' => 'Категория успешно создана', 'category' => $category], 201);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->validated();
        $category = $this->catalogService->updateCategory($id, $data);

        return response()->json(['message' => 'Категория успешно обновлена', 'category' => $category], 200);
    }

    public function delete($id)
    {
        $this->catalogService->deleteCategory($id);

        return response()->json(['message' => 'Категория успешно удалена'], 200);
    }
}
