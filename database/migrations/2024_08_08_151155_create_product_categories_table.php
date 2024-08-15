<?php

use App\Schema\CategorySchema;
use App\Schema\ProductCategorySchema;
use App\Schema\ProductSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create(ProductCategorySchema::TABLE, function (Blueprint $table) {
            $table->id(ProductCategorySchema::ID);
            $table->unsignedBigInteger(ProductCategorySchema::PRODUCT_ID);
            $table->unsignedBigInteger(ProductCategorySchema::CATEGORY_ID);
            $table->timestamps();

            $table->foreign(ProductCategorySchema::PRODUCT_ID)
                ->references(ProductSchema::ID)
                ->on(ProductSchema::TABLE)
                ->onDelete('cascade');

            $table->foreign(ProductCategorySchema::CATEGORY_ID)
                ->references(CategorySchema::ID)
                ->on(CategorySchema::TABLE)
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists(ProductCategorySchema::TABLE);
    }
}
