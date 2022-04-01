<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            CreatePermissionsSeeder::class,
            CreateCountriesSeeder::class,
            CreateStatesSeeder::class,
            CitySeeder::class,
            CreateCompanyType::class,
            CreateFabricFieldSeeder::class,
            CreateFabricGroupSeeder::class,
            CreateProductDefaultCategorySeeder::class,
            CreateAgentSeeder::class,
            CreateBankSeeder::class,
            CreateDesignationSeeder::class,
            AllCompanyDetailsSeeder::class,
            AllProductDetails::class,
            AllUserDetails::class,
            LinkCompanySeeder::class,
            TransportDetailsSeeder::class
        ]);
    }
}
