@extends('layouts.app')
@section('title', $page_title)
@section('css')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css"> --}}
@endsection
@section('content')
    <div id="app">
        <product-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></product-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
