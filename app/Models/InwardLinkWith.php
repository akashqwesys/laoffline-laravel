<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InwardLinkWith extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_delete'
    ];
}
