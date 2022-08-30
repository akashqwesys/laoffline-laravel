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
            <img src='data:image/png;base64,{{ base64_encode(file_get_contents(public_path("assets/images/logo_report.png"))) }}' alt="Logo">
            </div>
            <div class="text-center">
                <b class="mt-2" style="font-size: 14px;">COMMISSION REGISTER REPORT</b>
                <br>
                <b class="mb-4" style="font-size: 12px;">{{ date('d-m-Y', strtotime($request->start_date)) . ' TO ' . date('d-m-Y', strtotime($request->end_date)) }}</b>
            </div>
            <table class="" width="100%">
                @php
                    $tcommission_payment_amount = 0; $ttds = 0; $tservice_tax= 0; $total = 0;$company = '';
                @endphp
                @if ($request->show_detail == 1)
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Supplier</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i=>$d)
                    @php
                        $total += floatval($d->total);
                    @endphp
                    <tr>
                        <td class=""> {{ ++$i }} </td>
                        <td class=""> {{ $d->supplier_name }} </td>
                        <td class="text-right"> {{ number_format($d->total) }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class=""> </th>
                        <th class="text-right"> </th>
                        <th class="text-right"> {{ number_format($total) }} </th>
                    </tr>
                </tbody>
                @else
                <thead>
                    <tr>
                    <th>Id</th>
                    <th>Company</th>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Mode</th>
                    <th>Dep.Bank</th>
                    <th>Chq.Date</th>
                    <th>Chq/DD No</th>
                    <th>Chq/DD Bank</th>
                    <th>Amount</th>
                    <th>TDS</th>
                    <th>S.T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $commission_payment_amount = $d->commission_payment_amount;
                        
                        if (!$commission_payment_amount) {
                            $commission_payment_amount = 0;
                        }

                        $tds = $d->tds;
                        
                        if (!$tds) {
                            $tds = 0;
                        }
                        
                        $service_tax = $d->service_tax;
                        
                        if (!$service_tax) {
                            $service_tax = 0;
                        }
                        
                        $tcommission_payment_amount += floatval($commission_payment_amount);
                        $ttds += floatval($tds);
                        $tservice_tax += floatval($service_tax);
                        
                        if ($d->customer_id == 0) {
                            $company = $d->supplier_name;
                        } else if ($d->supplier_id == 0) {
                            $company = $d->customer_name;
                        }

                        if ($d->commission_reciept_mode != 'cheque') {
                            $cheque_date = '-';
                            $cheque_dd_no = '-';
                            $cheque_bank = '-';
                            $bank_name = '-';
                        } else {
                            $cheque_date = $d->commission_cheque_date;
                            $cheque_dd_no = $d->commission_cheque_dd_no;
                            $cheque_bank = $d->commission_cheque_dd_bank;
                            $bank_name = $d->bank_name;
                        }
                    @endphp
                    <tr>
                        <td class=""> {{ $d->commission_id }} </td>
                        <td class=""> {{ $company }} </td>
                        <td class=""> {{ $d->commission_date }} </td>
                        <td class=""> {{ $d->agent }} </td>
                        <td class=""> {{ $d->commission_reciept_mode }} </td>
                        <td class=""> {{ $bank_name }} </td>
                        <td class=""> {{ $cheque_date }} </td>
                        <td class=""> {{ $cheque_dd_no }} </td>
                        <td class=""> {{ $cheque_bank }} </td>
                        <td class=""> {{ $commission_payment_amount }} </td>
                        <td class=""> {{ $tds }} </td>
                        <td class=""> {{ $service_tax }} </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> </th>
                        <th class=""> Total </th>
                        <th class=""> {{ $tcommission_payment_amount }} </th>
                        <th class=""> {{ $ttds }}</th>
                        <th class=""> {{ $tservice_tax }}</th>
                    </tr>
                </tbody>
                @endif
            </table>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
