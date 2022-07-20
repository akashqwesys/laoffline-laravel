@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <daily-commission-component></daily-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
