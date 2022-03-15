@extends('layouts.app')

@section('content')
    <div id="app">
        <create-states-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-states-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection