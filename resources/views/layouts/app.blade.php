<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8" />

    <meta name="application-name" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    {{ $meta ?? '' }}

    @unless(isset($meta))
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:image" content="{{ url('/og-image.png') }}">
        <meta property="og:type" content="website">
    @endunless

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased h-full">
    {{ $slot }}


    @livewire('notifications')

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
