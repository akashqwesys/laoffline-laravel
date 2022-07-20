@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <commission-collection-component></commission-collection-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
