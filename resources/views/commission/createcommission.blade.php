@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <create-commission-component :employee-name="'{{ $employees->username }}'"></create-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
