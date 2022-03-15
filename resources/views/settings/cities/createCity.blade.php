@extends('layouts.app')

@section('content')
    <div id="app">
        <create-cities-component></create-cities-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection