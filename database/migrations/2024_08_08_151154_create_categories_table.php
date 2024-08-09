<?php

use App\Schema\CategorySchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create(CategorySchema::TABLE, function (Blueprint $table) {
            $table->id(CategorySchema::ID);
            $table->string(CategorySchema::NAME)->unique();
            $table->string(CategorySchema::SLUG)->unique();
            $table->unsignedBigInteger(CategorySchema::PARENT_ID)->nullable();
            $table->timestamps();

            $table->foreign(CategorySchema::PARENT_ID)
                ->references(CategorySchema::ID)
                ->on(CategorySchema::TABLE)
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table(CategorySchema::TABLE, function (Blueprint $table) {
            $table->dropForeign([CategorySchema::PARENT_ID]);
        });

        Schema::dropIfExists(CategorySchema::TABLE);
    }
}
