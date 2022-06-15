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
        $total_pieces = $total_meters = $net_total = $gross_total = $rec_total = $gross_amount = $i = 0;
    @endphp
    @if ($request->show_detail == 1)
    <thead>
        <tr>
            <th colspan="4" align="center" style="font-size: 20px;"><b >SALES REGISTER REPORT</b></th>
        </tr>
        <tr>
            <th colspan="4" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
        <tr>
            <th><b>No</b></th>
            <th><b>Party</b></th>
            <th align="right"><b>Net Amount</b></th>
            <th align="right"><b>Received Amount</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $net_total += floatval($d->total);
            $rec_total += floatval($d->received_payment);
        @endphp
        <tr>
            <td align="left"> {{ ++$i }} </td>
            <td class=""> {{ $d->company_name }} </td>
            <td align="right"> {{ number_format($d->total) }}</td>
            <td align="right"> {{ number_format($d->received_payment) }}</td>
        </tr>
        @endforeach
        <tr>
            <th class="">  </th>
            <th class=""> </th>
            <th align="right"></th>
            <th align="right"></th>
        </tr>
        <tr>
            <th class=""> <b>Total</b> </th>
            <th class=""> </th>
            <th align="right"><b> {{ number_format($net_total) }} </b></th>
            <th align="right"><b> {{ number_format($rec_total) }} </b></th>
        </tr>
    </tbody>
    @else
    <thead>
        <tr>
            <th colspan="11" align="center" style="font-size: 20px;"><b >SALES REGISTER REPORT</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="11" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th><b>Date</b></th>
            <th><b>Serial</b></th>
            <th><b>Party</b></th>
            <th align="right"><b>Pieces</b></th>
            <th align="right"><b>Meters</b></th>
            <th align="right"><b>Net Amount</b></th>
            <th><b>Agent</b></th>
            <th><b>Invoice</b></th>
            <th align="right"><b>Gross Amount</b></th>
            <th><b>Transport</b></th>
            <th><b>City</b></th>
            <th><b>L. R. No.</b></th>
            <th><b>Purchase Party</b></th>
            <th align="right"><b>GST</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $total_pieces += floatval($d->tot_pieces);
            $total_meters += floatval($d->tot_meters);
            $net_total += floatval($d->total);
            if ($d->sign_change == '+') {
                $gross_amount = (floatval($d->total) - floatval($d->change_in_amount));
            } else {
                $gross_amount = (floatval($d->total) + floatval($d->change_in_amount));
            }
            $gross_total += $gross_amount;
        @endphp
        <tr>
            <td class=""> {{ $d->select_date }} </td>
            <td align="left"> {{ $d->sale_bill_id }} </td>
            <td class=""> {{ $d->customer_name }} </td>
            <td align="right"> {{ $d->tot_pieces }} </td>
            <td align="right"> {{ $d->tot_meters }} </td>
            <td align="right"> {{ number_format($d->total) }} </td>
            <td class=""> {{ $d->agent_name }} </td>
            <td align="left"> {{ $d->supplier_invoice_no }} </td>
            <td align="right"> {{ number_format($gross_amount) }} </td>
            <td class=""> {{ $d->transport_name }} </td>
            <td class=""> {{ $d->city_name }} </td>
            <td align="left"> {{ $d->lr_mr_no }} </td>
            <td class=""> {{ $d->supplier_name }} </td>
            <td align="right"> {{ $d->total_gst }} </td>
        </tr>
        @endforeach
        <tr>
            <th class="">  </th>
            <th class=""> </th>
            <th class=""> </th>
            <th class=""> </th>
            <th class=""> </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class=""> </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
        </tr>
        <tr>
            <th class=""> </th>
            <th class=""> </th>
            <th class=""> <b>Total</b></th>
            <th align="right"> <b>{{ $total_pieces }} </b></th>
            <th align="right"> <b>{{ $total_meters }} </b></th>
            <th align="right"> <b>{{ number_format($net_total) }} </b></th>
            <th class="">  </th>
            <th class="">  </th>
            <th align="right"> <b>{{ number_format($gross_total) }} </b></th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
        </tr>
    </tbody>
    @endif
</table>
