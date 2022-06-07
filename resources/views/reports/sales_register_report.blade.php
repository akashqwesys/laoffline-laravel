@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <sales-register-component ></sales-register-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
