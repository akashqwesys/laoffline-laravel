@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-sale-bill-component ></create-sale-bill-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
