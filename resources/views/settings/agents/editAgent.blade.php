@extends('layouts.app')

@section('content')
    <div id="app">
        <create-agent-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-agent-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection