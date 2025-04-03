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
            @if ($request->show_detail == 1)
                <th colspan="4" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION REPORT - {{ $request->report_type }}</b></th>
            @else
                <th colspan="8" align="center" style="font-size: 20px;"><b >OUTSTANDING COMMISSION REPORT - {{ $request->report_type }}</b></th>
            @endif
        </tr>
        <tr>
            @if ($request->show_detail == 1)
                <th colspan="4" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            @else
                <th colspan="8" align="center"><b>{{ date('d-m-Y', strtotime($request->start_date)) . ' - ' . date('d-m-Y', strtotime($request->end_date)) }}</b></th>
            @endif
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
                    if($data['cust_disp_name']) {
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
                $total_commission_amount = 0;
                $gtotal = $gtotal_commission = 0;
                $gparty_pending_commission = 0;
                $i = 1;
            @endphp
            @foreach ($data['detail'] as $key1 => $sc)
            @php
            if ($request->report_type == 'supplier') {

                        $supplier_name = $key1;
                        $address_supp = $sc[0]->company_address;
                if ($request->show_detail == 1) {
            @endphp
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><b>{{ $supplier_name }}</b></td>
            @php
               } else {
            @endphp
                            <tr width="100%">
		    					<td colspan="8"></td>
							</tr>
                            <tr width="100%">
                                <td colspan="2"><b>{{ $supplier_name }}</b></td>
                                <td colspan="6"><b>{{ $address_supp }}</b></td>
                            </tr>
            @php
                }
                } else {

                        $customer_name = $key1;
                        $address_supp = $sc[0]->company_address;
                    if ($request->show_detail == 1) {
            @endphp
                    <tr width="100%">
                                <td>{{ $i++ }}</td>
                                <td><b>{{ $customer_name }}</b></td>
            @php
                } else {
            @endphp

                        <tr width="100%">
                                    <td colspan="8"></td>
                                </tr>
                                <tr width="100%">
                                    <td colspan="2"><b>{{ $customer_name }}</b></td>
                                    <td colspan="6"><b>{{ $address_supp }}</b></td>
                                </tr>
            @php
                }
            }
            $ptotal = $ptotal_commission = 0;
            $party_pending_commission = 0;
            $invoices_included = [];
            @endphp
            @foreach ($sc as $key => $row)
            @php
                $color = "";
                    $paymentdate = strtotime($row->date);
                    $currentdate = strtotime(date('d-m-Y'));
                    $due_day = ($currentdate - $paymentdate) / 84600;
                    if ($due_day)
                    if ($due_day >= 90) {
                        $color = "style='color:red'";
                    }


                if ($request->report_type == 'supplier') {
                    $invoices = DB::table('commission_invoices as ci')
                    ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                    ->leftJoin(DB::raw('(SELECT "commission_invoice_id", SUM("received_commission_amount") as received_commission_amount FROM commission_details where is_deleted = 0 group by "commission_invoice_id") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                    ->where('ipd.flag', 1)
                        ->where('ci.is_deleted', 0)
                        ->where('ipd.payment_id', $row->payment_id)
                        ->where('ipd.financial_year_id', $row->financial_year_id)
                        ->select('ci.id', 'ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                        ->get();
                } else {
                    $invoices = DB::table('commission_invoices as ci')
                    ->leftJoin(DB::raw('(SELECT "payment_id", "commission_invoice_id", "financial_year_id", "flag" FROM invoice_payment_details group by "payment_id", "commission_invoice_id", "financial_year_id", "flag") as ipd'), 'ci.id', '=', 'ipd.commission_invoice_id')
                    ->leftJoin(DB::raw('(SELECT "commission_invoice_id", SUM("received_commission_amount") as received_commission_amount FROM commission_details where is_deleted = 0 group by "commission_invoice_id") as cd'), 'ci.id', '=', 'cd.commission_invoice_id')
                    ->where('ipd.flag', 2)
                        ->where('ci.is_deleted', 0)
                        ->where('ipd.payment_id', $row->payment_id)
                        ->where('ipd.financial_year_id', $row->financial_year_id)
                        ->select('ci.id', 'ci.bill_no', 'ipd.payment_id', 'ipd.financial_year_id', 'ci.final_amount', 'cd.received_commission_amount')
                        ->get();
                }
                $pending_amount = 0;
                if (count($invoices)) {
                    $total = 0;
                    $pending_parcent = collect($invoices)->groupBy('id');
                    $total_invoice_received = 0;
            @endphp
            @foreach ($pending_parcent as $inv)
                @foreach ($inv as $pp)
                    @php
                            $total += (int)$pp->received_commission_amount;
                            $final_amount = $pp->final_amount;
                            $commission_invoice_id = $pp->id;
                            $bill_no = $pp->bill_no;
                    @endphp
                @endforeach

                @php
                    $lastInvoice = $inv->last();
                    if (!in_array($lastInvoice->id, $invoices_included)) {
                        $invoices_included[] = $lastInvoice->id;
                        $party_pending_commission += ((int)$lastInvoice->final_amount - $pp->received_commission_amount);
                    }
                @endphp

            @endforeach
            @php
                    $pending_amount = (int)$final_amount - (int)$total;
                    $pending_percentage = round((($pending_amount * 100) / $final_amount), 2);
                    $pending_percentage = $pending_percentage . " %";
                } else {
                    $pending_percentage = "100 %";
                    $commission_invoice_id = '';
                    $bill_no = '';
                    $pending_amount = $row->commission_amount;
                }
                if ($pending_percentage == '0 %') {
                } else {
                    $receipt_amount = number_format($row->receipt_amount);
                    $commission_amount = number_format($row->commission_amount);
                    if ($request->show_detail == 1) {

                    } else {

            @endphp
                    <tr width="100%" {{ $color }}>
                                <td>{{  $row->payment_id }}</td>
                                <td>{{ date("d-m-Y", strtotime($row->date)) }}</td>
                                <td class="text-right">{{ $receipt_amount }}</td>
                                <td class="text-right">{{ $commission_amount }}</td>
            @php
                }
                    if ($request->report_type == 'supplier') {
                        $cust_supp_name = $row->customer_name;
                    } else {
                        $cust_supp_name = $row->supplier_name;
                    }
                    if ($request->show_detail == 1) {
                    } else {
            @endphp
                    <td>{{ $pending_percentage }}</td>
                    <td>{{ $cust_supp_name }}</td>
                    <td>{{ floor($due_day) }}</td>
                    <td>{{  $bill_no }}</td>
                   </tr>
            @php

                }
                $ptotal += $row->receipt_amount;
                $ptotal_commission += $row->commission_amount;
            }
            @endphp
            @endforeach
            @php
                $ptotal1 = number_format($ptotal);
                $ptotal_commission1 = number_format($ptotal_commission);
                $party_pending_commission1 = number_format($party_pending_commission);
                if ($request->show_detail == 1) {
            @endphp
                    <td class="text-right"><b>{{ $ptotal1 }}</b></td>
                    <td class="text-right"><b>{{ $party_pending_commission1 }}</b></td>
                </tr>
            @php
                 } else {
            @endphp

               <tr width="100%">
                        <td colspan="2"><b>Party Total</b></td>
                        <td class="text-right"><b>{{ $ptotal1 }}</b></td>
                        <td class="text-right"><b>{{ $party_pending_commission1 }}</b></td>
                        <td colspan="4"></td>
                        </tr>
            @php
               }
                $gtotal += $ptotal;
                $gtotal_commission += $ptotal_commission;
                $gparty_pending_commission += $party_pending_commission;
            @endphp
            @endforeach
            @php
                $gtotal1 = number_format($gtotal);
                $gtotal_commission1 = number_format($gtotal_commission);
                $gparty_pending_commission1 = number_format($gparty_pending_commission);
                if ($request->show_detail == 1) {
            @endphp
                <tr width="100%">
                        <td colspan="2"><b>Grand Total</b></td>
                        <td class="text-right"><b>{{ $gtotal1 }}</b></td>
                        <td class="text-right"><b>{{ $gparty_pending_commission1 }}</b></td>
                     </tr>
            @php
                } else {
            @endphp
                <tr width="100%">
                        <td colspan="2"><b>Grand Total</b></td>
                        <td class="text-right"><b>{{ $gtotal1 }}</b></td>
                        <td class="text-right"><b>{{ $gparty_pending_commission1 }}</b></td>
                        <td colspan="4"></td>
                        </tr>
            @php
                }
            @endphp

    </tbody>
</table>

