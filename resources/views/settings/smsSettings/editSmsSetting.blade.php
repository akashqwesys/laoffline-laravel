@extends('layouts.app')

@section('content')
    <div id="app">
        <create-sms-settings-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-sms-settings-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection