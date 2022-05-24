@extends('layouts.app')

@section('content')
    <div id="app">
        <view-outward-component :id="{{ $employees['id'] ?? 0 }}"></view-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
