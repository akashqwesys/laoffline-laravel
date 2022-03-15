@extends('layouts.app')

@section('content')
    <div id="app">
        <create-user-group-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-user-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection