@extends('layouts.app')

@section('content')
    <div id="app">
        <product-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></product-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection