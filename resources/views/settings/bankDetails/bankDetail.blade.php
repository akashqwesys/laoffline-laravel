@extends('layouts.app')

@section('content')
    <div id="app">
        <bank-details-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></bank-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection