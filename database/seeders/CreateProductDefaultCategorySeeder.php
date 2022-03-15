<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductDefaultCategory;

class CreateProductDefaultCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productDefaultCategory = [
            [
                'id' => '1',
                'name' => 'Product',
            ],
            [
                'id' => '2',
                'name' => 'Fabric',
            ]
        ];
  
        foreach ($productDefaultCategory as $key => $value) {
            ProductDefaultCategory::create($value);
        }
    }
}
