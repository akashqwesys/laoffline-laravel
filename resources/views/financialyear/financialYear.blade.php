@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <financial-year-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></financial-year-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
