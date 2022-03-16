@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-product-sub-category-component></create-product-sub-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
