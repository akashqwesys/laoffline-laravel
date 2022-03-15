<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'profile_pic',
        'email_id',
        'mobile',
        'address',
        'user_group',
        'excel_access',
        'id_proof',
        'ref_full_name',
        'ref_pass_pic',
        'ref_mobile',
        'ref_address',
        'extension_port_id',
        'extension_port_password',
    ];
}
