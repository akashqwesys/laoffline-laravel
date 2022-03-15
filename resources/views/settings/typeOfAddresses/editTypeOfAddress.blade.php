@extends('layouts.app')

@section('content')
    <div id="app">
        <create-type-of-address-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-type-of-address-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection