@extends('layouts.app')

@section('content')
  <div id="app">
    <dashboard-component></dashboard-component>
  </div>
@endsection

@section('js')
  <script src="{{ asset('js/app.js') }}"></script>
@endsection
