@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <reports-list-component ></reports-list-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
