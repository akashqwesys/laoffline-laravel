@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <percentage-evaluate-turnover-component ></percentage-evaluate-turnover-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
