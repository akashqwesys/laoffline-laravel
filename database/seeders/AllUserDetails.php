<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AllUserDetails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TABLES: users, user_groups, employees
        $sql = file_get_contents(base_path('storage/app/sql/users.sql'));
        DB::unprepared($sql);
    }
}
