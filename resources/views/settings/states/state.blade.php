@extends('layouts.app')

@section('content')
    <div id="app">
        <states-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></states-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection