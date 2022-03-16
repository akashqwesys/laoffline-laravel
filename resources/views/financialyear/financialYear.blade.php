@extends('layouts.app')

@section('content')
    <div id="app">
        <financial-year-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></financial-year-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
