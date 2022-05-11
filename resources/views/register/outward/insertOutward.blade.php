@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-outward-component :type="{{ $employees['outwardType'] }}"></insert-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
