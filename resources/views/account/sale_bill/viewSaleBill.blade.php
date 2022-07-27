@extends('layouts.app')
@section('title', $page_title)
@section('content')
    <div id="app">
        <view-sale-bill-component :sale_bill_id="{{ $employees['sale_bill_id'] }}" :fid="{{ $fid }}"></view-sale-bill-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
