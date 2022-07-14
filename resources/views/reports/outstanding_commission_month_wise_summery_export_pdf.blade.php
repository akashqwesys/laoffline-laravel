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
                <b class="mt-2" style="font-size: 14px;">OUTSTANDING COMMISSION MONTH WISE SUMMERY REPORT - {{ $request->report_type }}</b>
                <br>
                <b class="mb-4" style="font-size: 12px;">{{ date('d-m-Y', strtotime($request->start_date)) . ' TO ' . date('d-m-Y', strtotime($request->end_date)) }}</b>
            </div>
            <table class="" width="100%">
                {!! $data['table'] !!}
            </table>
        </article>
    </section>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
