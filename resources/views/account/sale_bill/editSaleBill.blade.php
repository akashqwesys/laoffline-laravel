@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <edit-sale-bill-component scope="{{ $employees['scope'] }}" :id="{{ $employees['editedId'] ?? 0 }}" global_fy_start_date="{{ session()->get('user')->financial_year_start_date }}" global_fy_end_date="{{ session()->get('user')->financial_year_end_date }}"></edit-sale-bill-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
