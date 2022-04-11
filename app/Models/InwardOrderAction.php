<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardOrderAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_order_id',
        'action_flag',
        'inward_id',
        'order_for',
        'product_or_fabric_id',
        'sub_product_id',
        'shade_no',
        'qty',
        'rate',
        'discount',
        'sale_bill_flag',
        'is_deleted',
    ];
}
