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

<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-splitwise/20 selection:text-slate-900">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        @auth
            @include('layouts.navigation')
        @endauth

        @isset($header)
            <header class="bg-white border-b border-slate-200 lg:pl-72 shadow-sm sticky top-0 z-20">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="@auth lg:pl-72 @endauth">
            @hasSection('content')
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div>
                        
                        @if (session('status'))
                            <div class="mb-6 rounded-xl bg-splitwise-light border border-splitwise/30 px-5 py-4 text-splitwise-dark flex items-center gap-3 shadow-sm">
                                <span class="text-xl">✓</span>
                                <span class="font-medium">{{ session('status') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 rounded-xl bg-danger-light border border-danger/30 px-5 py-4 text-danger shadow-sm">
                                <strong class="font-semibold">Please fix the errors below:</strong>
                                <ul class="mt-2 list-disc pl-5 space-y-1 text-sm">
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
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 pt-24 lg:pt-8 min-h-screen">
                    @isset($slot) {{ $slot }} @endisset
                </div>
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