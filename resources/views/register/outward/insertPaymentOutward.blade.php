@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-payment-outward-component :from-date="'{{ Session::get('outward_payment_from_date') }}'" :to-date="'{{ Session::get('outward_payment_to_date') }}'"></insert-payment-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
