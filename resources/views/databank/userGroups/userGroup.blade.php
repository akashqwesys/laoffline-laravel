@extends('layouts.app')

@section('content')
    <div id="app">
        <user-group-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></user-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
