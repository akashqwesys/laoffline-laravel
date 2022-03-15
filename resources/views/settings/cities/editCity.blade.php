@extends('layouts.app')

@section('content')
    <div id="app">
        <create-cities-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-cities-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection