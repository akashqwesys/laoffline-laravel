<?php

namespace App\Models\Goods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class goodReturn extends Model
{
    use HasFactory;

    protected $fillable = [
            'id',
            'goods_return_id',
            'p_increment_id',
            'iuid',
            'reference_id',
            'financial_year_id',
            'generated_by',
            'sale_bill_id',
            'sale_bill_for',
            'company_id',
            'supplier_id',
            'supp_invoice_no',
            'multiple_attachment',
            'amount',
            'adjust_amount',
            'goods_return',
            'tot_peices',
            'tot_meters',
            'tot_amount',
            'is_deleted',
            'date_added',
    ];
}
