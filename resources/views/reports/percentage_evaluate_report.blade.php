@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <percentage-evaluate-component ></percentage-evaluate-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
