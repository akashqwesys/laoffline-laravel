@extends('layouts.app')

@section('content')
    <div id="app">
        <insert-salebill-outward-component :from-date="'{{ Session::get('outward_sale_bill_from_date') }}'" :to-date="'{{ Session::get('outward_sale_bill_to_date') }}'"></insert-salebill-outward-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
