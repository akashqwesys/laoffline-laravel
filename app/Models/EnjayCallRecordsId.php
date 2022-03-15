<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnjayCallRecordsId extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'reference_id',
        'asteriskhost',
        'event',
        'direction',
        'number',
        'extension',
        'redirectchannel',
        'uniqueid',
        'starttime',
        'answertime',
        'endtime',
        'duration',
        'billableseconds',
        'disposition',
        'recordlink',
        'enjay_flag',
    ];
}
