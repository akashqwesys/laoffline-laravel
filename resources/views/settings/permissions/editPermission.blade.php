@extends('layouts.app')

@section('content')
    <div id="app">
        <create-permission-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-permission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection