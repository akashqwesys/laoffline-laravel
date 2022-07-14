<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="/assets/images/favicon.png">
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="/assets/css/paper.css">
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        /* @page {
            size: A4;
        } */
        * {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -o-box-sizing: border-box;
        }
        article {
            padding: 10px 0;
        }
        .logo {
            text-align: center;
        }
        .logo > img {
            max-width: 150px;
        }
        /* table {
            border: 1px solid;
        } */
        table tr th {
            text-align: left;
        }
        table tr th,
        table tr td {
            font-size: 10px;
            padding: 3px;
            /* letter-spacing: 0px; */
            border: 1px solid black;
        }
        .clearfix {
            clear: both;
        }
        hr {
            border: 1px solid #222;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mt-2 {
            margin-top: 0.5rem;
        }
        .mt-4 {
            margin-top: 1rem;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .w-80 {
            width: 80%;
        }
        .m-auto {
            margin: auto;
        }
        @media print {
            .content-block, p {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet">
        <!-- Write HTML just like a web page -->
        <article>
            <div class="logo">
                <img src="https://dev.laoffline.com/assets/images/logo_report.png" alt="Logo">
            </div>
            <div class="text-center">
                <b class="mt-2" style="font-size: 14px;">Salebill Details Report</b>
                <br>
                <b class="mb-4" style="font-size: 12px;">{{ date('d-m-Y', strtotime($request['selected_date'])) }}</b>
            </div>
            @php
                $total_pieces = $total_meters = $net_total = $gross_total = $gross_amount = $i = 0;
                $sub_total_pieces = $sub_total_meters = $sub_total_amount = 0;
                $sub_html = '';
            @endphp
            <table class="" width="100%">
                <thead>
                    <tr>
                        <th><b>Date</b></th>
                        <th><b>Serial</b></th>
                        <th><b>Party</b></th>
                        <th class="text-right"><b>Pieces</b></th>
                        <th class="text-right"><b>Meters</b></th>
                        <th class="text-right"><b>Net Amount</b></th>
                        <th><b>Agent</b></th>
                        <th><b>Invoice</b></th>
                        <th class="text-right"><b>Gross Amount</b></th>
                        <th><b>Transport</b></th>
                        <th><b>City</b></th>
                        <th><b>L. R. No.</b></th>
                        <th><b>Purchase Party</b></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $total_pieces += floatval($d->pieces);
                        $total_meters += floatval($d->meters);
                        $net_total += floatval($d->total);
                        if ($d->sign_change == '+') {
                            $gross_amount = (floatval($d->total) - floatval($d->change_in_amount));
                        } else {
                            $gross_amount = (floatval($d->total) + floatval($d->change_in_amount));
                        }
                        $gross_total += $gross_amount;
                        if ($i < (count($data) - 1) && $d->sale_bill_id == $data[$i+1]->sale_bill_id) {
                            $sub_html .= '<tr>
                                <td>' . $d->product_name . '</td>
                                <td class="text-right">' . $d->pieces . '</td>
                                <td class="text-right">' . $d->meters . '</td>
                                <td class="text-right">' . $d->rate . '</td>
                                <td class="text-right">' . floatval($d->amount) . '</td>
                            </tr>';
                            $sub_total_pieces += floatval($d->pieces);
                            $sub_total_meters += floatval($d->meters);
                            $sub_total_amount += floatval($d->amount);
                            continue;
                        } else {
                            $sub_total_pieces += floatval($d->pieces);
                            $sub_total_meters += floatval($d->meters);
                            $sub_total_amount += floatval($d->amount);
                            $sub_html .= '<tr>
                                <td>' . $d->product_name . '</td>
                                <td class="text-right">' . $d->pieces . '</td>
                                <td class="text-right">' . $d->meters . '</td>
                                <td class="text-right">' . $d->rate . '</td>
                                <td class="text-right">' . floatval($d->amount) . '</td>
                            </tr>';
                        }
                    @endphp
                    <tr>
                        <td class=""> {{ $d->select_date }} </td>
                        <td align="left"> {{ $d->sale_bill_id }} </td>
                        <td class=""> {{ $d->customer_name }} </td>
                        <td class="text-right"> {{ number_format($sub_total_pieces) }} </td>
                        <td class="text-right"> {{ number_format($sub_total_meters) }} </td>
                        <td class="text-right"> {{ number_format($d->total) }} </td>
                        <td class=""> {{ $d->agent_name }} </td>
                        <td align="left"> {{ $d->supplier_invoice_no }} </td>
                        <td class="text-right"> {{ number_format($gross_amount) }} </td>
                        <td class=""> {{ $d->transport_name }} </td>
                        <td class=""> {{ $d->city_name }} </td>
                        <td align="left"> {{ $d->lr_mr_no }} </td>
                        <td class=""> {{ $d->supplier_name }} </td>
                    </tr>
                    <tr>
                        <td colspan="13">
                            <div class="text-center w-80 m-auto-">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><b>Name</b></th>
                                            <th class="text-right"><b>Pieces</b></th>
                                            <th class="text-right"><b>Meters</b></th>
                                            <th class="text-right"><b>Rate</b></th>
                                            <th class="text-right"><b>Amount</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {!! $sub_html !!}
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right">{{ $sub_total_pieces }}</th>
                                            <th class="text-right">{{ $sub_total_meters }}</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">{{ floatval($sub_total_amount) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="13"> <br></td></tr>
                    @php
                        $sub_html = '';
                        $sub_total_pieces = $sub_total_meters = $sub_total_amount = 0;
                        $i++;
                    @endphp
                    @endforeach
                    <tr>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> <b>Total</b></th>
                        <th class="text-right"> <b>{{ number_format($total_pieces) }} </b></th>
                        <th class="text-right"> <b>{{ number_format($total_meters) }} </b></th>
                        <th class="text-right"> <b>{{ number_format($net_total) }} </b></th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="text-right"> <b>{{ number_format($gross_total) }} </b></th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="">  </th>
                    </tr>
                </tbody>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
