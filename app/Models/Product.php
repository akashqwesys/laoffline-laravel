<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'catalogue_name',
        'brand_name',
        'model',
        'launch_date',
        'company',
        'category',
        'sub_category',
        'main_image',
        'price_list_image',
        'description',
        'complete_flag',
        'generated_by',
        'updated_by',
    ];
}
