<?php

namespace App\Models\inwardOutward;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inwardActions extends Model
{
    use HasFactory;

    protected $fillable = [
            'inward_action_id',
            'iward_id',
            'action_date',
            'employe_id',
            'instruction',
            'status',
    ];
}
