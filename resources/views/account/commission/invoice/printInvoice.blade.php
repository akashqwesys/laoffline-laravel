<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="/assets/images/favicon.png">
    <title>{{ $page_title }}</title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="/assets/css/paper.css">
    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
    @page {
        size: A4;
    }

    * {
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -o-box-sizing: border-box;
    }

    article {
        padding: 20px 0;
    }

    body,
    html {
        font-family: 'Roboto', sans-serif;
    }

    .logo {
        text-align: center;
    }

    .logo > img {
        max-width: 150px;
    }

    .header-box {
        padding: 40px 80px 0px 80px;
        position: relative;
        display: table;
        width: 100%;
    }

    .section-left,
    .section-right {
        width: 50%;
        float: left;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    table {
        text-align: center;
    }

    table tr th {
        font-size: 14px;
    }

    table tr td {
        font-size: 12px;
    }

    table.tb-ct-bill tr td {
        padding: 10px;
    }

    table tr th,
    table tr td {
        min-width: 60px;
        padding: 6px 20px;
        letter-spacing: 0px;
    }

    table.tb-ct tr th,
    table.tb-ct tr td {
       letter-spacing: 0;
        border: 1px solid #222;
    }

    table.invoice-table {
        border: 1px solid #222;
    }

    table.invoice-table tr th,
    table.invoice-table tr td {
      letter-spacing: 0;
      font-size: 14px;
    }
    table.invoice-table thead tr th {
        font-size: 10px;
        font-weight: 700;
    }

    table.invoice-table tr th:first-child{
      min-width: 70px;
    }
    table.invoice-table tr th,
    table.invoice-table tfoot tr td:last-child {
        border: 1px solid #222;
        padding: 10px 18px;
    }

    table.invoice-table tr td:last-child,
    table.invoice-table tr th:last-child {
        border-left: 1px solid #222;
        text-align: right;
    }

    table.invoice-table tfoot tr td {
        font-weight: 700;
    }

    .clearfix {
        clear: both;
    }

    hr {
        border: 1px solid #222;
    }

    .section-title h5 {
        margin-top: 0px;
        margin-bottom: 6px;
    }

    .section-title p {
        margin-top: 0;
        font-size: 13px;
        padding-right: 10px;
        margin-bottom: 6px;
    }

    .gst-box {
        border: 1px solid #000;
        padding: 4px;
        font-size: 10px !important;
        display: inline-block;
    }

    .invoice-table {
        margin: 40px 80px 0px 80px;
        /* width: 100%; */
        float: right;
        text-align: left;
    }

    .tax_title {
        text-align: center;
    }

    .sac {
        font-size: 10px;
        border: 1px solid #000;
        padding: 2px;
        display: inline-block;
    }
    .text-center {
      text-align: center;
    }

    .text-right {
        text-align: right;
    }
    .title-sign h5{
      font-weight: 300;
      text-transform: uppercase;
      border-bottom: 1px solid #000;
      display: inline-block;
      padding-bottom: 10px;
      letter-spacing: 0;
    }
    .title-sign span{
      text-transform: capitalize;
      font-size: 10px;
      letter-spacing: 0;
      margin:  0;
      padding: 0;
    }
    footer {
      font-size: 9px;
      color: #222;
      text-align: center;
      bottom:20px;
      position: absolute;
      width: 100%;
      padding: 4px 0;
      border-top: 1px solid #222;
    }
    footer p{
      font-size: 12px;
      margin: 4px;
      font-size: 13px;
    }
    @media print {
      footer {
        position: fixed;
        bottom: 20px;
      }
      .content-block, p {
        page-break-inside: avoid;
      }
    }
    .sp-title{
      padding:  0;
      margin:  0 0 10px 0;
      text-decoration: underline;
    }
    .tax-pay{
      font-weight: 400 !important;
    }
    .tax-pay span{
      text-decoration: line-through;
    }
    </style>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    <div id="app">
        <print-invoice-component :id="{{ $employees['invoice_id'] }}"></print-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
