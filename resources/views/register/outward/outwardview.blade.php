@extends('layouts.app')

@section('content')
    <div id="app">
        <outward-view-component :id="{{ $employees['id'] ?? 0 }}"></outward-view-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
