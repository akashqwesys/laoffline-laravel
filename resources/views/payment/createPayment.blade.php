@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-payment-component></create-payment-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
