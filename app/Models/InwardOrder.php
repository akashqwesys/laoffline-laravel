<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'order_for',
        'product_or_fabric_id',
        'sub_product_id',
        'shade_no',
        'qty',
        'rate',
        'discount',
        'packing_id',
        'packing_date',
        'lump',
        'cut',
        'top',
        'bottom',
        'duppatta',
        'sale_bill_flag',
        'is_deleted',
    ];
}
