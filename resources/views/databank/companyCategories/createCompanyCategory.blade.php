@extends('layouts.app')

@section('content')
    <div id="app">
        <create-company-category-component></create-company-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection