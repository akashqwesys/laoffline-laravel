<?php

namespace App\Models\systemModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class systemModules extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_module_id',
        'name'
    ];
}
