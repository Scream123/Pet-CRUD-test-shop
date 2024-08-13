<?php

namespace Tests\Feature;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
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
    public function test_index_products()
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

    public function test_index_products_failure()
    {
        $this->productRepository->shouldReceive('paginate')
            ->andThrow(new \Exception('Unable to fetch products.'));

        $response = $this->getJson('/api/products');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'message' => 'Unable to fetch products.'
        ]);
    }

    public function test_store_product()
    {
        // Создаем категорию и теги для нового продукта
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        // Отправляем запрос для создания нового продукта
        $response = $this->postJson('/api/products', [
            ProductSchema::NAME => 'Test Product',
            ProductSchema::SLUG => 'test-product',
            ProductSchema::DESCRIPTION => 'This is a test product.',
            'category_id' => $category->id,
            'tags' => $tags->pluck('id')->toArray()
        ]);

        // Проверяем успешный ответ
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['message' => 'Product added successfully!']);

        // Проверяем, что продукт добавлен в базу данных
        $productId = $response->json('product.' . ProductSchema::ID);

        $this->assertDatabaseHas('products', [
            ProductSchema::NAME => 'Test Product',
            ProductSchema::SLUG => 'test-product',
            ProductSchema::DESCRIPTION => 'This is a test product.',
        ]);

        // Проверяем, что продукт связан с категорией
        $this->assertDatabaseHas(ProductCategorySchema::TABLE, [
            ProductCategorySchema::PRODUCT_ID => $productId,
            ProductCategorySchema::CATEGORY_ID => $category->id,
        ]);

        // Проверяем, что продукт связан с тегами
        foreach ($tags->pluck('id') as $tagId) {
            $this->assertDatabaseHas(ProductTagSchema::TABLE, [
                ProductTagSchema::PRODUCT_ID => $productId,
                ProductTagSchema::TAG_ID => $tagId,
            ]);
        }
    }




}
