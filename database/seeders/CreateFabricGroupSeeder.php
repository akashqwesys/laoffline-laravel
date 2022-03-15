<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductFabricGroup;

class CreateFabricGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fabricGroup = [
            [
                'id' => '1',
                'name' => 'Saree',
            ],
            [
                'id' => '2',
                'name' => 'Salwar Kameez',
            ],
            [
                'id' => '3',
                'name' => 'Lehenga',
            ],
            [
                'id' => '4',
                'name' => 'Kurti',
            ],
            [
                'id' => '5',
                'name' => 'Gown',
            ]
        ];
  
        foreach ($fabricGroup as $key => $value) {
            ProductFabricGroup::create($value);
        }
    }
}
