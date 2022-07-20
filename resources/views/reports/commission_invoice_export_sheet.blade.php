<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
<table class="" width="">
    @php
        $gross_amount = $cgst = $sgst = $igst = $total_gst = $tds = $net_amount = $row_gst = 0;
    @endphp
    <thead>
        <tr>
            <th colspan="12" align="center" style="font-size: 20px;"><b>Commission Invoice Report</b></th>
        </tr>
        <tr>
            <th colspan="12" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
        <tr>
            <th> <b>Invoice No </b></th>
            <th> <b>Invoice Date </b></th>
            <th> <b>Company </b></th>
            <th> <b>Agent </b></th>
            <th> <b>State </b></th>
            <th align="right"><b> Gross Amount </b></th>
            <th align="right"><b> CGST </b></th>
            <th align="right"><b> SGST </b></th>
            <th align="right"><b> IGST </b></th>
            <th align="right"><b> Total GST </b></th>
            <th align="right"><b> TDS </b></th>
            <th align="right"><b> Net Amount </b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $gross_amount += floatval($d->commission_amount);
            $sgst += floatval($d->sgst_amount);
            $cgst += floatval($d->cgst_amount);
            $igst += floatval($d->igst_amount);
            $row_gst = floatval($d->cgst_amount) + floatval($d->sgst_amount) + floatval($d->igst_amount);
            $total_gst += $row_gst;
            $tds += floatval($d->tds_amount);
            $net_amount += floatval($d->final_amount);
        @endphp
        <tr>
            <td class=""> {{ $d->bill_no }} </td>
            <td> {{ $d->bill_date2 }} </td>
            <td> {{ $d->supplier_name ? $d->supplier_name : $d->customer_name }} </td>
            <td> {{ $d->agent_name }} </td>
            <td> {{ $d->state_name }} </td>
            <td align="right"> {{ number_format($d->commission_amount) }} </td>
            <td align="right"> {{ number_format($d->cgst_amount, 2) }} </td>
            <td align="right"> {{ number_format($d->sgst_amount, 2) }} </td>
            <td align="right"> {{ number_format($d->igst_amount, 2) }} </td>
            <td align="right"> {{ number_format($row_gst, 2) }} </td>
            <td align="right"> {{ number_format($d->tds_amount, 2) }} </td>
            <td align="right"> {{ number_format($d->final_amount) }} </td>
        </tr>
        @endforeach
        <tr>
            <th colspan="5"> <b> Total</b></th>
            <th align="right"><b> {{ number_format($gross_amount) }} </b></th>
            <th align="right"><b> {{ number_format($cgst) }} </b></th>
            <th align="right"><b> {{ number_format($sgst) }} </b></th>
            <th align="right"><b> {{ number_format($igst) }} </b></th>
            <th align="right"><b> {{ number_format($total_gst) }} </b></th>
            <th align="right"><b> {{ number_format($tds) }} </b></th>
            <th align="right"><b> {{ number_format($net_amount) }} </b></th>
        </tr>
    </tbody>
</table>
