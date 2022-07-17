@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <outstanding-invoice-component ></outstanding-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
