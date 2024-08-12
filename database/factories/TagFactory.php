<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Schema\TagSchema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        return [
            TagSchema::NAME => $this->faker->unique()->word,
            TagSchema::SLUG => $this->faker->unique()->slug,
        ];
    }
}
