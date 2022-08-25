@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-commission-outward-component :from-date="'{{ Session::get('outward_commission_from_date') }}'" :to-date="'{{ Session::get('outward_commission_to_date') }}'"></insert-commission-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
