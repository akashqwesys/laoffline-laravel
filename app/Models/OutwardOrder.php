<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'outward_id',
        'product_or_fabric_id',
        'sub_product_id',
        'shade_no',
        'qty',
        'rate',
        'discount',
        'is_deleted'
    ];
}
