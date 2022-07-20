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
            <th colspan="6" align="center" style="font-size: 20px;"><b >OUTSTANDING PAYMENT REPORT</b></th>
        </tr>
        <tr>
            <th colspan="6" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $morethan = '';
            $sup = '';
            $agent = '';
        
        if ($request->day != '' && $request->day['report_days'] != 0) { 
                $morethan .= '( More then '. $request->day['report_days'] .'Days)';
        } else {
                $morethan .= '';
        }   
        
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
            <th colspan="6" align="center">{{ $sup }}</th>
        </tr>
        <tr width="100%">
            <td colspan="6" align="center">{{ $agent }}</td>
        </tr>
        @if  ($request->show_detail == 0)
        <tr width="100%">
            <th>Bill Date</th>
            <th>Sr.</th>
            <th>Bill Amount</th>
            <th>Days</th>
            <th>Purchase Party</th>
            <th>Bill No</th>
        </tr>
        @else 
        <tr width="100%">
            <th>No.</th>
            <th>Name</th>
            <th colspan="4">Party Amount</th>
        </tr>    
        @endif

        @php
            $report_days = $request->day ? $request->day['report_days'] : 0;
            $grand_total = 0;$i = 0;
            if (!empty($data['company_data'])) {
        @endphp
        @foreach ($data['company_data'] as $row)
        @php    
            $ptotal = 0;
            if ($request->show_detail == 1) {
        @endphp
                <tr width="100%">
                    <td>{{ ++$i }}</td>
                    <td>{{ $row[0]->customer_name }}</td>
                    @foreach($row as $key1 => $row2)
        @php
                        $startTimeStamp = strtotime($row2->select_date);
			            $endTimeStamp = strtotime(date('Y-m-d'));
                        $timeDiff = abs($endTimeStamp - $startTimeStamp);
                        $numberDays = $timeDiff/86400;
                        $numberDays = intval($numberDays);

                        if ($numberDays >= $report_days){
                            if($row2->pending_payment == 0) {
                                $final_amount=$row2->total;
                            } else {
                                $final_amount=$row2->pending_payment;
                            }
                            $ptotal += $final_amount;
                        }
        @endphp
        @endforeach
        @php
                } else {
        @endphp
                <tr width="100%">
                        <td colspan="6" class="text-center" style="height:35px"></td>
                </tr>
                <tr width="100%">
                            <td colspan="2"><b>{{ $row[0]->customer_name }}</b></td>
                            <td colspan="4"><b>{{ $row[0]->company_address }}</b></td>
                </tr>
            
            
            @foreach($row as $key1 => $row2){
        @php
                $startTimeStamp = strtotime($row2->select_date);
			    $endTimeStamp = strtotime(date('Y-m-d'));
			    $timeDiff = abs($endTimeStamp - $startTimeStamp);
			    $numberDays = $timeDiff/86400;
			    $numberDays = intval($numberDays);

                if ($numberDays >= $report_days){
                    if ($numberDays >= 90) {
                        $tr_color="style='color:red'";
                    }
        @endphp
                    <tr width="100%" {{ $tr_color }}>
                                <td>{{ $row2->select_date }} </td>
                                <td>{{ $row2->sale_bill_id }}</td>
        @php
                            if($row2->pending_payment == 0) {
                                $final_amount=$row2->total;
                            } else {
                                $final_amount=$row2->pending_payment;
                            }
        @endphp
                            <td>{{ $final_amount }}</td>
                            <td>{{ $numberDays }}</td>
                            <td>{{ $row2->supplier_name }}</td>
                            <td>{{ $row2->supplier_invoice_no }}</td>
                    </tr>
        @php
                            $ptotal += $final_amount; 
                }
        @endphp
        @endforeach
        @php
            }
            if ($request->show_detail == 0) {
        @endphp
            <tr width="100%">
                <td><b>Party Total</b></td>
                <td></td>
                <td><b>{{ $ptotal }}</b></td>
                <td colspan="3"></td>
            </tr>
        @php
            } else {
        @endphp
                <td colspan="4">{{ $ptotal }}</td></tr>
        @php 
            }
            $grand_total += $ptotal;
        @endphp
        @endforeach
            
        @php
        if ($request->show_detail == 0) {
        @endphp
                    <tr width="100%">
                        <td colspan="2"><b>Grand Total</b></td>
                        <td><b>{{ $grand_total }}</b></td>
                        <td colspan="3"></td>
                    </tr>
        @php
            } else {
        @endphp
                <tr width="100%">
                    <td colspan="2"><b>Grand Total</b></td>
                    <td colspan="4"><b>{{ $grand_total }}</b></td>
                </tr>
        @php
            }
        } else {
        @endphp
            <tr width="100%">
                <td class="text-center" colspan="6" style="height: 50px;">Record Not Found</td>
            </tr>
            @php
            }
            @endphp
        
    </tbody>
</table>
