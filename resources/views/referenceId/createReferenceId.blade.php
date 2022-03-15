@extends('layouts.app')

@section('content')
    <div id="app">
        <create-reference-id-component></create-reference-id-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script> 
@endsection