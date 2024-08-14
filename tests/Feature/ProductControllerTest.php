<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Repositories\ProductRepository;
use App\Schema\ProductCategorySchema;
use App\Schema\ProductSchema;
use App\Schema\ProductTagSchema;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function test_index_products()
    {
        $products = Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_returns_error_on_exception()
    {
        // Mock the repository to throw an exception
        $this->mock(ProductRepository::class, function ($mock) {
            $mock->shouldReceive('paginate')
                ->once()
                ->andThrow(new \Exception('Database error'));
        });

        $response = $this->getJson('/api/products');

        $response->assertStatus(500)
            ->assertJson([
                'message' => 'Unable to fetch products',
            ]);
    }

    public function test_store_product()
    {
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $data = [
            'name' => 'Unique Product Name',
            'description' => 'Unique Product Description',
            'category_id' => $category->id,
            'tag_ids' => $tags->pluck('id')->toArray(),
        ];
        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment(['message' => 'Product added successfully!']);

        $this->assertDatabaseHas('products', [
            'name' => 'Unique Product Name',
            'description' => 'Unique Product Description',
        ]);

        $product = Product::where('name', 'Unique Product Name')->first();

        // Check that the category is associated with the product
        $this->assertTrue($product->categories->contains($category));

        // Check that the tags are associated with the product
        foreach ($tags as $tag) {
            $this->assertTrue($product->tags->contains($tag));
        }
    }

    public function test_store_product_with_error()
    {
        $data = [
            'name' => 'Faulty Product',
            'description' => 'Faulty Product Description',
            'category_id' => 9999,
            'tag_ids' => [9999],
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'tag_ids.0']);
    }

    public function test_store_product_without_required_fields_fails()
    {
        // Attempt to create a product without a required field
        $data = [
            ProductSchema::NAME => '', // Product name is empty
            ProductSchema::DESCRIPTION => 'Unique Product Description',
            ProductCategorySchema::CATEGORY_ID => null, // Missing category
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            ProductSchema::NAME,
            ProductCategorySchema::CATEGORY_ID,
        ]);
    }

    public function test_store_product_with_invalid_category_fails()
    {
        // Create tags, but there is no category
        $tags = Tag::factory()->count(2)->create();

        $data = [
            ProductSchema::NAME => 'Unique Product Name',
            ProductSchema::DESCRIPTION => 'Unique Product Description',
            ProductCategorySchema::CATEGORY_ID => 9999, // Non-existent category ID
            ProductTagSchema::TAG_ID => $tags->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(422);
        $response->assertJsonFragment([
            'errors' => [
                'category_id' => ['The selected category id is invalid.'],
            ]
        ]);
    }

    public function test_show_product()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $tag = Tag::factory()->create(['name' => 'Sale']);

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'Description of test product',
        ]);
        $product->categories()->attach($category->id);
        $product->tags()->attach($tag->id);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => 'Test Product',
                    'description' => 'Description of test product',
                    'category' => 'Electronics',
                    'tags' => 'Sale',
                    'created_at' => $product->created_at->toDateTimeString(),
                    'updated_at' => $product->updated_at->toDateTimeString(),
                ]
            ]);
    }

    public function test_show_product_not_found()
    {
        $nonExistentProductId = 99999;
        $response = $this->getJson('/api/products/' . $nonExistentProductId);
        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Product not found.',
            ]);
    }

    public function test_update_product()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Original Product Name',
            'description' => 'Original Product Description',
        ]);
        $product->categories()->attach($category1);
        $product->tags()->attach([$tag1->id, $tag2->id]);

        $data = [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => $category2->id,
            'tag_ids' => [$tag1->id],
        ];
        $response = $this->putJson("/api/products/{$product->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Product updated successfully!']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
        ]);

        $updatedProduct = Product::find($product->id);

        $this->assertTrue($updatedProduct->categories->contains($category2));
        $this->assertFalse($updatedProduct->categories->contains($category1));

        $this->assertTrue($updatedProduct->tags->contains($tag1));
        $this->assertFalse($updatedProduct->tags->contains($tag2));
    }

    public function test_update_product_validation_errors()
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Original Product Name',
            'description' => 'Original Product Description',
        ]);
        $product->categories()->attach($category);
        $product->tags()->attach([$tag->id]);

        // Check for empty name validation
        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => '',
            'description' => 'Updated Product Description',
            'category_id' => $category->id,
            'tag_ids' => [$tag->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Check for non-existent category
        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => 999,
            'tag_ids' => [$tag->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['category_id']);

        // Check for non-existent tag
        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => $category->id,
            'tag_ids' => [999],
        ]);

        $response->assertStatus(422);

        // Check for an error at a specific index in the tag_ids array
        $response->assertJsonValidationErrors(['tag_ids.0']);
    }


    public function test_update_product_invalid_category()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => 9999,
            'tag_ids' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'errors' => [
                'category_id' => [
                    'The selected category id is invalid.'
                ]
            ]
        ]);
    }

    public function test_update_product_invalid_tag()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => $category->id,
            'tag_ids' => [9999],
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'errors' => [
                'tag_ids.0' => [
                    'Some tag_ids do not exist in the database.'
                ]
            ]
        ]);
    }

    public function test_update_product_not_found()
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->putJson("/api/products/9999", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => $category->id,
            'tag_ids' => [$tag->id],
        ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Product not found.']);
    }

    public function test_update_product_server_error()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create();

        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('update')
                ->once()
                ->andThrow(new \Exception('Server error'));
        });

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'description' => 'Updated Product Description',
            'category_id' => $category->id,
            'tag_ids' => [],
        ]);

        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Error updating product'
        ]);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product deleted successfully.']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_delete_non_existing_product()
    {
        $nonExistingId = (Product::max('id') ?? 0) + 1;

        $response = $this->delete('/api/products/' . $nonExistingId);
        $response->assertStatus(404);

        $response->assertJson([
            'message' => 'Product not found.',
        ]);
    }

    public function test_delete_product_server_error()
    {
        $product = Product::factory()->create();

        // We mock the delete method of the service so that it throws an exception
        $this->mock(ProductService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new \Exception('Server error'));
        });

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Error removing product.']);
    }
}
