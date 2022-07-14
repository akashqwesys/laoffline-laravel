@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <salebill-details-component ></salebill-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
