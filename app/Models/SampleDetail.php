<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'sample_details_id';
    protected $fillable = [
            'sample_id',
            'inward_sample_id',
            'inward_id',
            'name',
            'image',
            'price',
            'qty',
            'new_qty',
            'remaining_qty',
            'meters',
            'new_meters',
            'remaining_meters',
            'is_deleted',
    ];
}
