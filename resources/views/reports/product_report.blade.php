@extends('layouts.app')
@section('title', $page_title)
@section('content')
<style >
    @media (min-width: 768px) {
        .accordion-s3 .accordion-head {
            padding: 1rem 2rem 1rem 2.25rem;
            margin: 1rem 0rem;
            background-color: #f6f8f8;
        }
    }
    .accordion-s3 .accordion-head.collapsed .accordion-icon:before {
        font-weight: bold;
    }
    .accordion-s3 .accordion-icon {
        left: 0.5rem;
    }
</style>
    <div id="app">
        <product-report-component></product-report-component>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
