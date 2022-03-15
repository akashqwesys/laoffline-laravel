@extends('layouts.app')

@section('content')
    <div id="app">
        <product-category-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></product-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
