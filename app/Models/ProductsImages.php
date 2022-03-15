<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_code',
        'product_code',
        'image',
        'price',
        'sort_order',
    ];
}
