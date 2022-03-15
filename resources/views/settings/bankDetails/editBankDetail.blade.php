@extends('layouts.app')

@section('content')
    <div id="app">
        <create-bank-details-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-bank-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection