@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-financial-year-component></create-financial-year-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
