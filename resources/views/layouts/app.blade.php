<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') @else {{ config('app.name', 'Laravel') }} @endif</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="theme-glass antialiased bg-zinc-950 text-white">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        @auth
            @include('layouts.navigation')
        @endauth

        @isset($header)
            <header class="glass-panel border-b border-white/10 lg:pl-72 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="@auth lg:pl-72 @endauth">
            @hasSection('content')
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="app-frame rounded-3xl px-8 py-10 bg-white/5 backdrop-blur-3xl border border-white/10 shadow-2xl shadow-black/40">
                        
                        @if (session('status'))
                            <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 px-5 py-4 text-emerald-300 flex items-center gap-3">
                                <span class="text-xl">✦</span>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-400/30 px-5 py-4 text-red-300">
                                <strong class="font-semibold">Please fix the errors below:</strong>
                                <ul class="mt-2 list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            @else
                @isset($slot) {{ $slot }} @endisset
            @endif
        </main>
    </div>

    <!-- Loading Script -->
    <script>
        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!form.matches('[data-loading-form]')) return;

            const submitter = event.submitter || form.querySelector('[type="submit"]');
            if (submitter) {
                submitter.disabled = true;
                submitter.dataset.originalLabel = submitter.textContent.trim();
                submitter.innerHTML = `<span class="animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full mr-2"></span> ${submitter.dataset.loadingLabel || 'Processing...'}`;
            }
        });
    </script>
</body>
</html>