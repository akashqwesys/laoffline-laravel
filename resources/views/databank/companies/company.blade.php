@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <company-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}" ref="ccp"></company-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
