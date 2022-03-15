@extends('layouts.app')

@section('content')
    <div id="app">
        <create-type-of-address-component></create-type-of-address-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection