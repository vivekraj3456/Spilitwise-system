<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 min-h-screen text-slate-900">
    <div class="min-h-screen flex flex-col items-center py-6 pt-12 sm:pt-24 px-4 sm:justify-start">
        <div class="flex flex-col items-center gap-3 mb-8">
            <a href="/" class="flex flex-col items-center gap-4 group">
                <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-splitwise shadow-md transition-transform group-hover:scale-105 duration-300">
                    <x-application-logo class="h-8 w-8 fill-white" />
                </span>
                <div class="text-center">
                    <p class="text-2xl font-bold tracking-tight text-slate-900">Expense Splitter</p>
                    <p class="text-sm font-medium text-slate-500 mt-1">Smart &middot; Fair &middot; Effortless</p>
                </div>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white border border-slate-200 shadow-sm rounded-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
