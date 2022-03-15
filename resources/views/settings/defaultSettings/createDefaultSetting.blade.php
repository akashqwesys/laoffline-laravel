@extends('layouts.app')

@section('content')
    <div id="app">
        <create-default-settings-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-default-settings-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection