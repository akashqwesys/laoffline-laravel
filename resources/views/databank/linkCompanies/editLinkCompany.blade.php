@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-link-companies-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-link-companies-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
