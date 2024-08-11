<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Interfaces\TagRepositoryInterface;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    protected $tagService;
    protected $tagRepository;

    public function __construct(TagService $tagService, TagRepositoryInterface $tagRepository)
    {
        $this->tagService = $tagService;
        $this->tagRepository = $tagRepository;

    }
    public function index(): View
    {
        $tags = $this->tagRepository->all();
        return view('tags.index', compact('tags'));
    }
    public function create(): View
    {
        return view('tags.create');
    }



    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $tag = $this->tagService->create($data);

        return response()->json(['message' => 'Tag created successfully', 'tag' => $tag], 201);
    }

    public function show(string $id): View
    {
        $tag = $this->tagService->find($id);

        if (!$tag) {
            abort(404, 'Category not found.');
        }

        return view('tags.show', compact('tag'));
    }

    public function edit(string $id): View
    {
        $tag = $this->tagService->find($id);
        return view('tags.edit', compact('tag'));
    }
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $data = $request->validated();
        $tag = $this->tagService->update($id, $data);

        return response()->json(['message' => 'Tag updated successfully', 'tag' => $tag], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->tagService->delete($id);

        return response()->json(['message' => 'Tag removed successfully'], 200);
    }
}
