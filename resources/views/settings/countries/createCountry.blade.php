@extends('layouts.app')

@section('content')
    <div id="app">
        <create-countries-component></create-countries-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection