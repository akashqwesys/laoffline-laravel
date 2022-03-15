@extends('layouts.app')

@section('content')
    <div id="app">
        <create-user-group-component></create-user-group-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection