<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $this->getCategoryInfo($product),
                    'tags' => $product->tags->pluck('name')->implode(', '),
                    'description' => $product->description,
                    'slug' => $product->slug,
                    'created_at' => $product->created_at ? $product->created_at->toIso8601String() : null,
                    'updated_at' => $product->updated_at ? $product->updated_at->toIso8601String() : null,
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

    protected function getCategoryInfo(ProductResource  $product): ?array
    {
        $category = $product->categories ? $product->categories->first() : null;

        if ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'parent' => $category->parent_id ? [
                    'id' => $category->parent->id,
                    'name' => $category->parent->name,
                    'slug' => $category->parent->slug,
                ] : null,
            ];
        }

        return null; // If the category is not specified
    }

}
