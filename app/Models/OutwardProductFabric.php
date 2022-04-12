<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutwardProductFabric extends Model
{
    use HasFactory;

    protected $fillable = [
        'outward_id',
        'product_or_fabric_id',
        'product_or_fabric_flag',
        'is_deleted'
    ];
}
