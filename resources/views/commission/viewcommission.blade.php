@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-commission-component :id="{{ $employees['id'] ?? 0 }}" :fid="{{ $employees['fid'] ?? 0 }}"></view-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection