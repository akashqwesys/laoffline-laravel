@extends('layouts.app')

@section('content')
    <div id="app">
        <create-product-component></create-product-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection