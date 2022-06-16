<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsRegisterExport implements /* FromCollection, WithHeadings, WithMapping, */ ShouldAutoSize, FromView
{
    protected $data, $request;

    public function __construct($data, $request)
    {
        $this->data = $data;
        $this->request = $request;
    }

    public function view(): View
    {
        return view('reports.payments_register_export_sheet', [ 'data' => $this->data, 'request' => $this->request ]);
    }

    /* public function map($data): array
    {
        // $total_pieces += floatval($data->tot_pieces);
        // $total_meters += floatval($data->tot_meters);
        // $net_total += floatval($data->total);
        if ($data->sign_change == '+') {
            $gross_amount = (floatval($data->total) - floatval($data->change_in_amount));
        } else {
            $gross_amount = (floatval($data->total) + floatval($data->change_in_amount));
        }
        // $gross_total += $gross_amount;

        return [
            $data->select_date,
            $data->sale_bill_id,
            $data->customer_name,
            $data->tot_pieces,
            $data->tot_meters,
            $data->total,
            $data->agent_name,
            $data->supplier_invoice_no,
            $gross_amount,
            $data->transport_name,
            $data->city_name,
            $data->lr_mr_no,
            $data->supplier_name,
            $data->total_gst,
        ];
    } */

    /**
    * @return \Illuminate\Support\Collection
    */
    /* public function collection()
    {
        $total_pieces = $total_meters = $net_total = $gross_total = $rec_total = $gross_amount = $i = 0;
        return collect($this->data);
    } */

    /* public function headings(): array
    {
        return [
            [ 'Sales Register Reports' ],
            [ date('d-m-Y', strtotime($this->request->start_date)) . ' - ' . date('d-m-Y', strtotime($this->request->end_date)) ],
            [
                'Date',
                'Serial',
                'Party',
                'Peices',
                'Meters',
                'Net Amount',
                'Agent',
                'Invoice',
                'Gross Amount',
                'Transport',
                'City',
                'L. R. No.',
                'Purchase Party',
                'GST'
            ]
        ];
    } */
}
