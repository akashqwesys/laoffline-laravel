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
            ->where('is_delete', 0)
            ->orderBy('company_name', 'asc')
            ->get();
        $suppliers = DB::table('companies')
            ->select('id', 'company_name as name')
            ->where('company_type', 3)
            ->where('is_delete', 0)
            ->orderBy('company_name', 'asc')
            ->get();

        return response()->json([$customers, $suppliers]);
    }

    public function getAllCompanies()
    {
        $companies = DB::table('companies')->select('id', 'company_name as name', 'company_type as type')->where('is_delete', 0)->orderBy('company_name', 'asc')->get();

        return response()->json($companies);
    }

    public function getAllAgents()
    {
        $agents = DB::table('agents')->select('id', 'name')->where('is_delete', 0)->orderBy('name', 'asc')->get();

        return response()->json($agents);
    }
}
