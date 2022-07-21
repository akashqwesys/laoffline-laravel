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
            <th colspan="3" align="center" style="font-size: 20px;"><b >OUTSTANDING PAYMENT MONTH WISE SUMMERY REPORT</b></th>
        </tr>
        <tr>
            <th colspan="3" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            
            $morethan = '';
            $sup = '';
            $agent = '';
        
        
        if ($request->agent == '') {
                $agent .= 'All Agents';  
        } else {
                $agent .= $request->agent['name'];
        }   

        if ($request->customer != '') {
                $sup .= 'Customer: '.$data['cus_disp_name'] . '<br>';
        }

        if ($request->supplier != '') { 
            if($data['sup_disp_name']) {
                $sup .= 'Supplier: ' .$data['sup_disp_name'] . '<br>';
            } else {
                 $sup .= 'Supplier: ' . $request->supplier->company_name . '<br>';
            }
        }

        if($request->customer == '' && $request->supplier == '') {
            $sup .= 'All Parties';
        }
        $sup .= $morethan;
        @endphp
        <tr width="100%">
            <th colspan="3" align="center">{{ $sup }}</th>
        </tr>
        <tr width="100%">
            <td colspan="3" align="center">{{ $agent }}</td>
        </tr>
        @php
        if ($request->start_date != '' && $request->end_date != '') {
            $gtotal = 0;
        @endphp
            @foreach ($data['finaldata'] as $keys => $row)
                <tr>
                    <td colspan="3" align="center" style="height:35px"></td>
                </tr>
                <tr>
                    <td colspan="3" align="center"><b style="font-size: 20px">{{ $keys }}</b></td>
                </tr>
                @php
                if ($request->show_detail == 1) {
                @endphp
                            <tr>
                                <th>No.</th>
                                <th>Customer</th>
                                <th align="right">Party Total</th>
                            </tr>
                @php
                }
                $mtotal = 0;
                $i = 0;
                @endphp
                @foreach ($row as $key1 =>$row1)
                @php
                    if ($request->show_detail == 0) {
                @endphp
                        <tr>
                            <td colspan="3" align="center" style="height:35px"></td>
                        </tr>
                        <tr>
                            <td colspan="1"><b>{{ $key1 }}</b></td>
                            <td colspan="2"><b>{{ $row1[0]->company_address }}</b></td>
                            </tr>
                            <tr>
                                <th>Sr.</th>
                                <th>Purchase Party</th>
                                <th align="right">Bill Amount</th>
                            </tr>
                @php
                    } else {
                @endphp
                        <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $key1 }}</td>
                @php
                    }
                    $ptotal = 0;
                @endphp
                    @foreach ($row1 as $key2 =>$row2)
                @php
                        if($row2->pending_payment == 0) {
                            $final_amount=$row2->total;
                        } else {
                            $final_amount=$row2->pending_payment;
                        }
                        $ptotal += $final_amount;
                        if ($request->show_detail == 0) {
                @endphp
                        <tr>
                            <td align="left">{{ $row2->sale_bill_id }}</td>
                            <td>{{ $row2->supplier_name }}</td>
                            <td align="right">{{ $final_amount }}</td>
                        </tr>
                @php   
                        } 
                @endphp
                @endforeach
                @php
                    if ($request->show_detail == 0) {
                @endphp
                        <tr>
                            <td><b>Party Total</b></td>
                            <td align="right" colspan="2"><b>{{ $ptotal }}</b></td>
                        </tr>
                @php
                    } else {
                @endphp
                        <td align="right">{{ $ptotal }}</td>
                            </tr>
                @php
                    }
                    $mtotal += $ptotal;
                @endphp
                @endforeach
                        <tr>
                            <td><b>Montly Total</b></td>
                            <td align="right" colspan="2"><b>{{ $mtotal }}</b></td>
                        </tr>
                $gtotal += $mtotal; 
            @endforeach
                        <tr>
                            <td><b>Grand Total</b></td>
                            <td align="right" colspan="2"><b>{{ $gtotal }}</b></td>
                        </tr>
        @php    
        }
        @endphp
        </tbody>
</table>
