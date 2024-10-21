<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- Scripts -->
    @inertiaHead
</head>
<body>
    <div id="app">
        @inertia
    </div>
</body>
</html>
