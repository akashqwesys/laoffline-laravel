@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-commission-invoice-outward-component :set-date="'{{ Session::get('outward_invoice_date') }}'"></insert-commission-invoice-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
