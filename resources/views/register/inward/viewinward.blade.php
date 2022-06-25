@extends('layouts.app')

@section('content')
    <div id="app">
        <view-inward-component :id="{{ $employees['id'] ?? 0 }}"></view-inward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
