<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Softnio">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
        <!-- Fav Icon  -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
        <!-- Page Title  -->
        <title>{{ $page_title }}</title>
        <!-- StyleSheets  -->
        <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css') }}">
        <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
        <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
        <link rel="stylesheet" href="/assets/css/custom.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    </head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div id="overlay" class="loader-wrap">
            <div>
                <div>
                    <img src="/assets/images/loader_3.gif" class=""  alt="">
                </div>
            </div>
        </div>
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- content @s -->
                <div class="nk-content ">
                    <div >
                        <div class="nk-content ">
                            <div class="container-fluid">
                                <div class="nk-content-inner">
                                    <div class="nk-content-body">
                                        <div class="nk-block-head nk-block-head-sm">
                                            <div class="nk-block-between">
                                                <div class="nk-block-head-content">
                                                    <h3 class="nk-block-title page-title">Consolidate Monthly Sales Report</h3>
                                                    <div class="nk-block-des text-soft"> </div>
                                                </div><!-- .nk-block-head-content -->
                                            </div><!-- .nk-block-between -->
                                        </div><!-- .nk-block-head -->
                                        <div class="text-center mb-2"> <h5>Salebill of {{ $current_month }}</h5> </div>
                                        <div class="nk-block">
                                            <div class="card card-bordered card-stretch">
                                                <div class="card-inner">
                                                    <div class="mb-3">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Salebill</th>
                                                                    <th>Customer</th>
                                                                    <th>Supplier</th>
                                                                    <th class="text-right">Amount</th>
                                                                    <th class="text-right">Percentage</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($sale_bills as $k)
                                                                <tr>
                                                                    <td> <a href="/account/sale-bill/view-sale-bill/{{ $k->sale_bill_id . '/' . $k->financial_year_id }}" target="_blank">{{ $k->sale_bill_id }} </a></td>
                                                                    <td> {{ $k->customer_name }} </td>
                                                                    <td> {{ $k->supplier_name }} </td>
                                                                    <td class="text-right"> {{ number_format($k->total) }} </td>
                                                                    <td class="text-right"> {{ number_format(($k->total * 100 / $total), 2) }}% </td>
                                                                </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <th colspan="3" class="text-right">Total</th>
                                                                    <th class="text-right"> {{ number_format($total) }} </th>
                                                                    <th class="text-right"> 100% </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div><!-- .card -->
                                            </div>
                                        </div><!-- .nk-block -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                @include('layouts.includes.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="{{ asset('assets/js/nioapp/nioapp.min.js') }}"></script>
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="/assets/js/bootstrap-5-autocomplete/autocomplete.js"></script>

    @yield('js')
</body>

</html>
