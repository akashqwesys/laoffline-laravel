@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <payment-register-component ></payment-register-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
