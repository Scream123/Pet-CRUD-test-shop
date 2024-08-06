<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Repositories\TagRepositoryInterface;
use App\Services\CatalogService;

class TagController extends Controller
{
    protected $catalogService;
    protected $tagRepository;

    public function __construct(CatalogService $catalogService, TagRepositoryInterface $tagRepository)
    {
        $this->catalogService = $catalogService;
    }

    public function create()
    {
        return view('tags.create');
    }

    public function index()
    {
        $tags = $this->tagRepository->all();
        return view('tags.index', compact('tags'));
    }

    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();
        $tag = $this->catalogService->createTag($data);

        return response()->json(['message' => 'Тег успешно создан', 'tag' => $tag], 201);
    }

    public function update(UpdateTagRequest $request, $id)
    {
        $data = $request->validated();
        $tag = $this->catalogService->updateTag($id, $data);

        return response()->json(['message' => 'Тег успешно обновлен', 'tag' => $tag], 200);
    }

    public function delete($id)
    {
        $this->catalogService->deleteTag($id);

        return response()->json(['message' => 'Тег успешно удален'], 200);
    }
}
