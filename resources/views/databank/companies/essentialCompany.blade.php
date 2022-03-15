@extends('layouts.app')

@section('content')
    <div id="app">
        <essential-company-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></essential-company-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection