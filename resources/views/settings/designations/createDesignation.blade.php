@extends('layouts.app')

@section('content')
    <div id="app">
        <create-designation-component></create-designation-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection