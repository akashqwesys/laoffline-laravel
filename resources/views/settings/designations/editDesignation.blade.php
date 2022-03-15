@extends('layouts.app')

@section('content')
    <div id="app">
        <create-designation-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-designation-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection