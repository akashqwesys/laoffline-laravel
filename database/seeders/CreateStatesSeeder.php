<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\State;

class CreateStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'id' => '1', 
                'country_id' => '105', 
                'name' => 'Andaman and Nicobar Islands'
            ],
            [
                'id' => '2', 
                'country_id' => '105', 
                'name' => 'Andhra Pradesh'
            ],
            [
                'id' => '3', 
                'country_id' => '105', 
                'name' => 'Arunachal Pradesh'
            ],
            [
                'id' => '4', 
                'country_id' => '105', 
                'name' => 'Assam'
            ],
            [
                'id' => '5', 
                'country_id' => '105', 
                'name' => 'Bihar'
            ],
            [
                'id' => '6', 
                'country_id' => '105', 
                'name' => 'Chandigarh'
            ],
            [
                'id' => '7', 
                'country_id' => '105', 
                'name' => 'Chhattisgarh'
            ],
            [
                'id' => '8', 
                'country_id' => '105', 
                'name' => 'Dadra and Nagar Haveli'
            ],
            [
                'id' => '9', 
                'country_id' => '105', 
                'name' => 'Daman and Diu'
            ],
            [
                'id' => '10', 
                'country_id' => '105', 
                'name' => 'Delhi'
            ],
            [
                'id' => '11', 
                'country_id' => '105', 
                'name' => 'Goa'
            ],
            [
                'id' => '12', 
                'country_id' => '105', 
                'name' => 'Gujarat'
            ],
            [
                'id' => '13', 
                'country_id' => '105', 
                'name' => 'Haryana'
            ],
            [
                'id' => '14', 
                'country_id' => '105', 
                'name' => 'Himachal Pradesh'
            ],
            [
                'id' => '15', 
                'country_id' => '105', 
                'name' => 'Jammu and Kashmir'
            ],
            [
                'id' => '16', 
                'country_id' => '105', 
                'name' => 'Jharkhand'
            ],
            [
                'id' => '17', 
                'country_id' => '105', 
                'name' => 'Karnataka'
            ],
            [
                'id' => '18', 
                'country_id' => '105', 
                'name' => 'Kerala'
            ],
            [
                'id' => '19', 
                'country_id' => '105', 
                'name' => 'Lakshadweep'
            ],
            [
                'id' => '20', 
                'country_id' => '105', 
                'name' => 'Madhya Pradesh'
            ],
            [
                'id' => '21', 
                'country_id' => '105', 
                'name' => 'Maharashtra'
            ],
            [
                'id' => '22', 
                'country_id' => '105', 
                'name' => 'Manipur'
            ],
            [
                'id' => '23', 
                'country_id' => '105', 
                'name' => 'Meghalaya'
            ],
            [
                'id' => '24', 
                'country_id' => '105', 
                'name' => 'Mizoram'
            ],
            [
                'id' => '25', 
                'country_id' => '105', 
                'name' => 'Nagaland'
            ],
            [
                'id' => '26', 
                'country_id' => '105', 
                'name' => 'Odisha'
            ],
            [
                'id' => '27', 
                'country_id' => '105', 
                'name' => 'Puducherry'
            ],
            [
                'id' => '28', 
                'country_id' => '105', 
                'name' => 'Punjab'
            ],
            [
                'id' => '29', 
                'country_id' => '105', 
                'name' => 'Rajasthan'
            ],
            [
                'id' => '30', 
                'country_id' => '105', 
                'name' => 'Sikkim'
            ],
            [
                'id' => '31', 
                'country_id' => '105', 
                'name' => 'Tamil Nadu'
            ],
            [
                'id' => '32', 
                'country_id' => '105', 
                'name' => 'Telangana'
            ],
            [
                'id' => '33', 
                'country_id' => '105', 
                'name' => 'Tripura'
            ],
            [
                'id' => '34', 
                'country_id' => '105', 
                'name' => 'Uttar Pradesh'
            ],
            [
                'id' => '35', 
                'country_id' => '105', 
                'name' => 'Uttarakhand'
            ],
            [
                'id' => '36', 
                'country_id' => '105', 
                'name' => 'West Bengal'
            ],
        ];
  
        foreach ($states as $key => $value) {
            State::create($value);
        }
    }
}
