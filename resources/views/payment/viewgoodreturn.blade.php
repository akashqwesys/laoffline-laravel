@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-good-return-component :id="{{ $employees['id'] ?? 0 }}"></view-good-return-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection