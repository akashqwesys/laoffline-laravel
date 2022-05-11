@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <generate-invoice-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></generate-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
