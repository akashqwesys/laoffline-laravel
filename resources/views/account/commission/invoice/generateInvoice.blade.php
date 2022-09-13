@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <div id="app">
        <generate-invoice-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></generate-invoice-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@endsection
