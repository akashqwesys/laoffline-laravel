<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
    ];
}
