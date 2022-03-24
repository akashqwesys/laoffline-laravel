<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AllProductDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TABLES: products, product_categories, product_details, product_fabric_details, product_fabric_groups, products_images
        $sql = file_get_contents(base_path('storage/app/sql/products.sql'));
        DB::unprepared($sql);
    }
}
