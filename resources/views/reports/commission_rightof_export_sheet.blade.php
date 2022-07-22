<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
<table>
<table class="" width="">
    <thead>
    <tr>
            <th colspan="14" align="center" style="font-size: 20px;"><b >COMMISSION RIGHT OF REPORT</b></th>
        </tr>
        <tr>
            <th colspan="14" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Inv NO</td>
            <td>Inv Date</td>
            <td>Recipient</td>
            <td>Agent</td>
            <td>State</td>
            <td>Gross Amount</td>
            <td>CGST</td>
            <td>SGST</td>
            <td>IGST</td>
            <td>Total GST</td>
            <td>TDS</td>
            <td>Net Amount</td>
            <td>Right of Amt</td>
            <td>Remark</td>
        </tr>
        @php
            $total=0; $tot_cgst=0; $tot_sgst=0; $tot_igst=0; $tot_gst=0; $tot_tds=0; $tot_net=0; $tot_right=0;
            if (count($data['result']) != 0) {
        @endphp
        @foreach ($data['result'] as $key => $row)
        @php
            if ($row->supplier_id != 0) {
                $company = $row->supplier_name;
                $state = DB::table('states')->where('id', $row->supplier_state)->first()->name;
            } else {
                $company = $row->customer_name;
                $state = $state = DB::table('states')->where('id', $row->customer_state)->first()->name;;
            }
            $total_gst = 0;

            $total_gst = $row->cgst_amount + $row->cgst_amount + $row->igst_amount;
            $total += $row->commission_amount;
            $tot_gst += $total_gst;
            $tot_cgst += $row->cgst_amount;
            $tot_sgst += $row->sgst_amount;
            $tot_igst += $row->igst_amount;
            $tot_tds +=  $row->tds_amount;
            $tot_net +=  $row->final_amount;
            $tot_right +=  $row->right_of_amount;
        @endphp
                    <tr>
                        <td>{{ $row->bill_no }}</td>
                        <td>{{ $row->bill_date }}</td>
                        <td>{{ $company }}</td>
                        <td>{{ $row->agentname }}</td>
                        <td>{{ $state }}</td>
                        <td>{{ $row->commission_amount }}</td>
                        <td>{{ $row->cgst_amount }}</td>
                        <td>{{ $row->sgst_amount }}</td>
                        <td>{{ $row->igst_amount }}</td>
                        <td>{{ $total_gst }}</td>
                        <td>{{ $row->tds_amount }}</td>
                        <td>{{$row->final_amount }}</td>
                        <td>{{$row->right_of_amount }}</td>
                        <td>{{$row->right_of_remark}}</td>
                    </tr>
        @endforeach
                    <tr>
                        <td colspan="5"><b>Total</td>
                        <td><b>{{$total}}</b></td>
                        <td><b>{{$tot_cgst}}</b></td>
                        <td><b>{{$tot_sgst}}</b></td>
                        <td><b>{{$tot_igst}}</b></td>
                        <td><b>{{$tot_gst}}</b></td>
                        <td><b>{{$tot_tds}}</b></td>
                        <td><b>{{$tot_net}}</b></td>
                        <td><b>{{$tot_right}}</b></td>
                        <td></td>
                    </tr>
        @php
            } else {
        @endphp
            <tr colspan="14">Record Not found</tr>
        @php
        }
        @endphp             
    </tbody>
</table>

