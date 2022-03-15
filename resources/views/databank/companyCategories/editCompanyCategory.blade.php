@extends('layouts.app')

@section('content')
    <div id="app">
        <create-company-category-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}"></create-company-category-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>    
@endsection