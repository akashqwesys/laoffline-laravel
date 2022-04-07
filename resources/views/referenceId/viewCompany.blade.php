@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-company-component :id="{{ $employees['editedId'] ?? 0 }}"></view-company-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
