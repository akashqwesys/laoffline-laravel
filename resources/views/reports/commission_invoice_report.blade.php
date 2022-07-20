@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <commission-invoice-component ></commission-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
