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

<body class="theme-glass antialiased bg-gradient-to-br from-slate-950 via-zinc-950 to-slate-950 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[radial-gradient(at_top_right,#10b98120_0%,transparent_50%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(at_bottom_left,#f9731620_0%,transparent_50%)]"></div>

        <div class="flex items-center gap-3 mb-8 z-10">
            <a href="/" class="flex items-center gap-3 group">
                <span class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-emerald-400 to-teal-500 shadow-2xl shadow-emerald-500/50 transition-transform group-hover:scale-110 duration-300">
                    <x-application-logo class="h-8 w-8 fill-white drop-shadow" />
                </span>
                <div>
                    <p class="text-2xl font-bold tracking-tighter text-white">Expense Splitter</p>
                    <p class="text-sm text-emerald-300/80">Smart • Fair • Effortless</p>
                </div>
            </a>
        </div>

        <div class="app-frame w-full sm:max-w-md mt-4 px-8 py-10 sm:py-12 rounded-3xl backdrop-blur-2xl bg-white/5 border border-white/10 shadow-2xl shadow-black/50">
            {{ $slot }}
        </div>
    </div>
</body>
</html>