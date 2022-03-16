@extends('layouts.app')

@section('content')
    <div id="app">
        <create-financial-year-component></create-financial-year-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection