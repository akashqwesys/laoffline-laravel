@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <commission-invoice-right-of-component ></commission-invoice-right-of-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
