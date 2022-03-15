@extends('layouts.app')

@section('content')
    <div id="app">
        <transport-details-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></transport-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection