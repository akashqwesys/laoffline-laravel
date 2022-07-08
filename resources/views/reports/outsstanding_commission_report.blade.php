@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <outstanding-commission-component :report-type="'{{ $employees['type'] }}'"></outstanding-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
