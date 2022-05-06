@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-invoice-component ></create-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
