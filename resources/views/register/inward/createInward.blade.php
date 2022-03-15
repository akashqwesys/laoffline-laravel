@extends('layouts.app')

@section('content')
    <div id="app">
        <create-inward-component></create-inward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
