<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $currentRole = strtoupper(auth()->user()->role ?? 'admin');
    $routeName = request()->route()?->getName() ?? 'dashboard';
    $pageTitleFromSection = trim($__env->yieldContent('page_title'));
    $pageSubtitleFromSection = trim($__env->yieldContent('page_subtitle'));

    $resolvedTitle = match ($routeName) {
        'dashboard' => 'Dashboard',
        'profile.edit' => 'Profile',
        default => \Illuminate\Support\Str::headline(str_replace('.', ' ', $routeName)),
    };

    if ($pageTitleFromSection !== '') {
        $resolvedTitle = $pageTitleFromSection;
    }

    $resolvedSubtitle = match ($routeName) {
        'dashboard' => 'Ringkasan aktivitas aplikasi.',
        'profile.edit' => 'Kelola informasi akun Anda.',
        default => 'Kelola data aplikasi dengan cepat.',
    };

    if ($pageSubtitleFromSection !== '') {
        $resolvedSubtitle = $pageSubtitleFromSection;
    }

    $documentTitle = trim($__env->yieldContent('title'));
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ $documentTitle !== '' ? $documentTitle . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap (for existing CRUD pages that use Bootstrap classes) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F6F0E8] antialiased">
    <div class="min-h-screen lg:flex">
        @include('layouts.navigation')

        <main class="min-w-0 flex-1 p-4 md:p-5 lg:p-5">
            <section
                class="rounded-[24px] border border-[#1F1B16]/10 bg-white/90 px-5 py-5 shadow-[0_15px_30px_rgba(219,95,34,0.08)] md:px-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-[20px] font-semibold md:text-[24px]">{{ $resolvedTitle }}</h1>
                            <p class="mt-1 text-[13px] text-[#1F1B16]/60 md:text-[14px]">{{ $resolvedSubtitle }}</p>
                        @endisset
                    </div>

                    <div class="flex items-center gap-2 md:gap-3">
                        <span
                            class="inline-flex rounded-full bg-[#F8F2E8] px-3 py-1 text-[12px] font-bold uppercase text-[#B45A23]">
                            {{ $currentRole }}
                        </span>

                        @if (Route::has('logout'))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="inline-flex rounded-lg border border-[#1F1B16]/45 px-3 py-1.5 text-[13px] font-medium text-[#1F1B16] transition hover:bg-[#1F1B16] hover:text-white md:px-4 md:py-2">
                                    Logout
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </section>

            <div class="mt-5">
                @hasSection('content')
                    @yield('content')
                @elseif (isset($slot))
                    {{ $slot }}
                @endif
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const forms = document.querySelectorAll('form.needs-validation');

            forms.forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
