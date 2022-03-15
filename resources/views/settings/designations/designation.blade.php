@extends('layouts.app')

@section('content')
    <div id="app">
        <designation-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></designation-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection