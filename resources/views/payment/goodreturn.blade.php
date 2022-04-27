@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <good-return-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}"></good-return-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
