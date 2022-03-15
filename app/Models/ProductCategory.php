<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_default_category_id',
        'name',
        'main_category_id',
        'company_id',
        'product_fabric_id',
        'sort_order',
        'multiple_company',
        'rate',
    ];
}
