@extends('layouts.app')

@section('content')
    <div id="app">
        <agent-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></agent-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection