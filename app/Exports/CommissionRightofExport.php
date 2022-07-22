<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CommissionRightofExport implements /* FromCollection, WithHeadings, WithMapping, */ ShouldAutoSize, FromView
{
    protected $data, $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('reports.commission_rightof_export_sheet', [ 'data' => $this->data, 'request' => $this->request ]);
    }

}
