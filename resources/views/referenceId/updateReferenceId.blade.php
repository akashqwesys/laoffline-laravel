@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <update-reference-id-component :id="{{ $employees['id'] ?? 0 }}"></create-update-id-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
