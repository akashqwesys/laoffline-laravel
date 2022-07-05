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
            $m = -1;
            $month_data = $data['month_data'];
            $grand_total = $data['grand_total'];
            if (!empty($data['date_data'])) {
        @endphp
                @foreach($data['date_data'] as $row_date)
                @php
                    $m++;
                    if(isset($data[$row_date]) && is_array($data[$row_date]) && count($data[$row_date]) != 0) {
                @endphp
                        <tr>
                            <td height="35" colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="center"><b style="font-size: 20px">{{ $row_date }}</b></td>
                        </tr>
                @php
                    $maindata = $data['maindata'];
                    $month1=array(); $company_id1=array();	$name1=array();	$address1=array();
                    $party_total1=array();
                @endphp
                    @foreach ($maindata[$row_date] as $key => $row) 
                            $month1[$key] = $row['month'];
                            $company_id1[$key]  = $row['company_id'];
                            $name1[$key]  = $row['name'];
                            $address1[$key] = $row['address'];
                            $party_total1[$key] = $row['party_total'];
                    @endforeach
                @php
                    if($request->sorting == 1) {
                            array_multisort($party_total1, SORT_ASC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $month1, SORT_ASC, $maindata[$row_date]);
                        } elseif($request->sorting == 2) {
                            array_multisort($party_total1, SORT_DESC, $name1, SORT_ASC, $address1, SORT_ASC,  $company_id1, SORT_ASC, $month1, SORT_ASC, $maindata[$row_date]);
                        }
                @endphp
                @foreach ($maindata[$row_date] as $row)
                @php
                    if ($request->show_detail == 0) {
                @endphp            
                    <tr>
                        <td colspan="3" align="center" style="height:35px"></td>
                    </tr>
                    <tr>
                       <td colspan="1"><b>{{$row['name']}}</b></td>
                       <td colspan="2"><b>{{$row['address']}}</b></td>
                    </tr>
                    <tr>
                        <th><b>Sr.</b></th>
                        <th><b>Purchase Party</b></th>
                        <th align="right"><b>Bill Amount</b></th>
                    </tr>
                @php            
                    for($i=0; $i<((is_array($data[$row_date][$row['company_id']]['sr'])) ? count($data[$row_date][$row['company_id']]['sr']) : 0); $i++) {
                @endphp        
                        <tr>
                            <td align="left">{{$data[$row_date][$row['company_id']]['sr'][$i]}}</td>
                            <td>{{$data[$row_date][$row['company_id']]['purchase_Party'][$i]}}</td>
                            <td align="right">{{$data[$row_date][$row['company_id']]['supplier_total'][$i]}}</td>
                        </tr>
                @php
                    }
                @endphp
                    <tr>
                        <td colspan="2" align="right"><b>Party Totals</b></td>
                        <td colspan="" align="right"><b>{{$row['party_total']}}</b></td>
                    </tr>
                @php
                    } else {
                @endphp
                    <tr>
                        <td colspan="2"><b>{{$row['name']}}</b></td>';
                        <td colspan="" align="right"><b>{{$row['party_total']}}</b></td>
                    </tr>
                @php
                    }
                @endphp
                @endforeach
                    <tr>
                        <td colspan="2" align="right"><b>Month Total</b></td>
                        <td align="right"><b>{{$month_data[$row_date]}}</b></td>
                    </tr>
                @php 
                    }
                @endphp
                @endforeach
                        <tr></tr>
                        <tr>
                            <td colspan="2" align="right"><b>Grand Total</b></td>
                            <td align="right"><b>{{ $grand_total }}</b></td>
                        </tr>
        @php 
            }
        @endphp
    </tbody>
</table>
