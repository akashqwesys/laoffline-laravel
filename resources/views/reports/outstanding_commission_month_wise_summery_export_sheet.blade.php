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
            @php 
            if ($request->show_detail == 1) {
            @endphp
                <th colspan="4" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION MONTH WISE SUMMERY REPORT - {{ $request->report_type }}</b></th>
            @php
            } else {
            @endphp
            <th colspan="4" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION MONTH WISE SUMMERY REPORT - {{ $request->report_type }}</b></th> 
            @php
            }
            @endphp
        </tr>
        <tr>
        @php 
            if ($request->show_detail == 1) {
            @endphp
            <th colspan="4" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            @php
            } else {
            @endphp
            <th colspan="4" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            @php
            }
            @endphp
            
        </tr>
    </thead>
    <tbody>
            @php
                $sup = ''; $sup1 = '';
                

                if ($request->supplier != '') {
                    if($data['sup_disp_name']) {
                        $sup .= "Supplier: " .$data['sup_disp_name'];
                    } else {
                        $sup .= "Supplier: " .$data['sup_disp_name'];
                    }
                } else {
                    $sup .= "All Parties";
                }
                
                if ($request->customer != '') {
                    if($data['cus_disp_name']) {
                        $sup1 .= "Customer: " .$data['cust_disp_name'];
                    } else {
                        $sup1 .= "Customer: " .$data['cust_disp_name'];
                    }
                } else {
                    $sup1 .= "All Parties";
                }
                $html = '';
                if ($request->report_type == 'supplier'){
            @endphp
                <tr>
                    <th colspan="4" align="center">{{ $sup }}</th>
                </tr>
            @php
                } else {
            @endphp
                <tr>
                    <th colspan="4" align="center">{{ $sup1 }}</th>
                </tr>
            @php
                }
                $grandtotal = $grandcommissiontoal = 0;
                if ($request->show_detail == 1) {
            @endphp
            @foreach ($data['finaldata'] as $key => $row)
                    <tr>
                        <td align="center" colspan="4"></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="4"><b>{{ $key }}</b></td>
                    </tr>
                    <tr>
                        <th><b>Sr No</b></th>
                        <th><b>Party Name</b></th>
                        <th><b>Amount</b></th>
                        <th><b>Commission Amount</b></th>
                    </tr>
            @php
                $totalmonthamount = $totalmonthcommission = $i= 0;
            @endphp         
                

            @foreach ($row as $key1 => $row1)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $key1 }}</td>
                
                @php
                    $i = 0;
                    $totalamount = $commissionamount = 0;
                @endphp
                @foreach ($row1 as $key2 => $row2)
                @php
                    $commission_amount = round($row2->receipt_amount * $row2->commission_percentage / 100);
                    $totalamount += $row2->receipt_amount;
                    $commissionamount += $commission_amount;
                @endphp
                @endforeach
                @php
                    $totalmonthamount += $totalamount;
                    $totalmonthcommission += $commissionamount;    
                @endphp
                
                    <td>{{ $totalamount }}</td>
                    <td>{{ $commissionamount }}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="2"><b>Montly Total</b></td>
                    <td><b>{{ $totalmonthamount }}</b></td>
                    <td><b>{{ $totalmonthcommission }}</b></td>
                    
                </tr>
                @php
                    $grandtotal += $totalmonthamount;
                    $grandcommissiontoal += $totalmonthcommission;
                @endphp
            @endforeach
            @php
                if (empty($data['finaldata'])) {
            @endphp
                    <tr>
                        <td colspan="4">Record Not Found</td>
                    </tr>
                
            @php 
                } else {
            @endphp
                <tr>
                    <td colspan="2"><b>Grand Total</b></td>
                    <td><b>{{ $grandtotal }}</b></td>
                    <td><b>{{ $grandcommissiontoal }}</b></td>    
                </tr>    
            @php 
                }
            
            } else {
            @endphp
            @foreach ($data['finaldata'] as $key => $row)
                    <tr>
                        <td align="center" colspan="4"></td>
                    </tr>
                    <tr>
                        <td align="center" colspan="4"><b>{{ $key }}</b></td>
                    </tr>
                    <tr>
                        <th><b>Sr No</b></th>
                        <th><b>Amount</b></th>
                        <th><b>Commission Amount</b></th>
            @php
            $totalmonthamount = $totalmonthcommission = 0;
                if ($request->report_type == 'supplier') {
            @endphp
                <th><b>Customer</b></th>
            @php
            } else {
            @endphp
                <th><b>Supplier</b></th>
            @php
            }
            @endphp         
                </tr>

            @foreach ($row as $key1 => $row1)
                <tr>
                    <td align="center" colspan="4"></td>
                </tr>
                <tr>
                    <td colspan = "2"><b>{{ $key1 }}</b></td>
                    <td colspan = "2"><b>{{ $row1[0]->company_address }}</b></td>
                </tr>
                @php
                    $i = 0;
                    $totalamount = $commissionamount = 0;
                @endphp
                @foreach ($row1 as $key2 => $row2)
                @php
                    $commission_amount = round($row2->receipt_amount * $row2->commission_percentage / 100);
                    $totalamount += $row2->receipt_amount;
                    $commissionamount += $commission_amount;
                @endphp
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $row2->receipt_amount }}</td>
                    <td>{{ $commission_amount }}</td>
                    @php
                        if ($request->report_type == 'supplier') {
                    @endphp
                        <td>{{ $row2->customer_name }}</td>
                    @php
                        } else {
                    @endphp
                        <td>{{ $row2->supplier_name }}</td>
                    @php
                        }
                    @endphp
                </tr>
                @endforeach
                @php
                    $totalmonthamount += $totalamount;
                    $totalmonthcommission += $commissionamount;    
                @endphp
                <tr>
                    <td><b>Party Total</b></td>
                    <td><b>{{ $totalamount }}</b></td>
                    <td><b>{{ $commissionamount }}</b></td>
                    <td></td>
                </tr>
            @endforeach
                <tr>
                    <td><b>Montly Total</b></td>
                    <td><b>{{ $totalmonthamount }}</b></td>
                    <td><b>{{ $totalmonthcommission }}</b></td>
                    <td></td>
                </tr>
                @php
                    $grandtotal += $totalmonthamount;
                    $grandcommissiontoal += $totalmonthcommission;
                @endphp
            @endforeach
            @php
                if (empty($data['finaldata'])) {
            @endphp
                    <tr>
                        <td colspan="4">Record Not Found</td>
                    </tr>
                
            @php 
                } else {
            @endphp
                <tr>
                    <td><b>Grand Total</b></td>
                    <td><b>{{ $grandtotal }}</b></td>
                    <td><b>{{ $grandcommissiontoal }}</b></td>
                    <td></td>
                </tr>    
            @php 
                } 
                }
            @endphp
                              
            </tbody>
</table>

