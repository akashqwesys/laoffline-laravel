@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-employee-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-employee-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
