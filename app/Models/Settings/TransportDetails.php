<?php

namespace App\Models\settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gstin',
    ];
}
