<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFabricDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'saree_fabric',
        'saree_cut',
        'blouse_fabric',
        'blouse_cut',
        'top_fabric',
        'top_cut',
        'bottom_fabric',
        'bottom_cut',
        'dupatta_fabric',
        'dupatta_cut',
        'inner_fabric',
        'inner_cut',
        'sleeves_fabric',
        'sleeves_cut',
        'choli_fabric',
        'choli_cut',
        'lehenga_fabric',
        'lehenga_cut',
        'lining_fabric',
        'lining_cut',
        'gown_fabric',
        'gown_cut',
    ];
}
