<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LecAlert') }} - Never Miss a Lecture Again</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @fonts
    @vite(['resources/css/app.css'])
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .step-card {
            position: relative;
        }
        .step-card::after {
            content: '';
            position: absolute;
            top: 2rem;
            right: -2rem;
            width: 4rem;
            height: 2px;
            background: #e5e7eb;
        }
        .step-card:last-child::after {
            display: none;
        }
        @media (max-width: 768px) {
            .step-card::after {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-white antialiased">
    {{-- Navbar --}}
    <nav class="fixed top-0 z-50 w-full border-b border-white/10 bg-white/80 backdrop-blur-xl dark:bg-zinc-900/80">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-white">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-zinc-900 dark:text-white">LecAlert</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-zinc-600 hover:text-indigo-600 dark:text-zinc-300">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-zinc-600 hover:text-indigo-600 dark:text-zinc-300">How It Works</a>
                </div>
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-300">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-gradient relative overflow-hidden pt-32 pb-20 lg:pt-40 lg:pb-32">
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 inline-flex items-center rounded-full bg-white/10 px-4 py-1.5 text-sm font-medium text-white backdrop-blur-sm">
                    <span class="mr-2 size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Smart Lecture Reminders for Students
                </div>
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl lg:text-7xl">
                    Never Miss a
                    <span class="text-amber-300">Lecture</span>
                    Again
                </h1>
                <p class="mt-6 text-lg text-indigo-100 sm:text-xl">
                    Stay organized, manage your lecture schedules, and receive timely reminders before your classes.
                    Built for university students who want to stay on top of their academic game.
                </p>
                <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-600 shadow-lg hover:bg-indigo-50">
                            Get Started Free
                            <svg class="ml-2 size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl border border-white/30 px-8 py-3.5 text-base font-semibold text-white hover:bg-white/10">
                            Log in
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Decorative wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold text-zinc-900 sm:text-4xl">Everything you need to stay on track</h2>
                <p class="mt-4 text-lg text-zinc-500">Simple, powerful tools designed specifically for students</p>
            </div>
            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="feature-card rounded-2xl border border-zinc-200 bg-white p-8 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Smart Scheduling</h3>
                    <p class="mt-2 text-sm text-zinc-500">Organize all your lectures in one place with an intuitive calendar view.</p>
                </div>

                <div class="feature-card rounded-2xl border border-zinc-200 bg-white p-8 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-amber-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Timely Reminders</h3>
                    <p class="mt-2 text-sm text-zinc-500">Receive reminders before your lectures start so you're never late.</p>
                </div>

                <div class="feature-card rounded-2xl border border-zinc-200 bg-white p-8 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-emerald-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Access Anywhere</h3>
                    <p class="mt-2 text-sm text-zinc-500">Use the system on your laptop, tablet, or smartphone.</p>
                </div>

                <div class="feature-card rounded-2xl border border-zinc-200 bg-white p-8 text-center">
                    <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-purple-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-purple-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-semibold text-zinc-900">Easy Management</h3>
                    <p class="mt-2 text-sm text-zinc-500">Add, edit, and manage your lecture schedules with ease.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="how-it-works" class="bg-zinc-50 py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold text-zinc-900 sm:text-4xl">How It Works</h2>
                <p class="mt-4 text-lg text-zinc-500">Get started in 4 simple steps</p>
            </div>
            <div class="mt-16 flex flex-col items-center justify-center gap-8 md:flex-row md:gap-0">
                <div class="step-card flex flex-col items-center text-center md:flex-1">
                    <div class="flex size-16 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white">1</div>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Create an Account</h3>
                    <p class="mt-2 max-w-xs text-sm text-zinc-500">Sign up with your institution email in seconds.</p>
                </div>
                <div class="step-card flex flex-col items-center text-center md:flex-1">
                    <div class="flex size-16 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white">2</div>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Add Your Schedule</h3>
                    <p class="mt-2 max-w-xs text-sm text-zinc-500">Enter your lecture details - course, time, venue.</p>
                </div>
                <div class="step-card flex flex-col items-center text-center md:flex-1">
                    <div class="flex size-16 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white">3</div>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Set Your Reminder</h3>
                    <p class="mt-2 max-w-xs text-sm text-zinc-500">Choose when you want to be reminded before each lecture.</p>
                </div>
                <div class="step-card flex flex-col items-center text-center md:flex-1">
                    <div class="flex size-16 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white">4</div>
                    <h3 class="mt-4 text-lg font-semibold text-zinc-900">Get Notified</h3>
                    <p class="mt-2 max-w-xs text-sm text-zinc-500">Receive timely notifications so you never miss a class.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="hero-gradient relative overflow-hidden rounded-3xl px-8 py-16 text-center sm:px-16">
                <h2 class="text-3xl font-bold text-white sm:text-4xl">Ready to never miss a lecture?</h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-indigo-100">Join students who are already using LecAlert to stay organized and on time.</p>
                <div class="mt-8">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-600 shadow-lg hover:bg-indigo-50">
                            Get Started Free
                            <svg class="ml-2 size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-2">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-white">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-zinc-900">LecAlert</span>
                </div>
                <div class="text-sm text-zinc-500">
                    &copy; {{ date('Y') }} Built by <strong class="text-zinc-700">Hycon</strong>. WhatsApp: <a href="https://wa.me/23447177291" class="text-indigo-600 hover:underline">+23447177291</a>
                </div>
                <div class="flex gap-4 text-sm">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-zinc-500 hover:text-indigo-600">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-zinc-500 hover:text-indigo-600">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
