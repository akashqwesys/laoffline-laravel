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
                <img src="/assets/images/logo_report.png" alt="Logo">
            </div>
            <div class="text-center">
                <b class="mt-2" style="font-size: 14px;">SALES REGISTER REPORT</b>
                <br>
                <b class="mb-4" style="font-size: 12px;">{{ date('d-m-Y', strtotime($request->start_date)) . ' TO ' . date('d-m-Y', strtotime($request->end_date)) }}</b>
            </div>
            <table class="" width="100%">
                @php
                    $total_pieces = $total_meters = $net_total = $gross_total = $rec_total = $gross_amount = $i = 0;
                @endphp
                @if ($request->show_detail == 1)
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Party</th>
                        <th class="text-right">Net Amt</th>
                        <th class="text-right">Rec. Amt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $net_total += floatval($d->total);
                        $rec_total += floatval($d->received_payment);
                    @endphp
                    <tr>
                        <td class=""> {{ ++$i }} </td>
                        <td class=""> {{ $d->company_name }} </td>
                        <td class="text-right"> {{ number_format($d->total) }} </td>
                        <td class="text-right"> {{ number_format($d->received_payment) }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class=""> Total </th>
                        <th class=""> </th>
                        <th class="text-right"> {{ number_format($net_total) }} </th>
                        <th class="text-right"> {{ number_format($rec_total) }} </th>
                    </tr>
                </tbody>
                @else
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sr</th>
                        <th>Party</th>
                        <th class="text-right">Pieces</th>
                        <th class="text-right">Meters</th>
                        <th class="text-right">Net Amt</th>
                        <th>Agent</th>
                        <th>Invoice</th>
                        <th class="text-right">Gross Amt</th>
                        <th>Transport</th>
                        <th>City</th>
                        <th>L.R.No.</th>
                        <th>Purchase Party</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $total_pieces += floatval($d->tot_pieces);
                        $total_meters += floatval($d->tot_meters);
                        $net_total += floatval($d->total);
                        if ($d->sign_change == '+') {
                            $gross_amount = (floatval($d->total) - floatval($d->change_in_amount));
                        } else {
                            $gross_amount = (floatval($d->total) + floatval($d->change_in_amount));
                        }
                        $gross_total += $gross_amount;
                    @endphp
                    <tr>
                        <td class=""> {{ $d->select_date }} </td>
                        <td class=""> {{ $d->sale_bill_id }} </td>
                        <td class=""> {{ $d->customer_name }} </td>
                        <td class="text-right"> {{ $d->tot_pieces }} </td>
                        <td class="text-right"> {{ $d->tot_meters }} </td>
                        <td class="text-right"> {{ number_format($d->total) }} </td>
                        <td class=""> {{ $d->agent_name }} </td>
                        <td class=""> {{ $d->supplier_invoice_no }} </td>
                        <td class="text-right"> {{ number_format($gross_amount) }} </td>
                        <td class=""> {{ $d->transport_name }} </td>
                        <td class=""> {{ $d->city_name }} </td>
                        <td class=""> {{ $d->lr_mr_no }} </td>
                        <td class=""> {{ $d->supplier_name }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class=""> Total </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class="text-right"> {{ $total_pieces }} </th>
                        <th class="text-right"> {{ $total_meters }} </th>
                        <th class="text-right"> {{ number_format($net_total) }} </th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="text-right"> {{ number_format($gross_total) }} </th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="">  </th>
                        <th class="">  </th>
                    </tr>
                </tbody>
                @endif
            </table>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
