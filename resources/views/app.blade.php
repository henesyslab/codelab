@extends('layout')

@section('title', 'Dashboard')
@section('content')

  <h1>@{{ title }}</h1>
@endsection

@section('content')
  <router-view></router-view>
@endsection

@push('css')
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endpush

@push('js')
  <script src="{{ mix('js/app.js') }}"></script>
@endpush
