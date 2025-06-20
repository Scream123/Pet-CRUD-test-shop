<?php

declare(strict_types=1);

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\DestroyRequest;
use App\Http\Requests\Tag\IndexRequest;
use App\Http\Requests\Tag\ShowRequest;
use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Http\Resources\Tag\TagCollection;
use App\Http\Resources\Tag\TagResource;
use App\Interfaces\TagRepositoryInterface;
use App\Services\TagService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    protected $tagService;
    protected $tagRepository;

    public function __construct(TagService $tagService, TagRepositoryInterface $tagRepository)
    {
        $this->tagService = $tagService;
        $this->tagRepository = $tagRepository;
    }

    public function index(IndexRequest $request): TagCollection
    {
        $tags = $this->tagRepository->paginate();

        return new TagCollection($tags);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $tag = $this->tagService->create($data);

        return response()->json(['message' => 'Tag successfully created', 'tag' => $tag], 201);
    }

    public function show(ShowRequest $request): TagResource|JsonResponse
    {
        $validated = $request->validated();
        $tag = $this->tagRepository->find($validated['id']);

        if (!$tag) {
            return response()->json(['message' => 'Tag not found.'], 404);
        }

        return new TagResource($tag);
    }

    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $tag = $this->tagService->update($id, $request->validated());

            return response()->json($tag, Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Tag not found.'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(DestroyRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $this->tagService->delete($validated['id']);

            return response()->json(['message' => 'Tag deleted successfully.'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Tag not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing tag.'], 500);
        }
    }
}
