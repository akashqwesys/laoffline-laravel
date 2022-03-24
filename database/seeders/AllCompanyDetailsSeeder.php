<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AllCompanyDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TABLES: companies, company_address_owners, company_addresses, company_categories, company_contact_details, company_emails, company_packaging_details
        $sql = file_get_contents(base_path('storage/app/sql/companies.sql'));
        DB::unprepared($sql);
    }
}
