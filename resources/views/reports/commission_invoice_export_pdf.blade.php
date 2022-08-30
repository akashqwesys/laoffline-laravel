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
            font-size: 12px;
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
            <div class="logo mb-4">
            <img src='data:image/png;base64,{{ base64_encode(file_get_contents(public_path("assets/images/logo_report.png"))) }}' alt="Logo">
            </div>
            <table class="" width="" align="center">
                @php
                    $gross_amount = $cgst = $sgst = $igst = $total_gst = $tds = $net_amount = $row_gst = 0;
                @endphp
                <thead>
                    <tr><th colspan="12" class="text-center" style="font-size: 18px;">Commission Invoice Report</th></tr>
                    <tr><th colspan="12" class="text-center" >{{ date('d-m-Y', strtotime($request->start_date)) . ' TO ' . date('d-m-Y', strtotime($request->end_date)) }}</th></tr>
                    <tr>
                        <th>Invoice No</th>
                        <th>Invoice Date</th>
                        <th>Company</th>
                        <th>Agent</th>
                        <th>State</th>
                        <th class="text-right">Gross Amount</th>
                        <th class="text-right">CGST</th>
                        <th class="text-right">SGST</th>
                        <th class="text-right">IGST</th>
                        <th class="text-right">Total GST</th>
                        <th class="text-right">TDS</th>
                        <th class="text-right">Net Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $gross_amount += floatval($d->commission_amount);
                        $sgst += floatval($d->sgst_amount);
                        $cgst += floatval($d->cgst_amount);
                        $igst += floatval($d->igst_amount);
                        $row_gst = floatval($d->cgst_amount) + floatval($d->sgst_amount) + floatval($d->igst_amount);
                        $total_gst += $row_gst;
                        $tds += floatval($d->tds_amount);
                        $net_amount += floatval($d->final_amount);
                    @endphp
                    <tr>
                        <td class=""> {{ $d->bill_no }} </td>
                        <td> {{ $d->bill_date2 }} </td>
                        <td> {{ $d->supplier_name ? $d->supplier_name : $d->customer_name }} </td>
                        <td> {{ $d->agent_name }} </td>
                        <td> {{ $d->state_name }} </td>
                        <td class="text-right"> {{ number_format($d->commission_amount) }} </td>
                        <td class="text-right"> {{ number_format($d->cgst_amount, 2) }} </td>
                        <td class="text-right"> {{ number_format($d->sgst_amount, 2) }} </td>
                        <td class="text-right"> {{ number_format($d->igst_amount, 2) }} </td>
                        <td class="text-right"> {{ number_format($row_gst, 2) }} </td>
                        <td class="text-right"> {{ number_format($d->tds_amount, 2) }} </td>
                        <td class="text-right"> {{ number_format($d->final_amount) }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="5"> Total</th>
                        <th class="text-right"> {{ number_format($gross_amount) }} </th>
                        <th class="text-right"> {{ number_format($cgst) }} </th>
                        <th class="text-right"> {{ number_format($sgst) }} </th>
                        <th class="text-right"> {{ number_format($igst) }} </th>
                        <th class="text-right"> {{ number_format($total_gst) }} </th>
                        <th class="text-right"> {{ number_format($tds) }} </th>
                        <th class="text-right"> {{ number_format($net_amount) }} </th>
                    </tr>
                </tbody>
            </table>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
