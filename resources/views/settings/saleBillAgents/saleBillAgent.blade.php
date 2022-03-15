@extends('layouts.app')

@section('content')
    <div id="app">
        <sale-bill-agent-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></sale-bill-agent-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection