<?php

namespace App\Models\Commission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commissionFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'commission_followup_id',
        'date_added',
    ];
}
