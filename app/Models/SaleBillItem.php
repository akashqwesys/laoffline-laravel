<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleBillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_bill_id',
        'financial_year_id',
        'product_or_fabric_id',
        'sub_product_id',
        'pieces',
        'cut',
        'meters',
        'pieces_meters',
        'rate',
        'hsn_code',
        'discount',
        'discount_amount',
        'cgst',
        'cgst_amount',
        'sgst',
        'sgst_amount',
        'igst',
        'igst_amount',
        'amount',
        'main_or_sub',
        'inward_order_action_id',
        'is_deleted',
    ];
}
