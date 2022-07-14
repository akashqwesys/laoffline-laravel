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
                <th colspan="4" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION REPORT - {{ $request->report_type }}</b></th>
            @php
            } else {
            @endphp
            <th colspan="8" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION REPORT - {{ $request->report_type }}</b></th> 
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
            <th colspan="8" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            @php
            }
            @endphp
            
        </tr>
    </thead>
    <tbody>
            @php
                $morethan = $sup = ''; $sup1 = '';
                if ($request->day != '' && $request->day['report_days'] != 0) {
                    $morethan .= "( More then ". $request->day['report_days'] ." Days)";
                } else {
                    $morethan .= "";
                }

                if ($request->supplier != '') {
                    if($data['sup_disp_name']) {
                        $sup .= "Supplier: " .$data['sup_disp_name'] . $morethan;
                    } else {
                        $sup .= "Supplier: " .$data['sup_disp_name'] . $morethan;
                    }
                } else {
                    $sup .= "All Parties". $morethan;
                }
                
                if ($request->customer != '') {
                    if($data['sup_disp_name']) {
                        $sup1 .= "Customer: " .$data['cust_disp_name'] . $morethan;
                    } else {
                        $sup1 .= "Customer: " .$data['cust_disp_name'] . $morethan;
                    }
                } else {
                    $sup1 .= "All Parties". $morethan;
                }
                $html = '';
                if ($request->report_type == 'supplier'){
                if ($request->show_detail == 1) {
            @endphp    
                    <tr>
                        <th colspan="4">{{ $sup }}</th>
                    </tr>
                    <tr>
						    <th>Sr_No</th>
							<th>Supplier Name</th>
							<th>Amount</th>
							<th>Commission Amount</th>
						</tr>
            @php
                } else {
            @endphp
                    <tr>
                        <th colspan="8">{{ $sup }}</th>
                    </tr>
                    <tr width="100%" class=""text-center>
						    <th>Payment Id</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Commission Amount</th>
							<th>Percent</th>
							<th>Customer</th>
							<th>Days</th>
							<th>Invoice</th>
						</tr>
            @php
                }
            } else { 
                if ($request->show_detail == 1) {
            @endphp
            <tr>
                        <th colspan="4">{{ $sup1 }}</th>
                    </tr>
                    <tr>
						    <th>Sr_No</th>
							<th>Customer Name</th>
							<th>Amount</th>
							<th>Commission Amount</th>
						</tr>
            @php
                } else {
            @endphp
                    <tr>
                        <th colspan="8">{{ $sup1 }}</th>
                    </tr>
                    <tr width="100%" class=""text-center>
						    <th>Payment Id</th>
							<th>Date</th>
							<th>Amount</th>
							<th>Commission Amount</th>
							<th>Percent</th>
							<th>Supplier</th>
							<th>Days</th>
							<th>Invoice</th>
						</tr>
            @php
                } }
                if ($request->show_detail == 1) {
                $tot_payment = $total_payment = $total_commission_amount = 0;
            @endphp
                @foreach ($data['detail'] as $key => $row)
                @php 
                if ($request->report_type == 'supplier') {
                    $company_name = $row->supplier_name;
                } else {
                    $company_name = $row->customer_name;
                }  
                @endphp
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $company_name }}</td>
                            <td>{{ $row->receipt_amount }}</td>
                            <td>{{ $row->total_comm_amount }}</td>
                        </tr>
            @php
                $tot_payment += $row->receipt_amount;
                $total_commission_amount += $row->total_comm_amount;
            @endphp
            @endforeach
            @php
                if (!empty($data['detail'])){
            @endphp
                    <tr>
                        <td colspan="2"><b>Party Total</b></td>
                        <td><b>{{ $tot_payment }}</b></td>
                        <td><b>{{ $total_commission_amount }}</b></td>
                    </tr>
            @php
            } 
            } else {
                $supplier_name = ""; $customer_name = ""; $prev_com = 0; $tot_payment = $total_payment = $total_commission_amount = 0;
            @endphp   
            @foreach ($data['detail'] as $keys => $row)
            @php
                $color = "";
                $paymentdate = strtotime($row->date);
                $currentdate = strtotime(Carbon\Carbon::now()->format('d-m-Y'));
                $due_day = ($currentdate - $paymentdate) / 84600;
                if ($due_day >= 90) {
                    $color = "style='color:red'";
                }
                $i = 0;
                if ($request->report_type == 'supplier') {
                if($supplier_name != $row->supplier_name) {
                    $supplier_name = $row->supplier_name;
                    $address_supp = $row->company_address;

                    if($keys != 0) {
            @endphp
                        <tr>
                                <td colspan="2"><b>Party Total</b></td>
                                <td><b>{{ $tot_payment }}</b></td>
                                <td><b>{{ $prev_com }}</b></td>
                                <td colspan="4"></td>
							</tr>
            @php
                    $tot_payment = 0;
                    }
            @endphp
                        
                    <tr width="100%">
		    			<td colspan="8"></td>
					</tr>
                    <tr width="100%">
                        <td colspan="2"><b>{{ $supplier_name }}</b></td>
                        <td colspan="6"><b>{{ $address_supp }}</b></td>
                    </tr>
            @php
                } } else {
                    if($customer_name != $row->customer_name) {
                        $customer_name = $row->customer_name;
                        $address_supp = $row->company_address;
                        if($keys != 0) {
            @endphp
                        <tr>
                                <td colspan="2"><b>Party Total</b></td>
                                <td><b>{{ $tot_payment }}</b></td>
                                <td><b>{{ $prev_com }}</b></td>
                                <td colspan="4"></td>
							</tr>
            @php
                    $tot_payment = 0;
                    }
            @endphp
                        
                    <tr width="100%">
		    			<td colspan="8"></td>
					</tr>
                    <tr width="100%">
                        <td colspan="2"><b>{{ $supplier_name }}</b></td>
                        <td colspan="6"><b>{{ $address_supp }}</b></td>
                    </tr>
            @php
                } }
            @endphp     
                    <tr width="100%" {{ $color }}>
                                <td>{{ $row->payment_id }}</td>
                                <td>{{ date("d-m-Y", strtotime($row->date)) }}</td>
                                <td>{{ $row->receipt_amount }}</td>
                                <td>{{ $row->commission_amount }}</td>
            @php
            if ($request->report_type == 'supplier') {
                $invoices = DB::table('commission_invoices as ci')
                                ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                                ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                                ->where('ipd.flag', 1)
                                ->where('ipd.payment_id', $row->payment_id)
                                ->where('ipd.financial_year_id', $row->financial_year_id)
                                ->select('ci.id','ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                                ->get();
            } else {
                $invoices = DB::table('commission_invoices as ci')
                                ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                                ->leftJoin(DB::raw('(SELECT "commission_invoice_id", "received_commission_amount" FROM commission_details group by "commission_invoice_id", "received_commission_amount") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                                ->where('ipd.flag', 2)
                                ->where('ipd.payment_id', $row->payment_id)
                                ->where('ipd.financial_year_id', $row->financial_year_id)
                                ->select('ci.id','ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                                ->get();
            }
                if (count($invoices)) {
                    $total = 0;
                    $pending_parcent = collect($invoices)->groupBy('id');
                    foreach($pending_parcent as $inv) {
                        foreach($inv as $pp){
                            $total += (int)$pp->received_commission_amount;
                            $final_amount = $pp->final_amount;
                            $commission_invoice_id = $pp->id;
                            $bill_no = $pp->bill_no;
                        }
                    }
                            
                    $pending_amount = (int)$final_amount - (int)$total;
                    $pending_percentage = round((($pending_amount * 100) / $final_amount),2);
                    $pending_percentage = $pending_percentage." %";
                            
                } else {
                    $pending_percentage = "100 %";
					$commission_invoice_id = '';
					$bill_no = ''; 
                }
                if ($request->report_type == 'supplier') {
                    $cust_supp_name = $row->customer_name;
                } else {
                    $cust_supp_name = $row->supplier_name;
                }   
            @endphp
                    <td>{{ $pending_percentage }}</td>
                    <td>{{ $cust_supp_name }}</td>
                    <td>{{ floor($due_day) }}</td>
                    <td>{{ $bill_no }}</td>
                </tr>
            @php    
                $prev_com = $row->total_comm_amount;
                $tot_payment += $row->receipt_amount;
                $total_payment += $row->receipt_amount;
                $total_commission_amount += $row->commission_amount;
            @endphp            
            @endforeach
            @php    
                if (!empty($data['detail'])) {
            @endphp
                <tr>
                    <td colspan="2"><b>Party Total</b></td>
                    <td><b>{{ $tot_payment }}</b></td>
                    <td><b>{{ $prev_com }}</b></td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="8">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Grand Total</b></td>
                    <td><b>{{ $total_payment }}</b></td>
                    <td><b>{{ $total_commission_amount }}</b></td>
                    <td colspan="4"></td>
                </tr>
            @php
            }
            }
            @endphp            
            </tbody>
</table>

