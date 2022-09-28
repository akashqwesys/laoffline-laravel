<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;
    protected $primaryKey = 'sample_id';
    protected $fillable = [
            'ouid',
            'reference_id',
            'inward_id',
            'company_id',
            'supplier_id',
            'reference_via',
            'sample_via',
            'courier_name',
            'courier_receipt_no',
            'courier_received_time',
            'weight_of_parcel',
            'delivery_by',
           'product_qty',
           'fabric_meters',
    ];
}
