<?php

namespace App\Models\inwardOutward;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardActions extends Model
{
    use HasFactory;

    protected $primaryKey = 'inward_action_id';

    protected $fillable = [
        'inward_id',
        'action_date',
        'employee_id',
        'instruction',
        'status',
    ];
}
