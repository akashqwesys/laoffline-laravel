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
                    $received_amount = $total_amount = $roa = 0;
                @endphp
                <thead>
                    <tr><th colspan="13" class="text-center" style="font-size: 18px;">Commission Invoice Right of Report</th></tr>
                    <tr><th colspan="13" class="text-center" >{{ date('d-m-Y', strtotime($request->start_date)) . ' TO ' . date('d-m-Y', strtotime($request->end_date)) }}</th></tr>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Dep. Bank</th>
                        <th>Chq. Date</th>
                        <th>Chq/DD No</th>
                        <th>Chq/DD Bank</th>
                        <th class="text-right">Rec. Amount</th>
                        <th class="text-right">Tot. Amount</th>
                        <th class="text-right">Right of Amt</th>
                        <th>Right of Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $received_amount += floatval($d->receipt_amount);
                        $total_amount += floatval($d->total_amount);
                        $roa += floatval($d->right_of_amount);
                    @endphp
                    <tr>
                        <td class=""> {{ $d->payment_id }} </td>
                        <td> {{ $d->supplier_name }} </td>
                        <td> {{ $d->customer_name }} </td>
                        <td> {{ $d->date2 }} </td>
                        <td> {{ $d->reciept_mode }} </td>
                        <td> {{ $d->reciept_mode != "full return" ? $d->deposit_bank : '' }} </td>
                        <td> {{ $d->reciept_mode != "full return" ? $d->cheque_date2 : '' }} </td>
                        <td> {{ $d->reciept_mode == "cheque" ? $d->cheque_dd_no : '' }} </td>
                        <td> {{ $d->bank_name }} </td>
                        <td class="text-right"> {{ $d->reciept_mode != "full return" ? number_format($d->receipt_amount) : '' }} </td>
                        <td class="text-right"> {{ number_format($d->total_amount) }} </td>
                        <td class="text-right"> {{ number_format($d->right_of_amount) }} </td>
                        <td> {{ $d->right_of_remark }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="9"> Total</th>
                        <th class="text-right"> {{ number_format($received_amount) }} </th>
                        <th class="text-right"> {{ number_format($total_amount) }} </th>
                        <th class="text-right"> {{ number_format($roa) }} </th>
                        <th > </th>
                    </tr>
                </tbody>
            </table>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
