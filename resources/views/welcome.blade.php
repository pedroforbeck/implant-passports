<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <main class="min-h-screen flex flex-col items-center justify-center px-6">
            <h1 class="text-4xl font-semibold tracking-tight">{{ config('app.name') }}</h1>
            <p class="mt-4 text-gray-600">Em construção.</p>
        </main>
    </body>
</html>
