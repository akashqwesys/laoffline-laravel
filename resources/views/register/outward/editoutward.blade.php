@extends('layouts.app')

@section('content')
    <div id="app">
        <edit-outward-component :id="{{ $employees['editedId'] ?? 0 }}"></edit-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
