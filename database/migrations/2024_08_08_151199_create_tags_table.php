<?php

use App\Schema\TagSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create(TagSchema::TABLE, function (Blueprint $table) {
            $table->id(TagSchema::ID);
            $table->string(TagSchema::NAME)->unique();
            $table->string(TagSchema::SLUG)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(TagSchema::TABLE);
    }
}
