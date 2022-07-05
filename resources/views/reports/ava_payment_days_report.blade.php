@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <avarage-payment-days-component ></avarage-payment-days-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
