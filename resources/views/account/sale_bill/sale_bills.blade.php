@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <sale-bill-component :excel-access="{{ $employees['excelAccess'] ?? 0 }}" ></sale-bill-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
