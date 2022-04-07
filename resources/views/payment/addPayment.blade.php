@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <add-payment-component></add-payment-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
