<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Schema\CategorySchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index()
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






    /** @test */
    public function test_store()
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




    /** @test */
    public function test_show_existing_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);
        $responseData = $response->json('data');

        $createdAt = Carbon::parse($category->created_at)->format('Y-m-d H:i:s');
        $updatedAt = Carbon::parse($category->updated_at)->format('Y-m-d H:i:s');
        $response->assertJson(['data'=>[
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

    public function test_update()
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










    /** @test */
    public function test_delete()
    {
        $category = Category::factory()->create();

        // Disable CSRF protection for tests
        $response = $this->withoutMiddleware()->delete('/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Category successfully deleted',
        ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
