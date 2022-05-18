@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-payment-outward-component></insert-payment-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
