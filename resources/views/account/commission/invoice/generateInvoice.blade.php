@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <generate-invoice-component ></generate-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
