<?php

use App\Schema\ProductSchema;
use App\Schema\ProductTagSchema;
use App\Schema\TagSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTagsTable extends Migration
{
    public function up()
    {
        Schema::create(ProductTagSchema::TABLE, function (Blueprint $table) {
            $table->id(ProductTagSchema::ID);
            $table->unsignedBigInteger(ProductTagSchema::PRODUCT_ID);
            $table->unsignedBigInteger(ProductTagSchema::TAG_ID);
            $table->timestamps();

            $table->foreign(ProductTagSchema::PRODUCT_ID)
                ->references(ProductSchema::ID)
                ->on(ProductSchema::TABLE)
                ->onDelete('cascade');

            $table->foreign(ProductTagSchema::TAG_ID)
                ->references(TagSchema::ID)
                ->on(TagSchema::TABLE)
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists(ProductTagSchema::TABLE);
    }
}
