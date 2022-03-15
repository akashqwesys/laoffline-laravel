@extends('layouts.app')

@section('content')
    <div id="app">
        <link-companies-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></link-companies-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection