@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <payment-status-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}" :status="{{ $employees['status'] }}"></payment-status-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
