@extends('layouts.app')

@section('content')
    <div id="app">
        <create-permission-component></create-permission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection