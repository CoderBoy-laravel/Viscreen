<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Viscreen') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link href="{{ url('/') }}/assets/css/solid.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/brands.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/free.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/light.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/regular.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/toastr.min.css" rel="stylesheet"/>
        <link href="{{ url('/') }}/assets/css/dropzone.min.css" rel="stylesheet"/>
        @stack('css')
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles

    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="max-w-7xl m-auto">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('js')
    </body>
</html>
