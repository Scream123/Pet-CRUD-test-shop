<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Schema\ProductSchema;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            ProductSchema::NAME => $this->faker->unique()->word,
            ProductSchema::SLUG => $this->faker->unique()->slug,
            ProductSchema::DESCRIPTION => $this->faker->paragraph,
        ];
    }

    public function withCategoryAndTags()
    {
        return $this->afterCreating(function (Product $product) {
            $category = Category::factory()->create();
            $tags = Tag::factory()->count(2)->create();

            $product->categories()->attach($category->id);
            $product->tags()->sync($tags->pluck('id')->toArray());
        });
    }
}
