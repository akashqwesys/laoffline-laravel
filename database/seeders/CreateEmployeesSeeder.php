<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class CreateEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee = [
            [
                'id' => 2,
                'firstname' => 'Admin',
                'middlename' => 'Admin',
                'lastname' => 'Admin',
                'profile_pic' => '',
                'email_id' => 'admin@admin.com',
                'mobile' => '1234567890',
                'address' => 'This is address',
                'user_group' => '1',
                'excel_access' => '1',
                'id_proof' => '',
                'ref_full_name' => 'Refrence 1',
                'ref_pass_pic' => '',
                'ref_mobile' => '1234657890',
                'ref_address' => 'This is refrence address',
                'web_login' => '',
            ]
        ];
  
        foreach ($employee as $key => $value) {
            Employee::create($value);
        }
    }
}
