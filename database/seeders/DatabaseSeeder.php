<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Schema\ProductCategorySchema;
use App\Schema\ProductTagSchema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ClearDatabaseSeeder::class);

        $products = Product::factory(10)->create();
        $categories = Category::factory(10)->create();
        $tags = Tag::factory(10)->create();

        foreach ($products as $product) {
            $category = $categories->random();
            $productTags = $tags->random(rand(1, 3));

            DB::table(ProductCategorySchema::TABLE)->insert([
                ProductCategorySchema::PRODUCT_ID => $product->id,
                ProductCategorySchema::CATEGORY_ID => $category->id,
                ProductCategorySchema::CREATED_AT => now(),
                ProductCategorySchema::UPDATED_AT => now(),
            ]);

            foreach ($productTags as $tag) {
                DB::table(ProductTagSchema::TABLE)->insert([
                    ProductTagSchema::PRODUCT_ID => $product->id,
                    ProductTagSchema::TAG_ID => $tag->id,
                    ProductTagSchema::CREATED_AT => now(),
                    ProductTagSchema::UPDATED_AT => now(),
                ]);
            }
        }
    }
}
