@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-salebill-outward-component></insert-salebill-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
