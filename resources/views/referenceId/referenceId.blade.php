@extends('layouts.app')

@section('content')
    <div id="app">
        <reference-id-component></reference-id-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
