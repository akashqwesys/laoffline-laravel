@extends('layouts.app')

@section('content')
    <div id="app">
        <create-bank-details-component></create-bank-details-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection