@extends('layouts.app')

@section('content')
    <div id="app">
        <view-reference-id-component :id="{{ $employees['id'] ?? 0 }}"></view-reference-id-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
