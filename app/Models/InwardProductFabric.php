<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardProductFabric extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'product_or_fabric_id',
        'product_or_fabric_flag'
    ];
}
