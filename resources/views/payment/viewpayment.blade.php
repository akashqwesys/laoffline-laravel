@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-payment-component :id="{{ $employees['id'] ?? 0 }}" :fid="{{ $fid }}"></view-payment-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
