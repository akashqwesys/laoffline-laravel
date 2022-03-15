@extends('layouts.app')

@section('content')
    <div id="app">
        <create-company-type-component></create-company-type-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection