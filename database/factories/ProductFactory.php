<?php

namespace Database\Factories;

use App\Models\Product;
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
}
