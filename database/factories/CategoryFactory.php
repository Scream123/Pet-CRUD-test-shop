<?php

namespace Database\Factories;

use App\Models\Category;
use App\Schema\CategorySchema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            CategorySchema::NAME => $this->faker->word,
            CategorySchema::SLUG => $this->faker->slug,
        ];
    }
}
