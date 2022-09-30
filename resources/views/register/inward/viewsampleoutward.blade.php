@extends('layouts.app')

@section('content')
    <div id="app">
        <view-sample-outward-component :id="{{ $employees['id'] ?? 0 }}"></view-sample-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
