@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-inward-component :type="{{ $employees['inwardType'] }}"></insert-inward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
