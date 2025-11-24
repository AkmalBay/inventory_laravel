<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-data="layout">
    <div class="flex h-screen bg-gray-50 dark:bg-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        @include('layouts.sidebar')

        <div class="flex flex-col flex-1 w-full">
            @include('layouts.navigation')

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">
                    @isset($header)
                        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                            {{ $header }}
                        </h2>
                    @endisset

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>