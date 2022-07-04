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
            <th colspan="3">Name</th>
            <th colspan="2">Party Amount</th>
        </tr>    
        @endif

        @php
            $gtotal = 0;$i = 1;
            if (!empty($data['company_data'])) {
        @endphp
        @foreach ($data['company_data'] as $row)
            @php
                if($row['total'] != 0) {
                    if ($request->show_detail == 1) {
            @endphp
                <tr width="100%">
                    <td>{{ $i++ }}</td>
                    <td colspan="3">{{ $row['name'] }}</td>
            @php
            $ptotal = 0;
                for($j=0; $j<((is_array($data[$row['company_id']]['srno'])) ? count($data[$row['company_id']]['srno']) : 0); $j++) {
                    $tr_color='';
                    $ptotal += $data[$row['company_id']]['amount'][$j]; 
                }
            @endphp
                    <td colspan="2">{{ $ptotal }}</td>
                </tr>
            @php
                    } else {
            @endphp
                <tr width="100%">
                    <td colspan="6" class="text-center" style="height:35px"></td>
                </tr>
                <tr width="100%">
                    <td colspan="2">{{ $row['name'] }}</td>
                    <td colspan="4">{{ $row['address'] }}</td>
                </tr>
            @php
            $ptotal = 0;
                for($j=0; $j<((is_array($data[$row['company_id']]['srno'])) ? count($data[$row['company_id']]['srno']) : 0); $j++) {
                    $ptotal += $data[$row['company_id']]['amount'][$j];   
            @endphp 
                    <tr width="100%">
                        <td>{{ $data[$row['company_id']]['date'][$j] }}</td>
                        <td>{{ $data[$row['company_id']]['srno'][$j] }}</td>
                        <td>{{ $data[$row['company_id']]['amount'][$j] }}</td>
                        <td>{{ $data[$row['company_id']]['numberDays'][$j] }}</td>
                        <td>{{ $data[$row['company_id']]['supplier'][$j] }}</td>
                        <td>{{ $data[$row['company_id']]['bill_no'][$j] }}</td>
                    </tr>
            @php
                }
            @endphp
                    <tr width="100%">
                        <td>Party Total</td>
                        <td></td>
                        <td><b>{{ $ptotal }}</b></td>
                        <td colspan="3"></td>
                    </tr>
            @php
                $gtotal += $ptotal;
            }
            }
            @endphp
            @endforeach
            @php
            if ($request->show_detail == 0) {
            @endphp
                <tr width="100%">
                    <td colspan="2">Grand Total</td>
                    <td>{{ $gtotal }}</td>
                    <td colspan="3"></td>
                </tr>
            @php
            } else {
            @endphp
                <tr width="100%">
                    <td colspan="4"><b>Grand Total</b></td>
                    <td colspan="2">{{$gtotal}}</td>
                </tr>
            @php
            } } else {
            @endphp
            <tr width="100%">
                <td class="text-center" colspan="6" style="height: 50px;">Record Not Found</td>
            </tr>
            @php
            }
            @endphp
        
    </tbody>
</table>
