<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ClearDatabaseSeeder::class);

        $products = Product::factory(10)->create();
        $categories = Category::factory(10)->create();
        $tags = Tag::factory(10)->create();

        foreach ($products as $product) {
            $category = $categories->random();

            $productTags = $tags->random(rand(1, 3));

            DB::table('product_categories')->insert([
                'product_id' => $product->id,
                'category_id' => $category->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($productTags as $tag) {
                DB::table('product_tags')->insert([
                    'product_id' => $product->id,
                    'tag_id' => $tag->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
