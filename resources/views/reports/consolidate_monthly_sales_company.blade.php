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
        <style>
            @media (min-width: 768px) {
                .accordion-s3 .accordion-head {
                    padding: 1rem 2rem 1rem 2.25rem;
                    margin: 1rem 0rem;
                    background-color: #f6f8f8;
                }
            }
            .accordion-s3 .accordion-head.collapsed .accordion-icon:before {
                font-weight: bold;
            }
            .accordion-s3 .accordion-icon {
                left: 0.5rem;
            }
        </style>
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
                    <div id="app">
                        <consolidate-monthly-sales-company-component ></consolidate-monthly-sales-company-component>
                    </div>
                    <script src="{{ asset('js/app.js') }}"></script>
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
