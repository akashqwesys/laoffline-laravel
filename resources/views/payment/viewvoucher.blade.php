@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-voucher-component :id="{{ $employees['id'] ?? 0 }}"></view-voucher-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection