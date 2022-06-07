@extends('layouts.app')

@section('content')
    <div id="app">
        <inward-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></inward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
