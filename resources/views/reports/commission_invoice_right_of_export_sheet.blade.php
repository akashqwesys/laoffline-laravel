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
        $received_amount = $total_amount = $roa = 0;
    @endphp
    <thead>
        <tr>
            <th colspan="13" align="center" style="font-size: 20px;"><b>Commission Invoice Right of Report</b></th>
        </tr>
        <tr>
            <th colspan="13" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
        <tr>
            <th><b>ID</b></th>
            <th><b>Supplier</b></th>
            <th><b>Customer</b></th>
            <th><b>Date</b></th>
            <th><b>Mode</b></th>
            <th><b>Dep. Bank</b></th>
            <th><b>Chq. Date</b></th>
            <th><b>Chq/DD No</b></th>
            <th><b>Chq/DD Bank</b></th>
            <th align="right"><b>Rec. Amount</b></th>
            <th align="right"><b>Tot. Amount</b></th>
            <th align="right"><b>Right of Amt</b></th>
            <th><b>Right of Remark</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $received_amount += floatval($d->receipt_amount);
            $total_amount += floatval($d->total_amount);
            $roa += floatval($d->right_of_amount);
        @endphp
        <tr>
            <td class=""> {{ $d->payment_id }} </td>
            <td> {{ $d->supplier_name }} </td>
            <td> {{ $d->customer_name }} </td>
            <td> {{ $d->date2 }} </td>
            <td> {{ $d->reciept_mode }} </td>
            <td> {{ $d->reciept_mode != "full return" ? $d->deposit_bank : '' }} </td>
            <td> {{ $d->reciept_mode != "full return" ? $d->cheque_date2 : '' }} </td>
            <td> {{ $d->reciept_mode == "cheque" ? $d->cheque_dd_no : '' }} </td>
            <td> {{ $d->bank_name }} </td>
            <td align="right"> {{ $d->reciept_mode != "full return" ? number_format($d->receipt_amount) : '' }} </td>
            <td align="right"> {{ number_format($d->total_amount) }} </td>
            <td align="right"> {{ number_format($d->right_of_amount) }} </td>
            <td> {{ $d->right_of_remark }} </td>
        </tr>
        @endforeach
        <tr>
            <th colspan="9" align="right"> <b>Total</b></th>
            <th align="right"><b> {{ number_format($received_amount) }} </b></th>
            <th align="right"><b> {{ number_format($total_amount) }} </b></th>
            <th align="right"><b> {{ number_format($roa) }} </b></th>
            <th > </th>
        </tr>
    </tbody>
</table>
