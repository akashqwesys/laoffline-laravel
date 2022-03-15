@extends('layouts.app')

@section('content')
    <div id="app">
        <create-sale-bill-agent-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-sale-bill-agent-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection