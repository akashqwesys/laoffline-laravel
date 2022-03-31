@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <reference-id-component :employee-id="{{ $employees['employee_id'] }}"></reference-id-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
