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
            $tcommission_payment_amount = 0; $ttds = 0; $tservice_tax= 0; $total = 0;$company = '';
        @endphp
        @if ($request->show_detail == 1)
        <thead>
        <tr>
            <th colspan="3" align="center" style="font-size: 20px;"><b >COMMISSION REGISTER REPORT</b></th>
        </tr>
        <tr>
            <th colspan="3" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>

            <tr>
                <th>No</th>
                <th>Supplier</th>
                <th class="text-right">Total</th>
            </tr>
        
            @foreach ($data as $i=>$d)
            @php
                $total += floatval($d->total);
            @endphp
            <tr>
                <td class=""> {{ ++$i }} </td>
                <td class=""> {{ $d->supplier_name }} </td>
                <td class="text-right"> {{ $d->total }} </td>
            </tr>
            @endforeach
            <tr>
                <th class=""> </th>
                <th class="text-right"> Total </th>
                <th class="text-right"> {{ $total }} </th>
            </tr>
        </tbody>
        @else
        <thead>
        <tr>
            <th colspan="12" align="center" style="font-size: 20px;"><b >COMMISSION REGISTER REPORT</b></th>
        </tr>
        <tr>
            <th colspan="12" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
        </thead>
        <tbody>
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
                    <th>Amount</th>
                    <th>TDS</th>
                    <th>S.T</th>
                </tr>
                @foreach ($data as $d)
                @php
                        $commission_payment_amount = $d->commission_payment_amount;
                        
                        if (!$commission_payment_amount) {
                            $commission_payment_amount = 0;
                        }

                        $tds = $d->tds;
                        
                        if (!$tds) {
                            $tds = 0;
                        }
                        
                        $service_tax = $d->service_tax;
                        
                        if (!$service_tax) {
                            $service_tax = 0;
                        }
                        
                        $tcommission_payment_amount += floatval($commission_payment_amount);
                        $ttds += floatval($tds);
                        $tservice_tax += floatval($service_tax);
                        
                        if ($d->customer_id == 0) {
                            $company = $d->supplier_name;
                        } else if ($d->supplier_id == 0) {
                            $company = $d->customer_name;
                        }

                        if ($d->commission_reciept_mode != 'cheque') {
                            $cheque_date = '-';
                            $cheque_dd_no = '-';
                            $cheque_bank = '-';
                            $bank_name = '-';
                        } else {
                            $cheque_date = $d->commission_cheque_date;
                            $cheque_dd_no = $d->commission_cheque_dd_no;
                            $cheque_bank = $d->commission_cheque_dd_bank;
                            $bank_name = $d->bank_name;
                        }
                    @endphp
                    <tr>
                        <td class=""> {{ $d->commission_id }} </td>
                        <td class=""> {{ $company }} </td>
                        <td class=""> {{ $d->commission_date }} </td>
                        <td class=""> {{ $d->agent }} </td>
                        <td class=""> {{ $d->commission_reciept_mode }} </td>
                        <td class=""> {{ $bank_name }} </td>
                        <td class=""> {{ $cheque_date }} </td>
                        <td class=""> {{ $cheque_dd_no }} </td>
                        <td class=""> {{ $cheque_bank }} </td>
                        <td class=""> {{ $commission_payment_amount }} </td>
                        <td class=""> {{ $tds }} </td>
                        <td class=""> {{ $service_tax }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                    </tr>
                    <tr>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> Total </th>
                        <th class=""> {{ $tcommission_payment_amount }} </th>
                        <th class=""> {{ $ttds }}</th>
                        <th class=""> {{ $tservice_tax }}</th>
                    </tr>
                    </tbody>
                @endif

</table>
