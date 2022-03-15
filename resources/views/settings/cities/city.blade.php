@extends('layouts.app')

@section('content')
    <div id="app">
        <cities-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></cities-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection