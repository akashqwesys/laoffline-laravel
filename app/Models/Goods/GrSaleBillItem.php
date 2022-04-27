<?php

namespace App\Models\Goods;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrSaleBillItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'gr_sale_bill_item_id', 
        'gr_increment_id', 
        'goods_return_id', 
        'product_or_fabric_id', 
        'peices', 
        'meters', 
        'peices_meters', 
        'rate', 
        'discount_per', 
        'discount_amt', 
        'cgst_per', 
        'cgst_amt', 
        'sgst_per', 
        'sgst_amt', 
        'igst_per', 
        'igst_amt', 
        'amount', 
        'is_deleted',
    ];
}
