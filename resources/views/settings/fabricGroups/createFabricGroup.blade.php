@extends('layouts.app')

@section('content')
    <div id="app">
        <create-fabric-group-component></create-fabric-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection