<?php

declare(strict_types=1);

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Category;

class CategoryCollection extends ResourceCollection
{
    public $total;

    public function __construct($resource, $total = null)
    {
        parent::__construct($resource);
        $this->total = $total;
    }

    public function toArray(Request $request): array
    {
        // Check if the collection is a LengthAwarePaginator object
        $paginator = $this->collection instanceof LengthAwarePaginator ? $this->collection : null;

        return [
            'meta' => $paginator ? $this->getPaginationMeta($paginator) : [],
            'data' => $this->collection->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'parent_category' => $this->getParentCategoryInfo($category),
                    'slug' => $category->slug,
                    'created_at' => $category->created_at ? $category->created_at->toIso8601String() : null,
                    'updated_at' => $category->updated_at ? $category->updated_at->toIso8601String() : null,
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

    protected function getParentCategoryInfo($category): ?array
    {
        if ($category->parent_id) {
            $parentCategory = $category->parent;

            return [
                'id' => $parentCategory->id,
                'name' => $parentCategory->name,
                'slug' => $parentCategory->slug,
            ];
        }

        return null; // If parent category is not specified
    }
}
