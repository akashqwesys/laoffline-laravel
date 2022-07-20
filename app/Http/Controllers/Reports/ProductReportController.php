<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\FinancialYear;
use App\Models\Logs;
use App\Models\Employee;
use Illuminate\Support\Facades\Session;
use DB;

class ProductReportController extends Controller
{
    public function productReport(Request $request)
    {
        $page_title = 'Product Report';
        $user = Session::get('user');
        $employees = Employee::join('users', 'employees.id', '=', 'users.employee_id')
            ->join('user_groups', 'employees.user_group', '=', 'user_groups.id')
            ->where('employees.id', $user->employee_id)
            ->first();

        $logsLastId = Logs::orderBy('id', 'DESC')->first('id');
        $logsId = !empty($logsLastId) ? $logsLastId->id + 1 : 1;

        $logs = new Logs;
        $logs->id = $logsId;
        $logs->employee_id = Session::get('user')->employee_id;
        $logs->log_path = 'Product Report / View';
        $logs->log_subject = 'Product Report view page visited.';
        $logs->log_url = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $logs->save();

        return view('reports.product_report', compact('page_title', 'employees'));
    }

    public function listproductReport(Request $request)
    {
        $user = Session::get('user');
        $sql = '';
        if ($request->product_name != '') {
            $sql .= " and p.name like '%" . $request->product_name . "%'";
        }
        if ($request->start_date && $request->end_date) {
            $sql .= " and (s.select_date >= '" . $request->start_date . "' and s.select_date <= '" . $request->end_date . "')";
        }
        if ($request->customer && $request->customer['id']) {
            $sql .= ' and p.company_id = ' . $request->customer['id'];
        }
        if ($request->supplier && $request->supplier['id']) {
            $sql .= ' and p.supplier_id = ' . $request->supplier['id'];
        }
        $sort_by = "amount desc";
        if ($request->sort_by == 2) {
            $sort_by = "amount asc";
        } else if ($request->sort_by == 3) {
            $sort_by = "meters desc";
        } else if ($request->sort_by == 4) {
            $sort_by = "meters asc";
        } else if ($request->sort_by == 5) {
            $sort_by = "pieces desc";
        } else if ($request->sort_by == 6) {
            $sort_by = "pieces asc";
        }
        $data = DB::table('sale_bills as s')
            ->join('sale_bill_items as sbi', function ($j) {
                $j->on('s.sale_bill_id', '=', 'sbi.sale_bill_id')
                ->on('s.financial_year_id', '=', 'sbi.financial_year_id');
            });
        if ($request->sale_bill_for == 1) {
            $data = $data->join('products as p', 'sbi.product_or_fabric_id', '=', 'p.id');
            $p_name = 'p.product_name';
        } else {
            $p_name = 'p.name';
            $data = $data->join('product_categories as p', 'sbi.product_or_fabric_id', '=', 'p.id');
        }
        $data = $data->selectRaw('sum(sbi.meters) as meters, sum(sbi.pieces) as pieces, sum(sbi.amount) as amount, sbi.product_or_fabric_id, ' . $p_name . ' as name')
            ->whereRaw('s.is_deleted = 0 and sbi.is_deleted = 0 ' . $sql)
            ->groupByRaw('sbi.product_or_fabric_id, ' . $p_name)
            ->orderByRaw($sort_by)
            ->get();

        foreach ($data as $d) {
            $q = DB::table('sale_bills as s')
                ->join('sale_bill_items as sbi', function ($j) {
                    $j->on('s.sale_bill_id', '=', 'sbi.sale_bill_id')
                    ->on('s.financial_year_id', '=', 'sbi.financial_year_id');
                });
            if ($request->sale_bill_for == 1) {
                $q = $q->join('products as p', 'sbi.product_or_fabric_id', '=', 'p.id');
            } else {
                $q = $q->join('product_categories as p', 'sbi.product_or_fabric_id', '=', 'p.id');
            }
            $q = $q->selectRaw('sum(sbi.meters) as meters, sum(sbi.pieces) as pieces, sum(sbi.amount) as amount, sbi.product_or_fabric_id, s.supplier_id, c.company_name, ' . $p_name)
                ->join('companies as c', 's.supplier_id', '=', 'c.id')
                ->whereRaw('s.is_deleted = 0 and sbi.is_deleted = 0 and sbi.product_or_fabric_id = ' .$d->product_or_fabric_id . $sql)
                ->groupByRaw('s.supplier_id, c.company_name, sbi.product_or_fabric_id, ' . $p_name)
                ->orderByRaw($sort_by)
                ->get();
            $d->sub = $q;
        }

        return response()->json($data);
    }
}
