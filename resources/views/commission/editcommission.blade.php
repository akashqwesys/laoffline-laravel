@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <add-commission-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></add-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection