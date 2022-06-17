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
    $receipt_amount = $total_amount = 0;
    @endphp
    <thead>
        <tr>
            <th colspan="11" align="center" style="font-size: 20px;"><b >PAYMENTS REGISTER REPORT</b></th>
            
        </tr>
        <tr>
            <th colspan="11" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            
        </tr>
        <tr>
            <th>Id</th>
            <th>Supplier</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Mode</th>
            <th>Dep.Bank</th>
            <th>Chq.Date</th>
            <th>Chq/DD No</th>
            <th>Chq/DD Bank</th>
            <th>Rec.Amt</th>
            <th>Tot.Amt</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
        @php
            $receipt_amount += floatval($d->receipt_amount);
            $total_amount += floatval($d->total_amount);
            if ($d->reciept_mode == 'cheque') {
                $cheque_date = $d->cheque_date;
                $cheque_dd_no = $d->cheque_dd_no;
                $cheque_bank = $d->cheque_bank;
            } else {
                $cheque_date = '';
                $cheque_dd_no = '';
                $cheque_bank = '';
            }
        @endphp
        <tr>
            <td>{{ $d->payment_id }}</td>
            <td>{{ $d->supplier_name }}</td>
            <td>{{ $d->customer_name }}</td>
            <td>{{ $d->date }}</td>
            <td>{{ $d->reciept_mode }}</td>
            <td>{{ $d->bank_name }}</td>
            <td>{{ $cheque_date }}</td>
            <td>{{ $cheque_dd_no }}</td>
            <td>{{ $cheque_bank }}</td>
            <td>{{ $d->receipt_amount }}</td>
            <td>{{ $d->total_amount }}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="11"></th>
            
        </tr>
        <tr>
            <th colspan="9">Total</th>
            <th>{{ $receipt_amount }}</th>
            <th>{{ $total_amount }}</th>
        </tr>
    </tbody>
    
</table>
