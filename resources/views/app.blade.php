<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', 'resources/css/app.css', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <!-- Dynamic Colors -->
        @php
            $primaryColor = \App\Models\Setting::get('primary_color', '#007FFF');
            $surfaceColor = \App\Models\Setting::get('surface_color', '#E5F2FF');
        @endphp
        <style>
            :root {
                --primary-color: {{ $primaryColor }};
                --surface-color: {{ $surfaceColor }};

                --color-primary: {{ $primaryColor }};
                --color-surface: {{ $surfaceColor }};
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
