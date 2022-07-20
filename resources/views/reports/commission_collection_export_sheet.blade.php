<style>
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
</style>
<table class="" width="">
        <thead>
        <tr>
            <th colspan="3" align="center" style="font-size: 20px;"><b >COMMISSION COLLECTION REPORT</b></th>
        </tr>
        <tr>
            <th colspan="3" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>
    @php
        if (!empty($data['result'])) {
            
            $total = 0;
    @endphp
                <tr>
                    <th>Id</th>
                    <th>Company</th>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Mode</th>
                    <th>Dep.Bank</th>
                    <th>Chq.Date</th>
                    <th>Chq/DD No</th>
                    <th>Chq/DD Bank</th>
                    <th align="right">Amount</th>
                </tr>
            @foreach ($data['result'] as $row)
            @php
            if ($row->commission_reciept_mode == 'cash') {
               $commission_deposite_bank = '-';
               $commission_cheque_date = '-';
               $commission_cheque_dd_no = '-';
               $commission_cheque_dd_bank = '-';
            } else {
                $commission_deposite_bank = DB::table('bank_details')->where('id', $$row->commission_deposite_bank)->first()->name;
                $commission_cheque_dd_bank = DB::table('bank_details')->where('id', $$row->commission_cheque_dd_bank)->first()->name;
                $commission_cheque_date = $row->commission_cheque_date;
                $commission_cheque_dd_no = $row->commission_cheque_dd_no;
            }
            $commission_account = DB::table('agents')->where('id', $row->commission_account)->first()->name;
            $total += $row->commission_payment_amount;
            @endphp
                <tr>
                    <th>{{ $row->commission_id}}</th>
                    <th>{{ $row->company_name }}</th>
                    <th>{{ $row->commission_date }}</th>
                    <th>{{ $commission_account }}</th>
                    <th>{{ $row->commission_reciept_mode }}</th>
                    <th>{{ $commission_deposite_bank}}</th>
                    <th>{{ $commission_cheque_date }}</th>
                    <th>{{ $commission_cheque_dd_no }}</th>
                    <th>{{ $commission_cheque_dd_bank }}</th>
                    <th align="right">{{ $row->commission_payment_amount }}</th>
                </tr>
            @endforeach
            <tr>
                <td colspan="9"><b>Total</b></td>
                <td align="right"><b>{{ $total }}</b></td>
            </tr>
        @php
        } else {
        @endphp
            <tr>
                <td colspan=3>No Data Found<td>
            </tr>
        @php
            }
        @endphp
    </tbody>
</table>
