<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet" />
  </head>
  <body>
    <div id="app">
      <h1>Welcome edit</h1>
    </div>
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>
  </body>
</html>