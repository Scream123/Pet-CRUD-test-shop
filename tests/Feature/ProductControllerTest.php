<?php

namespace Tests\Feature;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Product;
use App\Schema\ProductCategorySchema;
use App\Schema\ProductSchema;
use App\Schema\ProductTagSchema;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $productService;
    protected $productRepository;
    protected $tagRepository;
    protected $categoriesRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = Mockery::mock(ProductService::class);
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->tagRepository = Mockery::mock(TagRepositoryInterface::class);
        $this->categoriesRepository = Mockery::mock(CategoryRepositoryInterface::class);
        $this->categoriesRepository = Mockery::mock(CategoryRepositoryInterface::class);

        $this->app->instance(ProductService::class, $this->productService);
        $this->app->instance(ProductRepositoryInterface::class, $this->productRepository);
        $this->app->instance(TagRepositoryInterface::class, $this->tagRepository);
        $this->app->instance(CategoryRepositoryInterface::class, $this->categoriesRepository);
    }

    /** @test */
    public function test_list_products()
    {
        $products = Product::factory()->count(3)->make();
        $this->productRepository->shouldReceive('paginate')->andReturn($products);

        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'category',
                    'tags',
                    'description',
                    'slug',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function test_create_product()
    {
        /** @var \App\Models\Product $product */
        $product = Product::factory()->withCategoryAndTags()->create();


        $response = $this->postJson('/api/products', [
            ProductSchema::NAME => $product->{ProductSchema::NAME},
            ProductSchema::SLUG => $product->{ProductSchema::SLUG},
            ProductSchema::DESCRIPTION => $product->{ProductSchema::DESCRIPTION},
            'category_id' => $product->categories->first()->id,
            'tags' => $product->tags->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['message' => 'Product added successfully!']);

        $this->assertDatabaseHas('products', [
            ProductSchema::NAME => $product->{ProductSchema::NAME},
            ProductSchema::SLUG => $product->{ProductSchema::SLUG},
            ProductSchema::DESCRIPTION => $product->{ProductSchema::DESCRIPTION},
        ]);

        $productId = $response->json('product.' . ProductSchema::ID);

        $this->assertDatabaseHas(ProductCategorySchema::TABLE, [
            ProductCategorySchema::PRODUCT_ID => $productId,
            ProductCategorySchema::CATEGORY_ID => $product->categories->first()->id,
        ]);

        foreach ($product->tags->pluck('id') as $tagId) {
            $this->assertDatabaseHas(ProductTagSchema::TABLE, [
                ProductTagSchema::PRODUCT_ID => $productId,
                ProductTagSchema::TAG_ID => $tagId,
            ]);
        }
    }



}
