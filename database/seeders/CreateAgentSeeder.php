<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\Agent;

class CreateAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agent = [
            ['id'=>'1','name'=>'APPLE FASHION','pan_no'=>'AASFA7122K','gst_no'=>'24AASFA7122K1ZA','include_tax'=>'0','default'=>'0','inv_prefix'=>'AF'],
            ['id'=>'2','name'=>'UTSAV FASHION','pan_no'=>'ABWPA8016H','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'UF'],
            ['id'=>'3','name'=>'SAXI IMPEX','pan_no'=>'AAAHC8093D','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'SI'],
            ['id'=>'4','name'=>'BHOR IMPEX','pan_no'=>'ABWPA8017G','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'BI'],
            ['id'=>'5','name'=>'VRINDA FASHION','pan_no'=>'AAAHL6508C','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'VRF'],
            ['id'=>'6','name'=>'VINTAGGE FASHION','pan_no'=>'BPMPA8114F','gst_no'=>'24BPMPA8114F1Z8','include_tax'=>'0','default'=>'0','inv_prefix'=>'VIF'],
            ['id'=>'7','name'=>'PREETI INTERNATIONAL','pan_no'=>'AAJPA9952E','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'PI'],
            ['id'=>'8','name'=>'RADHA INTERNATIONAL','pan_no'=>'AAJPA2420K','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'RI'],
            ['id'=>'9','name'=>'FASHION SSARAI','pan_no'=>'','gst_no'=>'','include_tax'=>'0','default'=>'0','inv_prefix'=>'FS'],
            ['id'=>'10','name'=>'Llavesh Agarwal','pan_no'=>'ABWPA8016H','gst_no'=>'','include_tax'=>'0','default'=>'1','inv_prefix'=>'LA'],
            ['id'=>'11','name'=>'FABRIC PANDIT','pan_no'=>'BPMPA8114F','gst_no'=>'24BPMPA8114F2Z7','include_tax'=>'0','default'=>'0','inv_prefix'=>'']

        ];
  
        foreach ($agent as $key => $value) {
            Agent::create($value);
        }
    }
}
