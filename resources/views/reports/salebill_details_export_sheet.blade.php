<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
@php
    $total_pieces = $total_meters = $net_total = $gross_total = $gross_amount = $i = 0;
    $sub_total_pieces = $sub_total_meters = $sub_total_amount = 0;
    $sub_html = '';
@endphp
<table class="" width="">
    <thead>
        <tr>
            <th colspan="13" align="center" style="font-size: 20px;"><b >Salebill Details Report</b></th>
        </tr>
        <tr>
            <th colspan="13" align="center"><b>{{ date('d-m-Y', strtotime($request['selected_date'])) }}</b></th>
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
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $total_pieces += floatval($d->pieces);
            $total_meters += floatval($d->meters);
            $net_total += floatval($d->total);
            if ($d->sign_change == '+') {
                $gross_amount = (floatval($d->total) - floatval($d->change_in_amount));
            } else {
                $gross_amount = (floatval($d->total) + floatval($d->change_in_amount));
            }
            $gross_total += $gross_amount;
            if ($i < (count($data) - 1) && $d->sale_bill_id == $data[$i+1]->sale_bill_id) {
                $sub_html .= '<tr><td></td><td></td>
                    <td>' . $d->product_name . '</td>
                    <td class="text-right">' . $d->pieces . '</td>
                    <td class="text-right">' . $d->meters . '</td>
                    <td class="text-right">' . $d->rate . '</td>
                    <td class="text-right">' . floatval($d->amount) . '</td>
                </tr>';
                $sub_total_pieces += floatval($d->pieces);
                $sub_total_meters += floatval($d->meters);
                $sub_total_amount += floatval($d->amount);
                continue;
            } else {
                $sub_total_pieces += floatval($d->pieces);
                $sub_total_meters += floatval($d->meters);
                $sub_total_amount += floatval($d->amount);
                $sub_html .= '<tr><td></td><td></td>
                    <td>' . $d->product_name . '</td>
                    <td class="text-right">' . $d->pieces . '</td>
                    <td class="text-right">' . $d->meters . '</td>
                    <td class="text-right">' . $d->rate . '</td>
                    <td class="text-right">' . floatval($d->amount) . '</td>
                </tr>';
            }
        @endphp
        <tr>
            <td class=""> {{ $d->select_date }} </td>
            <td align="left"> {{ $d->sale_bill_id }} </td>
            <td class=""> {{ $d->customer_name }} </td>
            <td align="right"> {{ number_format($sub_total_pieces) }} </td>
            <td align="right"> {{ number_format($sub_total_meters) }} </td>
            <td align="right"> {{ number_format($d->total) }} </td>
            <td class=""> {{ $d->agent_name }} </td>
            <td align="left"> {{ $d->supplier_invoice_no }} </td>
            <td align="right"> {{ number_format($gross_amount) }} </td>
            <td class=""> {{ $d->transport_name }} </td>
            <td class=""> {{ $d->city_name }} </td>
            <td align="left"> {{ $d->lr_mr_no }} </td>
            <td class=""> {{ $d->supplier_name }} </td>
        </tr>
        {{-- <tr>
            <td colspan="13">
                <table align="center">
                    <thead>
                        <tr>
                            <th><b>Name</b></th>
                            <th align="right"><b>Pieces</b></th>
                            <th align="right"><b>Meters</b></th>
                            <th align="right"><b>Rate</b></th>
                            <th align="right"><b>Amount</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $sub_html !!}
                        <tr>
                            <th>Total</th>
                            <th align="right">{{ $sub_total_pieces }}</th>
                            <th align="right">{{ $sub_total_meters }}</th>
                            <th align="right">Amount</th>
                            <th align="right">{{ floatval($sub_total_amount) }}</th>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr> --}}
        <tr>
            <th></th>
            <th></th>
            <th><b>Name</b></th>
            <th align="right"><b>Pieces</b></th>
            <th align="right"><b>Meters</b></th>
            <th align="right"><b>Rate</b></th>
            <th align="right"><b>Amount</b></th>
        </tr>
        {!! $sub_html !!}
        <tr>
            <th></th>
            <th></th>
            <th>Total</th>
            <th align="right">{{ $sub_total_pieces }}</th>
            <th align="right">{{ $sub_total_meters }}</th>
            <th align="right">Amount</th>
            <th align="right">{{ floatval($sub_total_amount) }}</th>
        </tr>
        <tr><td colspan="13"> </td></tr>
        @php
            $sub_html = '';
            $sub_total_pieces = $sub_total_meters = $sub_total_amount = 0;
            $i++;
        @endphp
        @endforeach
        <tr>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
            <th class="">  </th>
        </tr>
        <tr>
            <th class=""> </th>
            <th class=""> </th>
            <th class=""> <b>Total</b></th>
            <th align="right"> <b>{{ number_format($total_pieces) }} </b></th>
            <th align="right"> <b>{{ number_format($total_meters) }} </b></th>
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
</table>
