<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyType;

class CreateCompanyType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companyType = [
            [
                'id' => '1',
                'name' => 'General',
            ],
            [
                'id' => '2',
                'name' => 'Customer',
            ],
            [
                'id' => '3',
                'name' => 'Supplier',
            ]
        ];
  
        foreach ($companyType as $key => $value) {
            CompanyType::create($value);
        }
    }
}
