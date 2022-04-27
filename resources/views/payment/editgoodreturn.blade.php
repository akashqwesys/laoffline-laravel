@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <edit-good-return-component :id="{{ $employees['id'] ?? 0 }}"></edit-good-return-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection