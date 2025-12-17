{{--
      Main Application Layout

      This is the master layout that all pages extend. It provides:
      - The HTML document structure (doctype, head, body)
      - Common elements (header, footer)
      - The @yield('content') section where page content is injected

      Usage in other views:
      @extends('layouts.app')
      @section('content')
          <!-- page content here -->
      @endsection
  --}}
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Page title - can be overridden by child views using
  @section('title') -->
    <title>@yield('title', config('app.name', 'Repository
  Onboarder'))</title>

    <!-- Vite compiles and serves our CSS and JS assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<!-- Site header with navigation -->
<x-header />

<!-- Main content area - each page injects content here -->
<main>
    @yield('content')
</main>

<!-- Site footer -->
{{-- <x-footer /> --}}
</body>
</html>
