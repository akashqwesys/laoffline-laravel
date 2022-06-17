@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <commission-register-component ></commission-register-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
