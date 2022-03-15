@extends('layouts.app')

@section('content')
    <div id="app">
        <create-fabric-group-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-fabric-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection