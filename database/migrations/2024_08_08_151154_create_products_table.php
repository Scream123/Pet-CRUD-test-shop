<?php

use App\Schema\ProductSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create(ProductSchema::TABLE, function (Blueprint $table) {
            $table->id(ProductSchema::ID);
            $table->string(ProductSchema::NAME)->unique();
            $table->string(ProductSchema::SLUG)->unique();
            $table->text(ProductSchema::DESCRIPTION)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(ProductSchema::TABLE);
    }
}
