<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FabricField;

class CreateFabricFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fabricField = [
            [
                'id' => '1',
                'product_fabric_id' => '1',
                'name' => 'Saree',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '1',
                'name' => 'Blouse',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '2',
                'name' => 'Bottom',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '2',
                'name' => 'Dupatta',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '2',
                'name' => 'Inner',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '2',
                'name' => 'Sleeves',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '2',
                'name' => 'Top',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '3',
                'name' => 'Choli',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '3',
                'name' => 'Lehenga',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '3',
                'name' => 'Dupatta',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '4',
                'name' => 'Top',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '5',
                'name' => 'Top',
                'sort_order' => '1',
            ],
            [
                'id' => '1',
                'product_fabric_id' => '5',
                'name' => 'Lining',
                'sort_order' => '1',
            ],
        ];
  
        foreach ($fabricField as $key => $value) {
            FabricField::create($value);
        }
    }
}
