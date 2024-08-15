<?php

declare(strict_types=1);

namespace App\Http\Resources\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class TagCollection extends ResourceCollection
{
    public $total;

    public function __construct($resource, $total = null)
    {
        parent::__construct($resource);
        $this->total = $total;
    }

    public function toArray(Request $request): array
    {
        $paginator = $this->collection instanceof LengthAwarePaginator ? $this->collection : null;

        return [
            'meta' => $paginator ? $this->getPaginationMeta($paginator) : [],
            'data' => $this->collection->transform(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'created_at' => $tag->created_at ? $tag->created_at->toIso8601String() : null,
                    'updated_at' => $tag->updated_at ? $tag->updated_at->toIso8601String() : null,
                ];
            }),
        ];
    }

    protected function getPaginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'path' => $paginator->path(),
            'links' => $this->getPaginationLinks($paginator),
        ];
    }

    protected function getPaginationLinks(LengthAwarePaginator $paginator): array
    {
        return [
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ];
    }
}
