@extends('layouts.app')

@section('content')
    <div id="app">
        <create-link-companies-component></create-link-companies-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection