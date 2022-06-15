@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <consolidate-monthly-sales-component ></consolidate-monthly-sales-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
