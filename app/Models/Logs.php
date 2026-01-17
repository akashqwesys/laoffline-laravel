<?php

namespace App\Models;

use App\Traits\GeneratesAutoId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory, GeneratesAutoId;

    protected $fillable = [
        'log_path',
        'log_subject',
        'employee_id',
        'log_url',
    ];

}
