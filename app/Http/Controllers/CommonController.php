<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class CommonController extends Controller
{
    public function getCustomersAndSuppliers()
    {
        $customers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 2)
            ->orderBy('company_name', 'asc')
            ->get();
        $suppliers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 3)
            ->orderBy('company_name', 'asc')
            ->get();

        return response()->json([$customers, $suppliers]);
    }

    public function getAllCompanies()
    {
        $companies = DB::table('companies')->select('id', 'company_name as name')->orderBy('company_name', 'asc')->get();

        return response()->json($companies);
    }
}
