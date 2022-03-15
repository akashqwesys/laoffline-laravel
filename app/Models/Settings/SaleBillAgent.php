<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleBillAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default',
    ];
}
