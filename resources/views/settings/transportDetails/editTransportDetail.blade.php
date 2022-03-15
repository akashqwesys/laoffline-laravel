@extends('layouts.app')

@section('content')
    <div id="app">
        <create-transport-details-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-transport-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection