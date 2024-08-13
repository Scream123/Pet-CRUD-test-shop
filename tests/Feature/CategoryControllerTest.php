<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Schema\CategorySchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_categories()
    {
        $categories = Category::factory()->count(10)->create();

        $response = $this->get('/api/categories');

        $response->assertStatus(Response::HTTP_OK);

        foreach ($categories as $category) {
            $response->assertJsonFragment([
                'id' => $category->id,
                'name' => $category->name,
                'parent_category' => $category->parent_id,
                'slug' => $category->slug,
            ]);
        }
    }

    public function test_index_no_categories()
    {
        $response = $this->get('/api/categories');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([]);
    }

    public function test_store_category()
    {
        $data = Category::factory()->make()->toArray();

        $response = $this->post('/api/categories', $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $responseData = $response->json('category');

        $createdAt = Carbon::parse($responseData['created_at'])->format('Y-m-d H:i:s');
        $updatedAt = Carbon::parse($responseData['updated_at'])->format('Y-m-d H:i:s');

        $response->assertJson([
            'message' => 'Category successfully created',
            'category' => [
                CategorySchema::NAME => $data['name'],
                CategorySchema::SLUG => $responseData['slug'],
                CategorySchema::CREATED_AT => $createdAt,
                CategorySchema::UPDATED_AT => $updatedAt,
                CategorySchema::ID => $responseData['id'],
            ],
        ]);

        $this->assertDatabaseHas(CategorySchema::TABLE, [
            CategorySchema::NAME => $data['name'],
            CategorySchema::SLUG => $responseData['slug'],
        ]);
    }

    public function test_store_invalid_data_categories()
    {
        $data = [];

        $response = $this->post('/api/categories', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);
        $responseData = $response->json('data');

        $createdAt = Carbon::parse($category->created_at)->format('Y-m-d H:i:s');
        $updatedAt = Carbon::parse($category->updated_at)->format('Y-m-d H:i:s');
        $response->assertJson(['data' => [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]]);
    }

    /** @test */
    public function test_show_non_existing_category()
    {
        $response = $this->getJson('/api/categories/' . Str::uuid()->toString());

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson([
            'message' => 'Category not found.',
        ]);
    }

    public function test_update_categories()
    {
        $category = Category::factory()->create();

        $updateData = [
            CategorySchema::NAME => 'Updated Category Name ' . Str::random(5),
            CategorySchema::PARENT_ID => null
        ];

        $response = $this->put('/api/categories/' . $category->id, $updateData);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas(CategorySchema::TABLE, [
            CategorySchema::ID => $category->id,
            CategorySchema::NAME => $updateData['name'],
            CategorySchema::PARENT_ID => $updateData['parent_id'],
        ]);

        $this->assertDatabaseMissing(CategorySchema::TABLE, [
            CategorySchema::NAME => 'New Category Name',
        ]);
    }

    public function test_update_non_existing_category()
    {
        $updateData = [
            CategorySchema::NAME => 'Updated Category Name ' . Str::random(5),
        ];

        $response = $this->put('/api/categories/' . Str::uuid()->toString(), $updateData);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => 'Category not found.',
        ]);
    }

    public function test_update_invalid_data_categories()
    {
        $category = Category::factory()->create();

        $updateData = [
            CategorySchema::NAME => '',
        ];

        $response = $this->put('/api/categories/' . $category->id, $updateData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }


    public function test_update_name_already_exists_categories()
    {
        // Create two categories: one with the existing name and the other, which we will update
        $category1 = Category::factory()->create(['name' => 'Existing Name']);
        $category2 = Category::factory()->create();

        $updateData = [
            CategorySchema::NAME => 'Existing Name',
        ];

        $response = $this->put('/api/categories/' . $category2->id, $updateData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->withoutMiddleware()->delete('/api/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'message' => 'Category deleted successfully.',
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_delete_non_existing_category()
    {
        $nonExistingId = (Category::max('id') ?? 0) + 1;

        $response = $this->withoutMiddleware()->delete('/api/categories/' . $nonExistingId);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson([
            'message' => 'Category not found.',
        ]);
    }
}
