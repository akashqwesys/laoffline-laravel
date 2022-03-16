@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <product-sub-category-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></product-sub-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
