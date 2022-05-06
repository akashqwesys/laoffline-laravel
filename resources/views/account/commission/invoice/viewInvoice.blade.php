@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <invoice-component :invoice_id="{{ $employees['invoice_id'] }}"></invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
