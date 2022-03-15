@extends('layouts.app')

@section('content')
    <div id="app">
        <create-company-type-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-company-type-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection