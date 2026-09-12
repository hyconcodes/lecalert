<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found - {{ config('app.name', 'LecAlert') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900 flex items-center justify-center">
    <div class="text-center px-4">
        <div class="inline-flex items-center justify-center size-24 rounded-full bg-indigo-100 dark:bg-indigo-500/20 mb-6">
            <span class="text-5xl font-bold text-indigo-600 dark:text-indigo-400">404</span>
        </div>
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Page Not Found</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mb-8 max-w-md mx-auto">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                Go Home
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-6 py-3 text-sm font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    Dashboard
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
