@extends('layouts.app')

@section('content')
    <div id="app">
        <create-agent-component></create-agent-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection