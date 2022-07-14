@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <outstanding-commission-month-wise-summery-component :report-type="'{{ $employees['type'] }}'"></outstanding-commission-month-wise-summery-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
