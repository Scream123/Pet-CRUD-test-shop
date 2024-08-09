<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ClearDatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('tags')->truncate();
        DB::table('product_categories')->truncate();
        DB::table('product_tags')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
