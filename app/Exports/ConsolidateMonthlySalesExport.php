<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ConsolidateMonthlySalesExport implements ShouldAutoSize, FromView
{
    protected $data, $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('reports.consolidate_monthly_sales_export_sheet', ['data' => $this->data, 'request' => $this->request]);
    }

}
