@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <avarage-commission-days-component></avarage-commission-days-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
