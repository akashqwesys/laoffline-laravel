@extends('layouts.app')

@section('content')
    <div id="app">
        <register-component :inward_outward="{{ $employees['inward_outward'] ?? 0 }}" :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></register-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
