<?php

namespace Database\Seeders;

use App\Schema\CategorySchema;
use App\Schema\ProductCategorySchema;
use App\Schema\ProductTagSchema;
use App\Schema\TagSchema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table(ProductTagSchema::TABLE)->truncate();
        DB::table(CategorySchema::TABLE)->truncate();
        DB::table(TagSchema::TABLE)->truncate();
        DB::table(ProductCategorySchema::TABLE)->truncate();
        DB::table(ProductTagSchema::TABLE)->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
