<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ProductCategoryTag;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $products = Product::factory(10)->create();
        $categories = Category::factory(10)->create();
        $tags = Tag::factory(10)->create();

        $productCategoryTagData = [];

        foreach ($products as $product) {
            $category = $categories->random();

            $productTags = $tags->random(rand(1, 3));

            foreach ($productTags as $tag) {
                $productCategoryTagData[] = [
                    'product_id' => $product->id,
                    'category_id' => $category->id,
                    'tag_id' => $tag->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        ProductCategoryTag::insert($productCategoryTagData);
    }
}

