@extends('layouts.app')

@section('content')
    <div id="app">
        <countries-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></countries-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection