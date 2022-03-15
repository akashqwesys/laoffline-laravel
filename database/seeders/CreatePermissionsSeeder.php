<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
            [
                'name' => 'access-user-group',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-bank-details',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-default-settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-employee',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-payment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-redorded-files',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-product-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-ledger',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-message',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-commission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-company-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-sms-settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-link-companies',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-designation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-calendar',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-sale-bill-agent',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-product-sub-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-register',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-agent',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-type-of-address',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-reference-id',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-send',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-reports',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-sale-bill',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-enjoy-call-records',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-transport',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-auth-config',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-cities',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-attendance',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-countries',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-states',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-fabric-group',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-companytype',
                'guard_name' => 'web',
            ],
            [
                'name' => 'access-permission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-user-group',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-bank-details',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-default-settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-employee',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-payment',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-redorded-files',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-product-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-ledger',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-company',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-message',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-commission',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-company-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-sms-settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-link-companies',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-designation',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-calendar',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-sale-bill-agent',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-product-sub-category',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-register',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-agent',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-type-of-address',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-reference-id',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-send',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-reports',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-sale-bill',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-enjoy-call-records',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-transport',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-auth-config',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-cities',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-attendance',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-countries',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-states',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-fabric-group',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-companytype',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modify-permission',
                'guard_name' => 'web',
            ],
        ];
  
        foreach ($permission as $key => $value) {
            Permission::create($value);
        }
    }
}
