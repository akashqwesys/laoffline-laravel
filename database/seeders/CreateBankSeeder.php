<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings\BankDetails;

class CreateBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            ['id'=>'1','name'=>'Bank Of India ','sort_order'=>'1'],
            ['id'=>'2','name'=>'State Bank Of India','sort_order'=>'2'],
            ['id'=>'3','name'=>'ICICI Bank','sort_order'=>'3'],
            ['id'=>'4','name'=>'CHQ ON HAND ','sort_order'=>'0'],
            ['id'=>'5','name'=>'ING VYSYA BANK ','sort_order'=>'0'],
            ['id'=>'6','name'=>'HDFC BANK','sort_order'=>'0'],
            ['id'=>'7','name'=>'Kotak Mahindra Bank','sort_order'=>'0'],
            ['id'=>'8','name'=>'Bank Of Baroda ','sort_order'=>'0'],
            ['id'=>'9','name'=>'UBI','sort_order'=>'0'],
            ['id'=>'10','name'=>'CBI','sort_order'=>'0'],
            ['id'=>'11','name'=>'PNB','sort_order'=>'0'],
            ['id'=>'12','name'=>'AXIS BANK','sort_order'=>'0'],
            ['id'=>'13','name'=>'DENA BANK','sort_order'=>'0'],
            ['id'=>'14','name'=>'INDIAN BANK','sort_order'=>'0'],
            ['id'=>'15','name'=>'INDIAN BANK','sort_order'=>'0'],
            ['id'=>'16','name'=>'VIJAYA BANK','sort_order'=>'0'],
            ['id'=>'17','name'=>'YES BANK LTD','sort_order'=>'0'],
            ['id'=>'18','name'=>'SVC BANK','sort_order'=>'0'],
            ['id'=>'19','name'=>'F.B.L','sort_order'=>'0'],
            ['id'=>'20','name'=>'CITI BANK','sort_order'=>'0'],
            ['id'=>'21','name'=>'CITI BANK','sort_order'=>'0'],
            ['id'=>'22','name'=>'ALLAHABAD BANK','sort_order'=>'0'],
            ['id'=>'23','name'=>'CANARA BANK','sort_order'=>'0'],
            ['id'=>'24','name'=>'TMB','sort_order'=>'0'],
            ['id'=>'25','name'=>'SYNDICATE BANK','sort_order'=>'0'],
            ['id'=>'26','name'=>'New India Co.Op.Bank ','sort_order'=>'0'],
            ['id'=>'27','name'=>'The Surat Peo.Co.Op.Bank','sort_order'=>'0'],
            ['id'=>'28','name'=>'CUB','sort_order'=>'0'],
            ['id'=>'29','name'=>'city bank','sort_order'=>'0'],
            ['id'=>'30','name'=>'The Sutex Co.Op.Bank Ltd.','sort_order'=>'0'],
            ['id'=>'31','name'=>'O B C','sort_order'=>'0'],
            ['id'=>'32','name'=>'UCO BANK','sort_order'=>'0'],
            ['id'=>'33','name'=>'Lakshmi Vilas Bank','sort_order'=>'0'],
            ['id'=>'34','name'=>'IDBI BANK','sort_order'=>'0'],
            ['id'=>'35','name'=>'SCB','sort_order'=>'0'],
            ['id'=>'36','name'=>'THE CATHOLIC SYRIAN BANK.LTD','sort_order'=>'0'],
            ['id'=>'37','name'=>'THE MEHSANA USBAN CO.OP.BANK LTD','sort_order'=>'0'],
            ['id'=>'39','name'=>'SOUTH INDIAN BANK','sort_order'=>'0'],
            ['id'=>'40','name'=>'PRIME CO-OP.BANK LTD','sort_order'=>'0'],
            ['id'=>'41','name'=>'Bandhan Bank ','sort_order'=>'0'],
            ['id'=>'42','name'=>'PRIVY LEAGUE','sort_order'=>'0'],
            ['id'=>'43','name'=>'THE KARUR VYSYA BANK LIMITED','sort_order'=>'0'],
            ['id'=>'44','name'=>'CORPORATION BANK','sort_order'=>'0'],
            ['id'=>'45','name'=>'THE PANCHSHEEL MERCANTILE CO-OP. BANK LTD','sort_order'=>'0'],
            ['id'=>'46','name'=>'PANJAB & MAHARASHTRA CO-OPERATIVE BANK LIMITED','sort_order'=>'0'],
            ['id'=>'47','name'=>'STANDARD CHARTERED BANK','sort_order'=>'0'],
            ['id'=>'48','name'=>'STATE BANK OF PATIALA','sort_order'=>'0'],
            ['id'=>'49','name'=>'RBL BANK','sort_order'=>'0'],
            ['id'=>'50','name'=>'INDUSIND BANK','sort_order'=>'0'],
            ['id'=>'51','name'=>'Associate co-op.bank ltd','sort_order'=>'0'],
            ['id'=>'52','name'=>'Rajkot Nagarik sahakari bank ltd','sort_order'=>'0'],
            ['id'=>'53','name'=>'BANK OF MAHARASHTRA','sort_order'=>'0'],
            ['id'=>'54','name'=>'COSMOS BANK','sort_order'=>'0'],
            ['id'=>'55','name'=>'The Financial Co-operative Bank ltd','sort_order'=>'0'],
            ['id'=>'56','name'=>'kayur vysya bank ','sort_order'=>'0'],
            ['id'=>'57','name'=>'CORPORATION BANK','sort_order'=>'0'],
            ['id'=>'58','name'=>'the shamrao vithal co-op','sort_order'=>'0'],
            ['id'=>'59','name'=>'J & K BANK','sort_order'=>'0'],
            ['id'=>'60','name'=>'ADARSH CO-OPERATIVE BANK','sort_order'=>'0'],
            ['id'=>'61','name'=>'THE KALUPUR COMMERCIAL CO-OPERATIVE BANK LIMITED','sort_order'=>'0'],
            ['id'=>'62','name'=>'punjab and sind','sort_order'=>'0'],
            ['id'=>'63','name'=>'IDFC BANK','sort_order'=>'0'],
            ['id'=>'64','name'=>'Surat national ','sort_order'=>'0'],
            ['id'=>'65','name'=>'Akhand anand co-op bank','sort_order'=>'0'],
            ['id'=>'66','name'=>'DCB BANK ','sort_order'=>'0'],
            ['id'=>'67','name'=>'Punjab & Sind Bank','sort_order'=>'0'],
            ['id'=>'68','name'=>'THE SARVODAYA SAHAKARI BANK LTD.','sort_order'=>'0'],
            ['id'=>'69','name'=>'karnatak','sort_order'=>'0']
        ];
  
        foreach ($banks as $key => $value) {
            BankDetails::create($value);
        }
    }
}

