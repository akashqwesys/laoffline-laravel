<?php

use Illuminate\Support\Facades\DB;

function getLastID ($table, $pid) {
    return intval(DB::table($table)->select($pid)->orderBy($pid, 'desc')->limit(1)->pluck($pid)->first());
}
