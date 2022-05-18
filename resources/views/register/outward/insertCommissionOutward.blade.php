@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-commission-outward-component></insert-commission-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
