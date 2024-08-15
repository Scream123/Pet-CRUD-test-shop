<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Schema\TagSchema;
use App\Services\TagService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_index_tags()
    {
        $tags = Tag::factory()->count(10)->create();

        $response = $this->get('/api/tags');

        $response->assertStatus(Response::HTTP_OK);

        foreach ($tags as $tag) {
            $response->assertJsonFragment([
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ]);
        }
    }

    public function test_index_no_tags()
    {
        $response = $this->get('/api/tags');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([]);
    }

    public function test_store_tag()
    {
        $data = Tag::factory()->make()->toArray();

        $response = $this->post('/api/tags', $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $responseData = $response->json('tag');

        $createdAt = Carbon::parse($responseData['created_at'])->format('Y-m-d H:i:s');
        $updatedAt = Carbon::parse($responseData['updated_at'])->format('Y-m-d H:i:s');

        $response->assertJson([
            'message' => 'Tag successfully created',
            'tag' => [
                TagSchema::NAME => $data['name'],
                TagSchema::SLUG => $responseData['slug'],
                TagSchema::CREATED_AT => $createdAt,
                TagSchema::UPDATED_AT => $updatedAt,
                TagSchema::ID => $responseData['id'],
            ],
        ]);

        $this->assertDatabaseHas(TagSchema::TABLE, [
            TagSchema::NAME => $data['name'],
            TagSchema::SLUG => $responseData['slug'],
        ]);
    }

    public function test_store_invalid_data_tags()
    {
        $data = [];

        $response = $this->post('/api/tags', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_show_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->getJson('/api/tags/' . $tag->id);

        $response->assertStatus(Response::HTTP_OK);
        $responseData = $response->json('data');

        $createdAt = Carbon::parse($tag->created_at)->format('Y-m-d H:i:s');
        $updatedAt = Carbon::parse($tag->updated_at)->format('Y-m-d H:i:s');
        $response->assertJson(['data' => [
            TagSchema::ID => $tag->id,
            TagSchema::NAME => $tag->name,
            TagSchema::SLUG => $tag->slug,
            TagSchema::CREATED_AT => $createdAt,
            TagSchema::UPDATED_AT => $updatedAt,
        ]]);
    }

    /** @test */
    public function test_update_invalid_data_tags()
    {
        $tag = Tag::factory()->create();

        $updateData = [
            TagSchema::NAME => '',
        ];

        $response = $this->put('/api/tags/' . $tag->id, $updateData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_update_name_already_exists_tags()
    {
        $tag1 = Tag::factory()->create(['name' => 'Existing Name']);
        $tag2 = Tag::factory()->create();

        $updateData = [
            TagSchema::NAME => 'Existing Name',
        ];

        $response = $this->put('/api/tags/' . $tag2->id, $updateData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_delete_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->withoutMiddleware()->delete('/api/tags/' . $tag->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'message' => 'Tag deleted successfully.',
        ]);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_delete_non_existing_tag()
    {
        $nonExistingId = (Tag::max('id') ?? 0) + 1;

        $response = $this->withoutMiddleware()->delete('/api/tags/' . $nonExistingId);
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson([
            'message' => 'Tag not found.',
        ]);
    }

    public function test_tag_product_server_error()
    {
        $tag = Tag::factory()->create();

        // We mock the delete method of the service so that it throws an exception
        $this->mock(TagService::class, function ($mock) {
            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new \Exception('Server error'));
        });

        $response = $this->deleteJson("/api/tags/{$tag->id}");
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Error removing tag.']);
    }
}
