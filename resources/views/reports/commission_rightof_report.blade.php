@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <commission-rightof-component></commission-rightof-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
