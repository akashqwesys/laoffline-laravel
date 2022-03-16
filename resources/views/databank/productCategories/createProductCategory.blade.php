@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-product-category-component></create-product-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
