@extends('layouts.app')

@section('content')
    <div id="app">
        <view-company-component :id="{{ $employees['editedId'] ?? 0 }}"></view-company-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
