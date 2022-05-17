@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-invoice-component :id="{{ $employees['invoice_id'] }}"></view-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
