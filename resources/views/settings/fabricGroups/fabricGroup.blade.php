@extends('layouts.app')

@section('content')
    <div id="app">
        <fabric-group-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></fabric-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection