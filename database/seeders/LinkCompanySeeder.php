<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class LinkCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TABLES: link_companies, link_companies_log
        $sql = file_get_contents(base_path('storage/app/sql/link_companies.sql'));
        DB::unprepared($sql);
    }
}
