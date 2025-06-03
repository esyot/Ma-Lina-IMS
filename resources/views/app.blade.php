<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }}</title>
  <link rel="icon" href="{{ asset('assets/images/ma-lina-logo.png') }}" type="image/x-icon">
  @vite(['resources/js/app.js'])  <!-- Correct way to load your Vite assets -->
  @inertiaHead                     
</head>
<body>
  @inertia                        
</body>
</html>
