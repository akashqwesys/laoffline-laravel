<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'inward_id',
        'name',
        'image',
        'price',
        'qty',
        'meters',
        'is_deleted',
    ];
}
