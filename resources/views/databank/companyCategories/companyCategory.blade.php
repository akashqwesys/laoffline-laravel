@extends('layouts.app')

@section('content')
    <div id="app">
        <company-category-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></company-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection