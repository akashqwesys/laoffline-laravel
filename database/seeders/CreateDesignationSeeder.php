<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Designation;

class CreateDesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designation = [
            ['id'=>'1','name'=>'Owner'],
            ['id'=>'2','name'=>'OWNER'],
            ['id'=>'3','name'=>'MANAGER'],
            ['id'=>'4','name'=>'ASSISTANT'],
            ['id'=>'5','name'=>'Assistant Manager'],
            ['id'=>'6','name'=>'ACCOUNTANT'],
            ['id'=>'7','name'=>'COLLECTION'],
            ['id'=>'8','name'=>'DYEING MASTER'],
            ['id'=>'9','name'=>'SALES'],
            ['id'=>'10','name'=>'FIELD EXECUTIVE'],
            ['id'=>'11','name'=>'All in one'],
            ['id'=>'12','name'=>'OWNER (No message)'],
            ['id'=>'13','name'=>'(Payment message only)'],
            ['id'=>'14','name'=>'MANAGING DIRECTOR'],
            ['id'=>'15','name'=>'DIRECTOR'],
            ['id'=>'16','name'=>'MARKETING HEAD'],
            ['id'=>'17','name'=>'Testing message'],
            ['id'=>'18','name'=>'VICE PRESIDENT'],
            ['id'=>'19','name'=>'ASSISTANT MANAGER'],
            ['id'=>'20','name'=>'CIVIL ENGINEER'],
            ['id'=>'21','name'=>'PRINTING MASTER'],
            ['id'=>'22','name'=>'FOUNDER'],
            ['id'=>'23','name'=>'CEO'],
            ['id'=>'24','name'=>'DISPATCH EXECUTIVE']
        ];
  
        foreach ($designation as $key => $value) {
            Designation::create($value);
        }
    }
}
