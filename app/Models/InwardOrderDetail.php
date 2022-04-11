<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'order_for',
        'packing_id',
        'packing_date',
        'lump',
        'cut',
        'top',
        'bottom',
        'duppatta',
        'is_deleted',
    ];
}
