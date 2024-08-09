<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Http\Resources\Tag\TagCollection;
use App\Http\Resources\Tag\TagResource;
use App\Interfaces\TagRepositoryInterface;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    protected $tagService;
    protected $tagRepository;

    public function __construct(TagService $tagService, TagRepositoryInterface $tagRepository)
    {
        $this->tagService = $tagService;
        $this->tagRepository = $tagRepository;

    }

    public function index()
    {
        $categories = $this->tagRepository->paginate();

        return new TagCollection($categories);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $tag = $this->tagService->create($data);

        return response()->json(['message' => 'Ефп successfully created', 'category' => $tag], 201);
    }

    public function show($id)
    {
        $category = $this->tagRepository->find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return new TagResource($category);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();
        $tag = $this->tagService->update($id, $data);

        return response()->json(['message' => 'Tag successfully updated', 'tag' => $tag], 200);
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->tagService->delete($id);

            return response()->json(['message' => 'Tag deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing tag.'], 500);
        }
    }
}
