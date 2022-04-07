@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <payment-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></payment-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
