<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,700,300">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @stack('styles')
  </head>

  <body>
    @yield('content')

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
      $(document).ready(function() {
        $('input[type="checkbox"]').bootstrapSwitch()
      })
    </script>
    @stack('scripts')
  </body>
</html>
