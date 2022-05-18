@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-commission-invoice-outward-component></insert-commission-invoice-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
