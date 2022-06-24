@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <outstanding-payment-component ></outstanding-payment-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
