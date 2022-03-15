@extends('layouts.app')

@section('content')
    <div id="app">
        <create-countries-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-countries-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection