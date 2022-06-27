@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <company-commission-component :company="{{ $company }}" :company-type="{{ $type }}" :company-name="'{{ $company_name }}'"></company-commission-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
