@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <outstanding-payment-month-wise-summery-component></outstanding-payment-month-wise-summery-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
