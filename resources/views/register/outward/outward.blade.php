@extends('layouts.app')

@section('content')
    <div id="app">
        <outward-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
