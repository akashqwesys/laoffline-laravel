@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-inward-component :id="{{ $employees['editedId'] ?? 0 }}" scope="{{ $employees['scope'] }}" :type="{{ $employees['inwardType'] }}"></insert-inward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
