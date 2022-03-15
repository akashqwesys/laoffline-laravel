<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesBillAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'name',
        'is_defult',
    ];
}
